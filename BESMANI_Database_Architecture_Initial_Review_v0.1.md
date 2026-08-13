# BESMANI Ecosystem Database Architecture — Initial Technical Review v0.1

## Scope reviewed
- `besmani_core.sql` — 55 tables
- `besmani_s001beauty.sql` — 98 tables
- `p001vascular_db.sql` — 43 tables
- Total: 196 tables, 1,803+ declared columns
- Dumps generated August 12, 2026 on MariaDB 10.11.18

## Executive conclusion
BESMANI should become one logical ecosystem with a single source of truth for identity, businesses, locations, services, booking, commerce, referrals, BC, messaging, notifications and AI-facing data.

This does **not** mean all data must live in one physical database. In particular, Vascular/medical PHI should remain in a strongly isolated medical domain while linking back to the central BESMANI identity/business layer through controlled identifiers.

The current Beauty database contains valuable live/legacy operational data and should be migrated, not rewritten in place.

---

## Major findings

### 1. Identity is fragmented
Current identity concepts exist in:
- Core: `users`, `main_users`
- Beauty: `tbl_users`
- Vascular: `users`

This creates duplicate login/account concepts and makes cross-vertical identity, referrals, booking, messaging and Besmo AI harder.

**Target:** one canonical `users` identity in Core, with profiles/memberships/patient links outside the authentication record.

### 2. Business/provider modeling is incomplete
Core currently has `businesses`, but it is minimal and not yet capable of representing:
- multiple businesses per owner
- multiple locations per business
- multiple staff members
- staff roles per location/business
- multiple verticals/specialties
- business-level settings and subscriptions

Beauty stores much of this indirectly through `info_activity`, personnel tables and user fields.

**Target:** `businesses`, `business_locations`, `business_members`, `business_verticals`, `provider_profiles`, `specialties`, `licenses`.

### 3. Service modeling is duplicated
Examples:
- Core: `services`
- Beauty: `tbl_services`, `clinic_services`, `salon_services`, `man_salon_services`
- Vascular: `services`

The existing tables mix three different concepts:
1. service definition/catalog
2. provider offering
3. price/capacity/duration

**Target:** separate them:
- `service_catalog`
- `business_services`
- `service_prices`
- `service_staff`
- optional vertical extension tables

### 4. Booking is duplicated by business type/gender
Beauty has separate booking/scheduling families:
- `reserves`
- `clinic_reserves`
- `man_salon_reserves`
- many woman/man/clinic personnel day/hour tables

Some availability tables have very high historical IDs, including `woman_personnel_hour_services` over ~500k.

**Target:** one scheduling engine:
- `availability_rules`
- `availability_overrides`
- `appointments`
- `appointment_items`
- `appointment_participants`
- `appointment_status_history`

Gender/business type must be data, not table structure.

### 5. Referential integrity is extremely weak
- Core: no declared foreign keys found
- Beauty: no declared foreign keys found
- Vascular: only one declared FK found (`reply_messages.message_id -> messages.id`)

Many tables have index-like IDs but the DB does not enforce the relationships.

**Target:** explicit foreign keys for stable relational boundaries, with deliberate exceptions only where polymorphism or isolation requires them.

### 6. Beauty has major data-type debt
Examples:
- prices stored as `char(50)`
- dates/times frequently stored as `char`/`varchar`
- IDs/lists stored in text fields
- many status/boolean fields are generic integers

Automated scan found:
- 19 money-like fields stored as text in Beauty
- 73 date/time-like fields stored as text in Beauty

**Target:** `DECIMAL` for money, native date/time types, normalized relationship tables, enums/status dictionaries where appropriate.

### 7. Beauty has almost no secondary indexing
Beauty's dump declares primary keys but essentially no purposeful secondary indexes.

This will become increasingly expensive for provider search, customer lookup, appointments, services, products and Besmo queries.

**Target:** indexes driven by real access paths: business/location/service/status/date/user/provider, plus appropriate composite indexes.

### 8. Referral/BC Core is a useful starting point
The current Core already contains:
- `referrals`
- `referral_invitations`
- `referral_partners`
- `referral_status_histories`
- `service_referral_settings`
- `token_ledger`

This is one of the strongest newer domains and should be evolved rather than discarded.

Important changes:
- settings should attach to a canonical `business_service_id`, not a loose `(service_type, service_id)`
- referral participant IDs should use canonical Core identities/business/location IDs
- BC ledger must support non-referral future sources as well
- wallet/ledger accounting should become the source of truth
- branch IDs should map to a real `business_locations` entity
- DB-level integrity/index design should be tightened

### 9. Vascular must be connected, not merged blindly
Vascular has separate `users`, `personnels`, `patient_infos`, `services`, `prescriptions`, `messages`.

Public/business/provider data can use Core. Medical/clinical data should remain in a protected medical domain.

**Target boundary:**
Core may know the user, business, location and appointment identity.
Medical domain owns patient medical profile, clinical notes, prescriptions, insurance/eligibility data, clinical documents and PHI audit trails.

---

# Target Logical Architecture

## A. BESMANI Core

### Identity & Access
- `users`
- `user_profiles`
- `user_contacts`
- `addresses`
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `sessions` / auth tokens
- `consents`

### Business / Provider
- `businesses`
- `business_locations`
- `business_members`
- `business_verticals`
- `provider_profiles`
- `specialties`
- `provider_specialties`
- `licenses`
- `business_settings`

### Taxonomy & Catalog
- `verticals`
- `categories`
- `service_catalog`
- `business_services`
- `service_prices`
- `service_options`
- `service_staff`

### Scheduling
- `availability_rules`
- `availability_overrides`
- `appointments`
- `appointment_items`
- `appointment_participants`
- `appointment_status_history`

### Commerce
- `products`
- `product_variants`
- `inventory`
- `carts`
- `cart_items`
- `orders`
- `order_items`
- `payments`
- `refunds`
- `invoices`
- `subscriptions`
- `subscription_plans`

### Referral + BC
- `referrals`
- `referral_status_history`
- `referral_invitations`
- `referral_partner_stats` (derived/cache, not source of truth)
- `service_referral_settings`
- `bc_wallets`
- `bc_ledger_entries`

### Communication
- `conversations`
- `conversation_participants`
- `messages`
- `message_attachments`
- `notifications`
- `notification_preferences`

### Media / Search / Analytics
- `media`
- `favorites`
- `reviews`
- `search_events`
- `analytics_events`
- `audit_logs`

### Besmo AI
- `ai_agents`
- `ai_sessions`
- `ai_messages`
- `ai_memories`
- `ai_actions`
- `ai_tool_audit`

Besmo should read/write the same canonical entities through APIs and authorization, not maintain shadow copies of providers/users/services.

---

## B. Beauty Vertical Extensions

Beauty should keep only domain-specific concepts that do not belong in generic Core.

Suggested Beauty-specific tables:
- `beauty_anatomy`
- `beauty_service_attributes`
- `beauty_portfolios`
- `academy_courses`
- `course_sessions`
- `course_enrollments`
- optional beauty-specific product attributes

Do **not** retain parallel structural tables such as:
- woman salon services
- man salon services
- clinic services
- woman personnel
- man personnel
- clinic personnel
- separate reserve tables

Those differences become attributes/relationships in Core.

---

## C. Vascular / Medical Protected Domain

Suggested protected schema/service:
- `medical_patients`
- `patient_identifiers`
- `insurance_policies`
- `eligibility_checks`
- `medical_encounters`
- `clinical_notes`
- `prescriptions`
- `medical_documents`
- `medical_consents`
- `care_team_assignments`
- `medical_messages` (if PHI-bearing)
- `phi_access_logs`

Every medical patient can carry `core_user_id` as a controlled external identity link.

The Core appointment can expose only the minimum safe operational data required by BESMANI. Clinical details remain outside the generic Core/Besmo access path unless authorization explicitly allows them.

---

# High-level Migration Map

## KEEP / EVOLVE
Core:
- roles / permissions infrastructure
- notifications
- referrals family
- parts of carts/orders if still used
- geography/reference data where valid

## MERGE
Identity:
- `core.main_users`
- `core.users`
- `beauty.tbl_users`
- `vascular.users`
→ canonical Core users + profile/membership/patient links

Services:
- Core + Beauty + Vascular service definitions
→ `service_catalog` + `business_services`

Personnel:
- Beauty woman/man/clinic personnel families
- Vascular personnel
→ Core business memberships/provider profiles; medical staffing extension when necessary

Booking:
- Beauty reserve tables + personnel day/hour scheduling
→ central scheduling/appointment model

## MIGRATE / RETIRE AFTER CUTOVER
Beauty:
- duplicated woman/man/clinic service tables
- duplicated personnel tables
- duplicated reserve tables
- denormalized schedule tables once their data is migrated and validated

## ISOLATE
Vascular:
- prescriptions
- patient clinical details
- insurance/clinical documents
- PHI-bearing communication
→ protected Medical domain

---

# Critical design decisions

1. **One person = one BESMANI identity.**
   Roles and memberships determine what the person can do.

2. **A user can belong to multiple businesses.**
   Ownership/employment/provider relationships are memberships, not fields on the user row.

3. **A business can have multiple locations.**
   Existing `branch_id` concepts should become `business_location_id`.

4. **A service definition is not a provider offering.**
   The catalog describes what a service is; `business_services` describes who offers it, where, for how much, how long, and under what rules.

5. **Appointment is universal.**
   Salon, clinic, academy and future verticals should not each get separate booking engines.

6. **BC is ledger-based.**
   Balances are derived/cached; immutable ledger entries are the accounting source of truth.

7. **Medical data has a separate trust boundary.**
   Connected ecosystem does not mean indiscriminate database consolidation.

8. **No destructive migration.**
   Legacy IDs must be mapped through migration tables and verified before any retirement.

9. **Do not redesign the DB engine at the same time.**
   MariaDB 10.11 can remain during the architecture/migration phase to avoid unnecessary simultaneous risk.

10. **APIs become the contract.**
   Beauty, Vascular, Besmo, Admin and future verticals should consume canonical domain APIs rather than directly coupling to legacy table layouts.

---

# Recommended execution phases

## Phase 0 — Freeze architecture
Produce final ERD, entity definitions, ID strategy, status state machines and data ownership rules.

## Phase 1 — Identity + Business foundation
Create canonical users, businesses, locations, memberships and mapping tables.

## Phase 2 — Catalog + Services
Create canonical service catalog and business offerings. Migrate Beauty and Vascular service references.

## Phase 3 — Booking
Build generic availability and appointment engine; migrate historical/current bookings without deleting legacy data.

## Phase 4 — Referral + BC
Refactor existing referral tables to canonical service/business/location/user IDs and introduce wallets/ledger.

## Phase 5 — Commerce
Unify products/cart/order/payment structures.

## Phase 6 — Communication + Besmo
Canonical messaging/notifications and AI access layer.

## Phase 7 — Vascular protected domain
Move/secure patient/clinical data behind strict medical authorization and audit.

## Phase 8 — Legacy retirement
Read-only legacy DBs, reconciliation, then controlled retirement only after validation.

---

# Current architectural verdict

The existing databases contain valuable assets and data, but they evolved as separate applications rather than one ecosystem.

The right strategy is **not**:
- keep adding new cross-database joins, or
- throw everything away and rebuild.

The right strategy is:
**create the canonical BESMANI Core, map legacy entities into it, migrate by domain, keep the system running, and retire duplicate structures gradually.**

This is the foundation for Beauty, Vascular, Besmo AI, Referrals/BC, Marketplace and future verticals such as Travel and Pet.
