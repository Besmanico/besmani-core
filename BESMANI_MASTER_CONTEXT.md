# BESMANI MASTER PROJECT CONTEXT & ARCHITECTURE BASELINE

**Version:** 2.1
**Baseline date:** August 19, 2026
**Audience:** BESMANI team, Codex/AI coding agents, architects, developers, reviewers
**Status:** Consolidated master source of truth + architecture/audit baseline
**Repository use:** Repository root as `BESMANI_MASTER_CONTEXT.md`

> **Use this as the master context for BESMANI. Keep it as the project baseline, but do not let it limit your thinking. Challenge our decisions and suggest better solutions when appropriate.**
>
> This document records both deliberate product direction and verified findings from the August 18–19, 2026 code/database/server audit. Do not silently replace verified facts with assumptions. Where current implementation and target architecture differ, preserve compatibility and migrate incrementally.

---

## 1. Executive Summary

BESMANI is not intended to be a collection of disconnected websites. It is an AI-enabled platform and network layer connecting people, providers, businesses, services, products, appointments, referrals, communication, rewards, and personalized customer intent across multiple verticals.

The strategic architecture is:

**One intelligent BESMANI Core + configurable vertical modules + business-specific extensions.**

Current major environments/domains include:

- `besmani.com` — BESMANI Core/platform.
- `beauty.besmani.com` — first major vertical and current large legacy operational domain.
- Vascular Cosmetics — working clinic/medical implementation and reference for the future Clinic vertical.
- Future vertical candidates include Clinic, Marketplace, Services, Travel, Pet, and others.
- Besmo AI is the AI assistant/direction for the ecosystem.

The current system grew organically. Beauty contains substantial legacy data and operational logic, while Core increasingly owns shared capabilities. The correct path is **not a destructive rewrite**. The preferred migration strategy is an additive/strangler architecture: introduce canonical Core domains beside legacy systems, map legacy IDs, add adapters, migrate reads/writes domain-by-domain, reconcile, then retire legacy structures only when safe.

---

## 2. AI / Codex Operating Instructions

Any AI agent, Codex session, or developer using this document must follow these rules:

1. Treat this document as a strong baseline, not a cage.
2. Before building a new feature, determine whether an implementation already exists.
3. Reuse existing assets and proven behavior where sensible.
4. Ask whether a feature belongs in BESMANI Core or is genuinely vertical-specific.
5. Do not force every vertical into the same UX or workflow.
6. Challenge weak decisions when a materially safer, simpler, more scalable, more profitable, or more maintainable alternative exists.
7. Explain tradeoffs when challenging an existing decision.
8. Do not perform destructive production migrations from this document alone.
9. Do not assume legacy IDs from different tables/databases represent the same entity.
10. Never trust browser-supplied numeric user/patient/business IDs as authorization.
11. Do not expose secrets, `.env` values, passwords, API keys, private keys, or PHI.
12. Preserve backward compatibility while canonical domains are introduced.
13. Prefer explicit domain services, repositories/adapters, policies, APIs, and migration mappings over new direct cross-database coupling.
14. Do not add AI merely for branding. Use AI where it reduces steps, improves matching/discovery, captures customer intent, prepares providers, automates repetitive work, or produces measurable value.
15. New deliberate founder/team decisions supersede older assumptions; material conflicts must be flagged.

### Codex implementation gate

Codex may perform read-only audits, tests, documentation, refactors with no behavior change, and prepare proposed migrations.

Do **not** ask Codex to execute production canonical migrations until at minimum:

- canonical ERD is reviewed;
- dependency map is sufficiently complete;
- legacy statuses/value dictionaries are mapped;
- identity reconciliation strategy is approved;
- medical/PHI boundary is confirmed;
- migration/reconciliation tests exist;
- rollback/backup strategy is verified.

---

## 3. Product Philosophy

Think of BESMANI as:

**Shared Core + Configurable Vertical Modules + Business-Specific Features**

Shared capabilities should generally be implemented once:

- identity/authentication;
- accounts and profiles;
- business/entity model;
- locations;
- memberships/staff relationships;
- provider profiles/capabilities;
- service catalog;
- service offerings;
- availability and booking;
- referrals/network;
- BC/rewards;
- messaging;
- notifications;
- commerce/payments;
- media;
- reviews;
- analytics/audit;
- AI access/action contracts;
- administration and permissions.

Vertical-specific domains extend Core rather than duplicate it.

### North-star mental model

```text
                           BESMANI
                              |
               +--------------+--------------+
               |              |              |
            PEOPLE        BUSINESSES      PROVIDERS
               |              |              |
               +--------------+--------------+
                              |
                         BESMANI CORE
                              |
       +-------------+--------+--------+-------------+
       |             |        |        |             |
    NETWORK        BOOKING    AI    COMMERCE   COMMUNICATION
       |             |        |        |             |
    REFERRAL       SERVICES  BESMO   PRODUCTS      MESSAGES
       |                      |        |
       +----------------------+--------+
                              |
                   PERSONALIZED EXPERIENCE
                              |
          +-------------------+--------------------+
          |                   |                    |
        BEAUTY              CLINIC             FUTURE
                                                VERTICALS
```

---

## 4. Strategic Product Engines

### 4.1 Network + Referral + Introduction

Referral/network is a core economic and growth engine, not a minor add-on.

BESMANI should support:

- provider → customer referrals;
- provider → provider referrals;
- customer → provider referrals;
- business → business introductions;
- relationships/network;
- messaging;
- discovery;
- incentives/rewards;
- referral visibility and lifecycle.

### 4.2 AI-Powered Customer Intent / Style

For Beauty, users should be able to express or generate desired outcomes before appointments: hair, nail, beard, style, color, aesthetic concepts, etc.

The final customer intent should connect to My Page, appointment, provider preparation, reference media, and customer preferences. This is intended as a meaningful operational differentiator, not an image-generation novelty.

### 4.2.1 AI Style / Try-On operational requirements

The Beauty implementation must support the full pre-visit loop, not only image generation:

1. Customer captures or uploads selfie/photo/video.
2. Customer explores or selects a desired style/model.
3. Customer saves a final desired look to My Page.
4. The final look can be attached to the relevant appointment.
5. The destination Provider/Business can view only the customer intent/media authorized by that appointment/business relationship.
6. Provider can prepare supplies, products, equipment, or staff before the visit.
7. Appropriate provider/business capture and sharing of before/after or in-location media may be supported with consent/visibility controls.

Candidate canonical concepts, subject to ERD review:

```text
style_sessions
style_media
style_variants
saved_looks
appointment_style_attachments
```

Keep source media, generated media, consent/visibility, retention/deletion, and appointment attachment separable. Do not expose unrelated private customer media to providers.

### 4.3 My Page

My Page is the personal BESMANI hub.

Potential user content includes profile/preferences, saved/final AI styles, favorites, appointments, referrals/network, purchases/services, content, and offers/events. Provider/business variants should adapt to operational needs.

Network visualization should not assume a simple MLM tree. BESMANI relationships are many-to-many.

### 4.4 Events / Promotions

Events and promotions are reusable platform capabilities, not Beauty-only banners.

Examples include Employee Day, Nurses Day, healthcare-worker appreciation days, profession/community appreciation days, weekly kids/family promotions, and business-specific special days.

Support platform-created and business-created promotions with permissions, date/recurrence, eligibility, geography, business/vertical/location/service targeting, and percentage/fixed-price offer mechanics where appropriate. Relevant promotions should surface naturally in discovery, business/profile, My Page, and booking contexts.

Candidate concepts, subject to ERD review:

```text
events / occasions
promotions
promotion_eligibility
promotion_businesses
promotion_service_offerings
promotion_schedules
```

---

## 5. Brand / Vertical Architecture

- **BESMANI** — parent technology/platform brand.
- **Beauty by BESMANI** — first major vertical.
- **Besmo AI** — AI assistant/character/direction.
- **Vascular/Clinic implementation** — current real-world clinic/reference implementation.

Beauty includes Women's Salon, Men's Salon/Barber, Beauty Clinic, Women's Academy, Men's Academy, Beauty Store, products/marketplace, and services/providers.

Future verticals should reinforce BESMANI instead of creating new technology islands.

---

## 6. Business Model Baseline

Current provider subscription planning baseline:

- Basic: $0/month
- Standard: $30/month
- Advanced: $100/month

A three-month introductory free period has been discussed.

These prices are not immutable. Evaluate provider ROI, conversion, AI costs, referral value, advertising demand, and competitive alternatives.

Potential revenue includes subscriptions, advertising, featured placement, promotions, AI premium features, marketplace economics, transaction-related revenue where appropriate, provider tools/services, and future clinic/enterprise solutions.

**Important:** healthcare referral economics require separate legal/compliance analysis. Do not automatically transfer beauty referral incentives into medical referral workflows.

---

# PART II — VERIFIED CURRENT STATE

## 7. Production Server Baseline — August 19, 2026

Verified production host characteristics:

- OS: AlmaLinux 10.2.
- Hostname observed: `72-167-45-19.cprapid.com`.
- cPanel/EasyApache environment.
- Apache/httpd running.
- MariaDB 10.11.18 running.
- Node.js 22.23.2.
- npm 10.9.8.
- Composer 2.10.2.
- PHP 8.2 and PHP 8.3 are both installed/running through PHP-FPM.
- Laravel Core runs successfully under PHP 8.3.33.
- Laravel version: 10.50.0.
- n8n is running as a long-lived Node process.
- n8n also has a PostgreSQL process/database connection.
- No Laravel queue worker/Horizon process was observed.
- No BESMANI-user Laravel scheduler cron was observed.
- cPanel root cron includes daily backup execution.

### Storage snapshot

Observed:

- `/home/besmani/core` ≈ 292 MB
- `/home/besmani/core/storage` ≈ 127 MB
- `/home/besmani/public_html` ≈ 1.1 GB

Public storage symlink:

```text
/home/besmani/public_html/storage
  -> /home/besmani/core/storage/app/public
```

A second storage symlink exists under Core public.

### Laravel environment snapshot

```text
Application Name: Besmani
Laravel: 10.50.0
PHP: 8.3.33
Environment: local
Debug Mode: OFF
Maintenance: OFF

Config: NOT CACHED
Events: NOT CACHED
Routes: NOT CACHED
Views: CACHED
```

### Infrastructure concerns

1. Production currently reports `APP_ENV=local`; target should explicitly use a production environment after compatibility review.
2. Default account CLI PHP points to PHP 8.2, while current installed Composer dependencies require PHP >= 8.3. This caused previous Composer/Artisan failures.
3. Web/CLI/Composer/cron/queue PHP versions should be standardized.
4. Queue/scheduler infrastructure is currently absent for BESMANI Core.
5. Deployment is not currently a conventional Git-based release pipeline.
6. Production optimization/cache strategy is incomplete.
7. Backup existence is not sufficient; retention, off-site copy, scope, and restore testing must be verified.
8. A `storage_backup` directory exists inside the public tree and must be reviewed for web exposure/sensitive content.

Do not change these blindly in production. Handle through a controlled hardening/deployment plan.

---

## 8. Current Production Deployment Shape

Core application location: `/home/besmani/core`

Public web root: `/home/besmani/public_html`

The Core production directory is **not a Git repository**; no `.git` was found under `/home/besmani`. Production is currently deployed/copied independently of Git metadata.

Target direction:

```text
GitHub
   -> CI/checks
   -> staging
   -> tests + migration validation
   -> versioned production release
   -> shared env/storage
```

Do not convert production deployment destructively until the current mapping and rollback process are understood.

---

## 9. Database Inventory — August 19, 2026

Databases observed:

```text
besmani_core
besmani_s001beauty
p001vascular_db
s001clinic_doctorweb2.ir
s002stage_wp_bq2bq
s011stage_besmani_core_stage11
s011stage_stage11_beauty
w001stage_doctor
```

Primary sizes/counts observed:

- `besmani_s001beauty`: 98 tables, ~110.99 MB
- `w001stage_doctor`: 122 tables, ~59.33 MB
- `s001clinic_doctorweb2.ir`: 122 tables, ~44.20 MB
- `besmani_core`: 55 tables, ~3.98 MB
- `p001vascular_db`: 46 tables, ~2.57 MB
- `s002stage_wp_bq2bq`: 12 tables, ~0.78 MB

Core `config/database.php` defines logical connections including `mysql`, `beauty_mysql`, `shop_mysql`, and `travel_mysql`. The current code has substantial direct Eloquent access from Core to `beauty_mysql`.

### Architectural decision

Do **not** continue proliferating direct cross-database Eloquent dependencies. Prefer explicit canonical Core domain ownership, domain APIs/services/repositories, legacy adapters during migration, controlled vertical interfaces, and legacy database IDs retained only through mapping/compatibility.

This does **not** require immediate microservices. A modular monolith/domain-oriented Core is preferred before introducing unnecessary distributed-system complexity.

---

# PART III — IDENTITY, BUSINESS, SERVICES, BOOKING

## 10. Identity — Current Reality

Core has two authenticatable models/realms.

### `App\Models\User`
Used by default `web` guard, Filament/back-office, Spatie/Filament Shield roles and permissions, and administrative workflows.

### `App\Models\MainUser`
Used by `mainUsers` session guard, customer/provider signup/login, user panel, Beauty provider integration, products/services, appointments, referrals, and platform-facing workflows.

Observed counts:

```text
core.main_users     ~1,254
core.users              21
beauty.tbl_users       111
```

Do **not** blindly merge `users` and `main_users` in-place. Current semantics indicate `main_users` is the platform customer/provider population and `users` is the back-office/admin population.

Long-term, BESMANI should have **one canonical human identity model** with roles/scopes/memberships, but migration requires deterministic reconciliation. Never assume `users.id == main_users.id`.

Recommended reconciliation order: verified phone; verified email; explicit legacy linkage; manual conflict review. Use a `legacy_entity_maps` mechanism.

---

## 11. Provider Detection — Current Reality

Provider status is largely represented by `main_users.service_pr == 1`. This is a legacy capability flag and should not become the permanent authorization model.

A real provider relationship depends on business, location, membership, role, specialty, license/verification where applicable, assigned services, and active status.

Target relationship:

```text
user
 -> business_membership
 -> provider_profile
 -> business/location/service assignment
```

`service_pr` should eventually become migration/compatibility input only.

---

## 12. Business / `info_activity` Findings

Core has a `businesses` table/model, but it currently contains very little real production business data and is not the actual source used by much of Beauty/Referral.

Beauty's `info_activity` currently acts as the effective provider/activity/business-profile structure.

Verified facts:

- approximately 1,251 `info_activity` rows;
- approximately 1,027 distinct owners;
- many users own multiple `info_activity` records;
- no owner had multiple active profiles for the same `activity_id` in the tested query;
- multiple profiles generally correspond to different vertical/activity types;
- multi-profile owners often share name/phone/address;
- some multi-profile owners have different names/addresses, so `GROUP BY user_id` is unsafe;
- active `info_activity` rows exist whose Core `main_users` owner no longer exists;
- some provider-flagged users have no active `info_activity`.

Activity distribution observed:

```text
Women's Salon       792
Men's Salon         207
Store                94
Beauty Clinic        74
Women's Academy      32
Men's Academy         9
Men's Store           2
Unknown/0             1
```

### Important semantic conclusion

`info_activity` is **not** safely equivalent to a canonical Business or Branch. It is best understood as a legacy **vertical/activity profile** combining business/profile/location/contact properties.

Current Referral code sometimes calls `info_activity.id` a `business_id`. That naming is semantically inaccurate and must be treated as legacy compatibility.

Target candidates:

```text
businesses
business_types
verticals
business_verticals
business_locations
business_members
membership_roles
provider_profiles
provider_specialties
provider_licenses
business_settings
```

One account may be a customer, be a provider, own/manage businesses, work for multiple businesses/locations, and offer multiple services. One business may operate multiple locations, participate in multiple verticals, have many members/providers, and expose different vertical-specific profiles.

---

## 13. Personnel / Membership Findings

Legacy Beauty contains separate personnel tables such as `clinic_personnels`, `woman_personnels`, and `man_personnels`.

Observed counts:

```text
clinic: total 80, owners 70, distinct colleagues 12
women:  total 407, owners 392, distinct colleagues 17
men:    total 159, owners 150, distinct colleagues 3
```

These are multiple implementations of a shared concept. Target canonical concepts are business members, business/location membership assignments, membership roles, and provider/service assignments. Do not create a new personnel table for every future vertical.

---

## 14. Service Architecture — Verified

Legacy Beauty separates global/service definitions from provider offerings in practice, although not through one canonical schema.

Observed approximate counts:

```text
Women's Salon Offerings   7,559
Men's Salon Offerings     1,023
Clinic Offerings            872
Women's Courses               70
Men's Courses                 38
Products                   1,750
```

Provider offering rows carry provider/user, service, price, duration/time_work, capacity, active state, discount, and BC in some domains.

### Canonical rule

Separate **Service Definition — what the service is** from **Business Service Offering — who offers it, where, at what price/duration/capacity and under what rules**.

Recommended concepts:

```text
service_catalog
business_services
service_prices
service_staff
service_options
```

A service definition should not contain provider-specific price.

### Products

Do not collapse products into services. A higher-level discovery/referral abstraction may treat both as offerings, but persistence/transaction semantics remain distinct:

```text
Services -> Booking
Products -> Order
```

---

## 15. Referral Service Catalog — Current Coupling

Current Referral code dispatches to Beauty-specific models using source/type strings such as `clinic_beauty`, `women_salon`, `man_salon`, `woman_learn`, `man_learn` and synthetic keys such as `clinic:<legacy_id>` or `women_salon:<legacy_id>`.

This is acceptable as a transition compatibility mechanism because IDs from different legacy tables can collide.

Long-term target:

```text
referral -> business_service_id
business_service -> service_catalog_id
```

The Referral domain should not know whether a service originated in Beauty, Clinic, Academy, Travel, Pet, or another vertical.

---

## 16. Booking / Appointment Architecture — Verified

Legacy Beauty has multiple separate booking implementations:

```text
reserves                        -> Women's Salon
man_salon_reserves              -> Men's Salon
clinic_reserves                 -> Beauty Clinic
course_registration_woman_learn -> Women's Academy
course_registration_man_learn   -> Men's Academy
```

Observed booking counts at audit time:

```text
Women's Salon bookings ~12
Men's Salon bookings   ~17
Clinic bookings        ~49
```

This makes the current period favorable for introducing a canonical booking model before transaction volume grows.

### Vascular implementation

The newer Vascular `appointments` design is structurally more modern and includes appointment date/time, status, decimal price/discount, currency, `patient_data` JSON, lifecycle timestamps, `working_schedule_id`, and a real FK to working schedules.

Useful concepts should be reused, but the Vascular schema should not simply be copied wholesale into Core.

### Canonical booking direction

```text
appointments/bookings
appointment_items
appointment_resources
appointment_status_history
business_hours
availability_rules
availability_overrides
bookable_resources
appointment_holds
```

A canonical booking should explicitly reference customer, business, business location, business service, provider/resource, start/end time, timezone, status, monetary snapshot, and source/legacy mapping.

Vertical-specific clinical information belongs in protected extension tables/domains, not the generic operational booking table.

---

# PART IV — REFERRAL, BC, COMMERCE

## 17. Referral Domain — What Is Already Strong

The newer Referral implementation contains engineering decisions worth preserving: dedicated access/workflow services, DB transactions, `lockForUpdate()`, explicit status history, reward/discount snapshots, idempotency-oriented BC creation, three-party visibility, provider/business scope checks, and explicit lifecycle statuses.

Do not throw this implementation away. Evolve it.

### Locked Referral MVP product behavior

The following product behavior is intentionally preserved during canonical migration:

- Regular Referral dashboard is for **User and Provider**; Admin/Owner referral management/configuration belongs in **BESMANI Admin**.
- New Referral flow:
  1. Referral From
  2. Referral Destination
  3. Customer Details
  4. Referral Details
- “Referral From” identifies who is sending the referral.
- Referral Destination uses a real searchable **Combobox/Autocomplete with floating results**, not static inline destination cards.
- Every referral must be visible to sender/referrer, destination Provider/Business, and referred registered Customer.
- The referred Customer sees the referral in their own account even when they did not create it.
- Referral-linked appointment functionality becomes available only after destination accepts the referral.
- User primarily sends referrals and earns BC after completion.
- Provider has broader referral controls and may use BC in future for advertising, promotions, discounts, featured placement, and BESMANI services.

Canonicalization may change internal IDs/tables but must not silently change this behavior.

### Current legacy dependencies

Referral still depends on `MainUser`, `InfoActivity`, `service_pr`, `service_type`, `service_id`, Beauty-specific service dispatch, and ownership inferred through `InfoActivity.user_id`.

### Target Referral references

```text
canonical user_id
canonical business_id
canonical business_location_id
canonical business_service_id
```

Legacy type/id values remain in migration mapping/compatibility during transition.

Fields currently named `referrer_business_id` and `receiver_business_id` may actually hold `info_activity.id`. Do not rename/reinterpret existing production columns without a compatibility plan.

---

## 18. BC / Reward Architecture

BC is currently an **internal BESMANI reward/credit**, not a cryptocurrency/blockchain token.

Preserve the ledger concept, transactional award, completion-state restriction, history/snapshots, and idempotency-oriented creation.

Potential canonical naming:

```text
bc_wallets / reward_accounts
bc_ledger_entries / reward_ledger
```

Ledger remains the accounting source of truth. Balance may be derived/cached.

Current `token_ledger` is too referral-specific because `referral_id` is mandatory. Future sources may include referral, loyalty, promotion, campaign, review, adjustment, reversal, and other approved programs.

Canonical ledger should support explicit/generalized source type/id, idempotency, debit/credit direction, reconciliation, and wallet ownership.

---

## 19. Commerce / Payments

Legacy Beauty has `tbl_orders`. Core has a more modern `orders`/`order_items` direction. Independent canonical payment transaction infrastructure is not yet mature/present.

Target separation:

```text
Products / Variants / Inventory
          |
         Cart
          |
        Order
       /     \
 Order Items  Payments
                |
       auth/capture/failure/refund
```

Order payment status is not a substitute for payment transaction history. Use DECIMAL for money, ISO currency, immutable/historical snapshots where needed, payment/refund entities, and reconciliation.

---

# PART V — AUTHORIZATION, SECURITY, MEDICAL BOUNDARY

## 20. Authentication Configuration

Verified Core guards:

```text
web       -> users provider -> App\Models\User
mainUsers -> main_users provider -> App\Models\MainUser
```

Password brokers exist for both providers but share `password_reset_tokens`. Default auth uses `web` / `users`; MainUser reset flows must explicitly use the appropriate broker where necessary.

---

## 21. Back-Office RBAC

Observed:

```text
roles                  4
permissions          265
model_has_roles        6
model_has_permissions  0
```

Current pivot schema is not fully standard/robust. Do not mutate production tables blindly. Compare installed package migrations/version and prepare safe corrective migration.

### Authorization architecture

Keep **Back-office authorization** (Filament/Shield, global administrative roles/permissions) separate from **Platform authorization** (business ownership, memberships, scoped roles, provider capability, location/service assignments, Laravel policies).

Avoid dozens of global roles like clinic-owner/salon-owner when authority is contextual to a business/location.

---

## 22. Vascular / Medical Protected Domain

BESMANI should not automatically rebuild a complete EMR/EHR. Prefer mature specialized systems where appropriate for clinical records, prescriptions/eRx, insurance eligibility, benefits verification, and regulated clinical workflows.

BESMANI should own relationship, discovery, canonical identity, operational appointments, referrals where legally appropriate, communication/orchestration, customer/provider workflow, and appropriate AI assistance.

### High-priority security findings from prior code audit

Reviewed Vascular code showed concerns including patient/staff routes not visibly protected consistently by server-side auth middleware; endpoints accepting patient IDs from browser/URL; medical/message attachments stored on Laravel public disk; browser `localStorage` used for user/access-token state; Core appointment APIs accepting numeric user IDs.

Immediate requirements before expanding sensitive functionality:

1. Protect patient/staff routes server-side.
2. Authorize each record using current authenticated identity and role.
3. Never grant access merely because a request knows a patient/user ID.
4. Use `/me/...` APIs where appropriate.
5. Move PHI/sensitive attachments to private storage.
6. Serve sensitive files through authorized controlled endpoints.
7. Add PHI access audit logging.
8. Separate generic operational messaging from PHI-bearing clinical messaging.
9. Do not expose PHI through generic Besmo/search/analytics pipelines.
10. Treat client tokens as authentication material only; authorization is server-side.
11. Apply minimum-necessary access principles.
12. Review HIPAA/BAA/compliance obligations with qualified professionals where applicable.

### Ownership boundary

Core owns human identity/auth, global admin roles, Business, Locations, Membership/Staff, Provider public profile, Service catalog, Business service offering, Availability, Operational appointment, Products/orders/payments, Referral/BC, and Generic notifications.

Beauty-specific attributes/portfolio remain Beauty extension. Medical patient record, clinical notes, prescriptions, sensitive insurance payload, and PHI documents/messages remain in the Protected Medical domain. Besmo accesses them only through authorized APIs; analytics excludes PHI unless explicitly governed.

---

# PART VI — TARGET CANONICAL ARCHITECTURE

## 23. Core Architecture Principles

1. One human should converge toward one canonical BESMANI identity.
2. A user may own/manage/work for/provide services for multiple businesses.
3. A business may have multiple locations and vertical capabilities.
4. Business membership is first-class.
5. Provider is a capability/profile + contextual assignment, not a boolean flag.
6. Service definition and business/provider offering are separate.
7. Booking is a universal primitive across verticals.
8. Products/services share commerce/discovery infrastructure where useful but remain distinct catalog entities.
9. Referral references canonical entities, not Beauty table IDs.
10. BC is an auditable reward ledger.
11. Medical PHI stays behind a protected trust boundary.
12. Besmo uses canonical authorized APIs; it does not own duplicate factual tables.
13. Legacy migration is incremental/reversible.
14. Canonical transactional tables use FKs, timestamps, clear statuses, and access-path indexes.
15. Monetary values use DECIMAL + ISO currency.
16. Times use native date/time fields and IANA timezones.
17. Business type/vertical/specialty/gender/taxonomy should be data, not duplicated table families.
18. Public external IDs should avoid exposing sequential internal IDs where that matters.

---

## 24. Canonical Entity Direction

### Identity

```text
users/accounts
user_profiles
user_contacts
user_consents
roles/permissions (admin/global where appropriate)
legacy_entity_maps
```

### Business / Provider

```text
businesses
business_types
verticals
business_verticals
business_locations
business_members
membership_roles
provider_profiles
specialties
provider_specialties
provider_licenses
business_settings
```

### Services

```text
categories
service_catalog
business_services
service_prices
service_staff
service_options
```

### Availability / Booking

```text
business_hours
availability_rules
availability_overrides
bookable_resources
appointments
appointment_items
appointment_resources
appointment_status_history
appointment_holds
```

### Referral / Rewards

```text
referrals
referral_invitations
referral_status_history
service_referral_settings (evolved/canonical)
bc_wallets / reward_accounts
bc_ledger_entries / reward_ledger
```

### Commerce

```text
products
product_variants
inventory
carts
cart_items
orders
order_items
payments
refunds
invoices
subscriptions
```

### Communication / Media / Audit

```text
conversations
messages
notifications
notification_deliveries
notification_preferences
media
reviews
audit_logs
```

### AI Style / Customer Intent

```text
style_sessions
style_media
style_variants
saved_looks
appointment_style_attachments
```

These names are candidate canonical concepts pending ERD review. Media visibility, consent, retention/deletion, and appointment-scoped provider access must remain explicit.

### Events / Promotions

```text
events / occasions
promotions
promotion_eligibility
promotion_businesses
promotion_service_offerings
promotion_schedules
```

These names are candidate canonical concepts pending ERD review. Promotions must support platform/business ownership, eligibility, scheduling/recurrence, targeting, and auditable discount application.

Vertical-specific extensions should reference canonical IDs.

---

## 25. Legacy Mapping Strategy

Never migrate by assuming equal numeric IDs.

Recommended:

```text
legacy_entity_maps
------------------
id
source_system
source_table
source_id
target_entity_type
target_id
migration_batch_id
checksum
migrated_at
verified_at
```

Unique key concept:

```text
(source_system, source_table, source_id, target_entity_type)
```

This allows old Beauty/Vascular/Core IDs to remain traceable without becoming canonical foreign keys.

---

## 26. Business Migration Must Be Confidence-Based

Do not automatically merge all `info_activity` rows with the same `user_id`.

Potential matching evidence: owner identity, normalized business name, phone, address/location, website, activity relationships, existing explicit links, manual review.

Possible outcomes for one owner: one business with multiple vertical profiles; one business with multiple locations; multiple separate businesses; legacy duplicate; orphan/inconsistent record.

Migration must classify rather than guess.

---

## 27. Scheduling Principle

Do not continue pre-generating large vertical-specific time-slot tables. Prefer availability calculation from business hours, staff/resource rules, service duration, overrides, blocked periods, temporary holds, existing appointments, and capacity.

Persist appointments and explicit exceptions, not every theoretical future slot unless proven necessary.

---

# PART VII — MIGRATION / IMPLEMENTATION ROADMAP

## 28. Migration Philosophy

**No destructive in-place rewrite.** Use a compatibility/strangler period.

### Phase A — Safety and governance
- verify backups/restore;
- standardize PHP/runtime;
- establish production environment config;
- protect canonical Git branches;
- establish staging;
- inventory secrets without exposure;
- add deployment checklist;
- address urgent medical/API authorization risks.

### Phase B — Canonical identity foundation
- define canonical identity;
- add legacy identity mappings;
- reconcile `users`, `main_users`, Beauty identities;
- preserve admin/back-office realm during transition.

### Phase C — Business/provider foundation
- canonical businesses, locations, memberships, provider profiles, vertical mappings;
- migrate `info_activity` using confidence/review process.

### Phase D — Service catalog/offering
- canonical service catalog and business service offerings;
- prices, staff assignments, legacy service adapters.

### Phase E — Booking/availability
- canonical appointments;
- availability engine;
- status history;
- resource/provider assignments;
- legacy booking adapters;
- migrate low-volume booking history.

### Phase F — Referral / BC
- retain strong existing workflow logic;
- replace legacy identity/business/service references;
- introduce generalized wallet/ledger;
- preserve reward snapshots/idempotency.

### Phase G — Commerce/payments
- normalize products/orders;
- independent payment/refund transactions;
- immutable financial snapshots/reconciliation.

### Phase H — Medical isolation/hardening
- protected medical trust boundary;
- private files;
- authorization;
- PHI audit;
- controlled integrations.

### Phase I — Besmo/API/search
- canonical authorized APIs;
- AI action permissions;
- no duplicate factual stores;
- no PHI leakage.

### Stage11 validation gate

Before production cutover, Stage11 should be populated from a controlled/sanitized production copy or equivalent reproducible dataset and must pass reconciliation for identity/authentication, provider/business ownership, orphan/ambiguous `info_activity`, memberships/staff, service ownership, appointments/status/time, referral participants/lifecycle, BC ledger balances/reversals, orders/payments, authorization, and medical-boundary tests.

Legacy IDs remain traceable through migration mappings so reconciliation/rollback are possible.

### Phase J — Legacy retirement
- dual-read validation;
- reconciliation;
- disable legacy writes;
- read-only period;
- archive/back up;
- retire only after signoff.

---

## 29. Immediate Priority Matrix

### P0 — Production / Security
- validate backup + restore;
- standardize PHP 8.3 runtime for Core;
- correct production environment configuration safely;
- establish scheduler/queue strategy;
- secure Vascular/PHI routes and attachments;
- remove authorization based on browser-supplied IDs;
- review public backup/storage exposure;
- define safe deployment pipeline.

### P1 — Canonical Platform
- canonical identity mapping;
- canonical Business/Location/Membership/Provider model;
- Service Definition -> Business Service Offering;
- unified Booking/Availability;
- evolve Referral to canonical references;
- generalized BC ledger;
- platform authorization policies.

### P2 — Platform Operations
- AI Style / Customer Intent lifecycle and appointment attachment;
- reusable Events / Promotions capability;
- payments/refunds;
- notification delivery infrastructure;
- observability/logging;
- CI/CD;
- API contracts;
- analytics/audit;
- n8n governance/integration boundaries.

### P3 — Cleanup
- legacy names/flags;
- dead files;
- old profile copies;
- unused DB connections/config;
- duplicated `.env` keys;
- framework/pivot schema cleanup;
- eventual legacy database retirement.

---

# PART VIII — INFRASTRUCTURE TARGET

## 30. Runtime Standard

For BESMANI Core, converge on one supported PHP runtime:

```text
Web PHP      -> 8.3
CLI PHP      -> 8.3
Composer PHP -> 8.3
Cron PHP     -> 8.3
Queue PHP    -> 8.3
```

Do not change versions without package/application testing.

---

## 31. Queue and Scheduler

Production should eventually have Laravel HTTP, Laravel Scheduler, Queue Backend, Queue Workers, Failed Job handling, and Monitoring.

Use systemd/Supervisor where appropriate. Redis may be considered for queue/cache if operationally justified; do not introduce it merely because it is common.

Suitable async jobs include emails, notifications, referral side effects, webhooks, reports, AI jobs, media processing, cleanup, appointment reminders, and integrations.

---

## 32. Deployment Direction

Target:

```text
GitHub
  -> CI
  -> staging
  -> automated checks
  -> approved release
  -> production
```

A mature release structure may use releases, `current` symlink, shared `.env`, and shared storage. Exact tooling is flexible. Core requirements: reproducibility, rollback, migration safety, no dependence on manually edited production files.

---

## 33. Git / Repository Governance

Important Laravel code has existed on non-default branches while default `main` may not always represent integration state.

Required direction:
- identify canonical repository for each deployable;
- establish protected canonical integration/default branch;
- ensure production release commit is traceable;
- require PR/review for high-risk changes;
- tag/releases where useful;
- prevent secrets from entering Git;
- keep this context document at a stable repository-root path.

Do not infer production state solely from Git until deployment traceability is established.

---

# PART IX — DEVELOPMENT RULES

## 34. New Vertical Rule

**No new vertical may create its own independent implementation of shared primitives without an explicit architecture decision.**

Before creating a new user, business, provider, staff, location, service, availability, booking, referral, reward, payment, or notification system ask:

> Is this a BESMANI Core capability with vertical configuration?

Also ask:

> Would forcing this into Core create harmful coupling?

Vertical-specific extensions are allowed and expected where workflows genuinely differ.

---

## 35. Database Rules

For new canonical work:
- use BIGINT UNSIGNED internal relational keys unless a better reason exists;
- use UUID/ULID/public IDs where external exposure benefits;
- use real FKs inside the same integrity boundary;
- explicit mapping for cross-system legacy references;
- DECIMAL for money;
- ISO currency codes;
- native dates/times and explicit timezones where needed;
- index real access paths;
- status histories for important workflows;
- soft deletes only where semantics justify them;
- do not hide first-class relational structure in arbitrary JSON;
- JSON is acceptable for snapshots/metadata/extensions where appropriate.

---

## 36. API Rules

Preferred patterns:

```text
/api/v1/me
/api/v1/me/profile
/api/v1/me/appointments
/api/v1/businesses
/api/v1/businesses/{business}
/api/v1/businesses/{business}/locations
/api/v1/businesses/{business}/services
```

Rules:
- authenticated identity resolved server-side;
- provider/business access requires membership/policy;
- admin access requires explicit authorization;
- medical access adds medical-domain authorization;
- public numeric IDs never act as authorization secrets;
- scoped tokens/permissions where appropriate;
- version external/vertical contracts.

---

## 37. Referral Rules

Preserve workflow service, access service, transactions, row locking, status history, snapshots, idempotency, and three-party visibility.

Improve canonical IDs, business/location/service semantics, generalized reward ledger, explicit incoming/outgoing/customer semantics, and legal/compliance boundaries for medical referrals.

---

## 38. Security Rules

- Never expose `.env` or credentials in logs/issues/AI prompts.
- Never authorize based only on a request-provided entity ID.
- Sensitive files are private by default.
- Customer selfie/video, generated style media, and before/after media require explicit visibility, consent, retention/deletion, and access-control rules appropriate to the data/jurisdiction.
- PHI requires stronger boundary, access logging, and minimum-necessary access.
- Server-side policy is authoritative; frontend hiding is not security.
- Treat localStorage/session data as untrusted client state.
- Add audit trails to sensitive/high-value actions.
- Review external vendors/data flows.
- Separate admin privileges from business-scoped privileges.
- Do not make `service_pr`, `personnel`, or similar flags the permanent authorization model.

---

# PART X — KNOWN TECHNICAL DEBT / OPEN QUESTIONS

## 39. Confirmed / High-Confidence Debt

- Production Core is not Git-managed in-place.
- CLI PHP 8.2 conflicts with current dependencies requiring >= 8.3.
- `APP_ENV` reports `local` in production.
- No BESMANI Laravel scheduler cron observed.
- No Laravel queue worker/Horizon observed.
- Core directly imports many Beauty DB models.
- `info_activity` is overloaded/semantically ambiguous.
- Provider status relies heavily on `service_pr`.
- personnel/service offerings/booking tables are duplicated by vertical.
- Referral uses legacy Beauty business/service references.
- BC ledger is referral-specific and named as token infrastructure.
- payment transaction model is incomplete.
- RBAC pivot schema requires review/correction.
- orphan Beauty profiles and provider users without active profiles exist.
- legacy database integrity cannot rely on cross-database FKs.
- Vascular/medical authorization/storage issues require high-priority hardening.

## 40. Items Still Requiring Verification

Before architecture freeze/implementation:
- full table-by-table mapping;
- exact status/value dictionaries;
- complete raw SQL / `DB::table` / `DB::connection` dependency inventory;
- production-to-Git commit/release mapping;
- actual backup destination/retention/restore success;
- full cron/task inventory across cPanel accounts;
- mail/SMS/Customer.io/n8n integration ownership;
- payment gateway details;
- notification architecture;
- Redis availability/use;
- public accessibility of backup directories;
- staging parity;
- branch governance;
- all Vascular PHI flows;
- business merge confidence rules;
- orphan data reconciliation;
- API contract inventory;
- observability/error monitoring;
- secrets rotation/storage policy;
- AI Style/Try-On consent, retention, provider visibility, media storage policy;
- Events/Promotions eligibility, stacking, recurrence, redemption, discount-accounting rules.

---

# PART XI — TEAM / PROJECT CONTEXT

## 41. Team Baseline

Known team roles:
- Mehdi — Founder
- Mokhtar — Programmer / full-stack development
- Farshid — Designer
- Shahab — CTO

This document supports collaboration between human developers, Codex, ChatGPT, reviewers, and future engineers without requiring repeated reconstruction of project history.

---

## 42. Current Technology Context

Known technologies used across current work include Laravel 10/12 depending on project, Livewire, Filament, Filament Shield / Spatie permissions, Tailwind CSS, Alpine.js, Next.js for newer frontend/staging work, shadcn/ui exploration, MariaDB/MySQL, Node.js, n8n, PostgreSQL for n8n, cPanel/WHM, Apache/PHP-FPM, GitHub, Vite, and accessibility tooling including axe-core in Vascular.

Do not interpret this as a permanent mandate to use every current framework forever. Architecture/domain contracts should outlive framework choices.

---

# PART XI-B — ARCHITECTURE MIGRATION DEFINITION OF DONE

The canonical migration is not complete merely because migrations run. It is complete only when:

- existing users can authenticate;
- provider/business ownership is reconciled;
- every legacy business/activity profile is mapped or explicitly flagged for review;
- staff/membership relationships survive;
- services point to the correct canonical business;
- appointments preserve participants, status, date/time, and monetary snapshots;
- referrals preserve sender/destination/customer visibility and lifecycle;
- BC balances can be reproduced/reconciled from ledger history;
- orders/payments reconcile;
- Beauty and Vascular consume shared Core capabilities without unnecessary duplication;
- medical/PHI boundaries and authorization tests pass;
- Stage11 migration/reconciliation tests pass;
- production rollback is documented and tested to the practical extent possible;
- no secrets or sensitive production dumps are committed;
- post-cutover monitoring confirms stability.

---

# PART XII — FINAL ARCHITECTURAL POSITION

## 43. What BESMANI Should Become

Do not treat BESMANI as another beauty directory.

Do not treat Vascular as an isolated clinic website.

Do not treat referrals as a small feature.

Do not treat AI Style/Intent as a novelty.

Do not rebuild mature regulated infrastructure merely to own every line of code.

BESMANI should become:

> **An AI-enabled network and operating layer connecting demand, people, providers, businesses, services, products, appointments, referrals, rewards, communication, commerce, and personalized intent across specialized verticals.**

The platform should become more useful as more users, providers, businesses, services, products, and relationships participate.

---

## 44. Strong Decisions vs Flexible Decisions

### Strong current decisions

- BESMANI is the core platform.
- Beauty is the first major vertical.
- Shared functionality should be reusable.
- Dashboards can contain shared + vertical-specific components.
- Referral/network is strategically important.
- AI Style/Intent is strategically important for Beauty.
- My Page is an important personal hub.
- Providers should receive relevant customer intent before appointments.
- Identity should work across BESMANI experiences.
- Admin/permissions must be centralized and robust.
- Mature regulated clinical systems should be integrated where practical rather than unnecessarily rebuilt.
- Migration should be incremental, not destructive.
- Medical PHI must remain in a protected trust boundary.

### Flexible / evidence-driven

Pricing; exact table names; long-term frameworks; AI orchestration stack; EMR/eligibility vendors; network visualization; BC economics; dashboard widgets; campaign mechanics; monetization mix; international rollout; exact CI/CD tooling; exact queue/cache infrastructure; microservices vs modular monolith timing.

---

## 45. Final Instruction to Codex / Developers

### Mandatory first response from Codex before architecture coding

After reading this document and inspecting the repository, Codex/developer must first produce a **Gap Report**, not immediately rewrite the application.

The Gap Report should classify current implementation as:
- already implemented correctly;
- partially implemented;
- conflicting with this baseline;
- missing;
- risky/destructive existing or proposed migration;
- unverified assumption requiring code/schema evidence.

It should then propose the canonical ERD/table list, migration batches, compatibility approach, tests, and every proposed destructive operation. Destructive migration or production cutover requires review.

When implementing BESMANI:

1. Understand current implementation before replacing it.
2. Preserve working behavior during migration.
3. Build canonical shared primitives once.
4. Keep vertical-specific behavior modular.
5. Protect security/privacy boundaries.
6. Use explicit ownership and authorization.
7. Keep legacy mappings traceable.
8. Write migration/reconciliation tests.
9. Prefer incremental releases.
10. Document architectural decisions.
11. Challenge unnecessary complexity.
12. Optimize not only for code elegance, but for provider/customer value, growth, retention, referral/network effects, security, operational cost, and future extensibility.

**Default question before creating new architecture:**

> Can this become a reusable BESMANI Core capability without creating harmful coupling?

If yes, build it in Core with vertical configuration/extensions. If no, keep it in the vertical behind a clear contract.

---

# Appendix A — Current Physical Mental Model

```text
                           INTERNET
                              |
                        cPanel / Apache
                              |
             +----------------+----------------+
             |                                 |
        BESMANI Core                     Vertical Apps
         Laravel 10                     Beauty / Vascular
             |
       besmani_core
             |
             +---------- direct legacy access ----------+
             |                                          |
             v                                          v
   besmani_s001beauty                              Other systems
       legacy Beauty
             |
   +---------+---------+------------------+
   |                   |                  |
info_activity      service tables     booking tables
   |                   |                  |
vertical profiles   offerings         reservations
```

Target:

```text
                    BESMANI CANONICAL CORE
                             |
       +----------+----------+----------+----------+
       |          |          |          |          |
    Identity   Business   Services   Booking   Referral/BC
       |          |          |          |          |
       +----------+----------+----------+----------+
                             |
                     Authorized APIs
                             |
            +----------------+----------------+
            |                |                |
          Beauty           Clinic          Future
         extension      protected domain   verticals
```

---

# Appendix B — Audit Snapshot Notice

The numeric counts and server state in this document are a **snapshot from August 18–19, 2026**. They are useful for architecture/migration planning but may change as production data changes. Do not treat changing counts as immutable requirements. Strategic decisions and verified structural relationships are more important than exact snapshot counts.

---

# Appendix C — Document Maintenance

Update this file when any of the following materially changes: canonical domain ownership; identity model; business/provider model; booking/service/referral architecture; medical trust boundary; production deployment; primary databases; repository governance; major provider/customer product strategy; BC/reward model; AI/Besmo architecture; security policy.

For major decisions, add an ADR (Architecture Decision Record) rather than silently rewriting history.

Recommended companion documents:

```text
/docs/architecture/ADR-xxxx-*.md
/docs/architecture/ERD.md
/docs/architecture/LEGACY_MAPPING.md
/docs/architecture/API_CONTRACTS.md
/docs/architecture/MIGRATION_RUNBOOK.md
/docs/security/MEDICAL_TRUST_BOUNDARY.md
/docs/operations/DEPLOYMENT_RUNBOOK.md
/docs/operations/BACKUP_RESTORE_RUNBOOK.md
```

---

**End of BESMANI Master Project Context & Architecture Baseline v2.1**
