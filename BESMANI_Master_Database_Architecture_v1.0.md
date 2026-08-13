# BESMANI Master Database Architecture v1.0
## Canonical Ecosystem Design
**Status:** Architecture baseline for review before implementation  
**Databases reviewed:** BESMANI Core, Beauty, Vascular  
**Primary objective:** One logical BESMANI ecosystem with reusable Core domains and protected vertical extensions.

---

# 1. Architecture Principles

1. One human = one canonical BESMANI identity.
2. A user can own, manage, work for, or provide services for multiple businesses.
3. A business can operate multiple locations and multiple verticals.
4. Service definition and provider offering are separate entities.
5. Booking is universal across Beauty, Medical, Academy and future verticals.
6. Products and services share commerce infrastructure but remain distinct catalog entities.
7. Referral points to canonical people/business/location/service entities.
8. BC uses an auditable ledger; balances are derived/cached.
9. Medical PHI remains behind a protected medical trust boundary.
10. Besmo uses canonical APIs and authorization; it does not maintain duplicate business/user/service tables.
11. Legacy databases are migrated incrementally; no destructive in-place rewrite.
12. All canonical transactional tables use foreign keys, timestamps, clear statuses and access-path indexes.
13. Monetary values use DECIMAL plus ISO currency; never VARCHAR.
14. Times use native date/time fields and IANA timezone names.
15. Business type, gender, vertical and specialty are data/taxonomy — never separate table families.
16. Public IDs should not expose sequential internal IDs where external exposure matters.

---

# 2. Physical Boundary Recommendation

Logical ecosystem:

BESMANI CORE
- Identity / Access
- Business / Provider
- Taxonomy
- Service Catalog
- Scheduling / Appointments
- Commerce
- Referral / BC
- Messaging / Notifications
- Media / Reviews
- Analytics / Audit
- Besmo AI metadata/actions

BEAUTY EXTENSION
- Beauty-only attributes and anatomy
- Portfolio
- Academy extensions
- Beauty-specific catalog attributes

MEDICAL / VASCULAR PROTECTED DOMAIN
- Patient medical identity link
- Insurance / eligibility
- Encounters
- Clinical notes
- Prescriptions
- Medical documents
- PHI-bearing messages
- Medical consents
- PHI access audit

These may be separate physical databases/services while sharing canonical IDs through controlled contracts.

---

# 3. Canonical ID Strategy

Recommended:
- Internal relational PK: BIGINT UNSIGNED
- Public external identifier: UUID/ULID column where public exposure occurs
- Legacy migration mapping: dedicated `legacy_entity_maps`

Core canonical entities never depend on Beauty/Vascular primary keys directly.

`legacy_entity_maps`
- id
- source_system (`core_legacy`, `beauty`, `vascular`)
- source_table
- source_id
- target_entity_type
- target_id
- migration_batch_id
- checksum
- migrated_at
- verified_at

Unique:
(source_system, source_table, source_id, target_entity_type)

---

# 4. Identity & Access Domain

## 4.1 users
Authentication identity only.

- id BIGINT PK
- public_id CHAR(26/36) UNIQUE
- first_name VARCHAR(100)
- last_name VARCHAR(100)
- display_name VARCHAR(150) NULL
- email VARCHAR(255) NULL
- email_normalized VARCHAR(255) NULL
- email_verified_at TIMESTAMP NULL
- phone_country_code VARCHAR(8) NULL
- phone VARCHAR(30) NULL
- phone_e164 VARCHAR(32) NULL
- phone_verified_at TIMESTAMP NULL
- password VARCHAR(255)
- status VARCHAR(30)
- locale VARCHAR(12) NULL
- timezone VARCHAR(64) NULL
- last_login_at TIMESTAMP NULL
- created_at
- updated_at
- deleted_at

Indexes:
- UNIQUE email_normalized where practical
- UNIQUE phone_e164 where product policy allows
- status
- created_at

Do not store:
business ownership, salon type, provider flag, medical status, address, license, service membership.

## 4.2 user_profiles
- id
- user_id FK UNIQUE
- date_of_birth DATE NULL
- gender_code VARCHAR(30) NULL
- avatar_media_id FK NULL
- bio TEXT NULL
- preferred_language VARCHAR(12) NULL
- created_at / updated_at

## 4.3 user_contacts
For additional verified contact points.
- id
- user_id FK
- type (`email`,`phone`,`other`)
- value
- normalized_value
- is_primary
- verified_at
- status
- timestamps

## 4.4 addresses
Reusable address entity.
- id
- owner_type / owner_id OR explicit link tables
- label
- address_line_1
- address_line_2
- city
- region
- postal_code
- country_code
- latitude DECIMAL(10,7)
- longitude DECIMAL(10,7)
- is_primary
- timestamps

Prefer explicit relation tables for high-integrity domains.

## 4.5 roles / permissions
Retain Spatie-compatible RBAC if already used:
- roles
- permissions
- model_has_roles
- model_has_permissions
- role_has_permissions

Global roles should be limited.
Business-specific authority should primarily come from business membership roles.

## 4.6 user_consents
- id
- user_id
- consent_type
- version
- accepted_at
- revoked_at
- source
- metadata_json

---

# 5. Business & Provider Domain

## 5.1 businesses
- id
- public_id UNIQUE
- owner_user_id FK NULL (convenience; ownership also represented by membership)
- legal_name NULL
- display_name
- slug UNIQUE
- business_type_id FK
- status
- description TEXT
- phone_e164 NULL
- email NULL
- website_url NULL
- logo_media_id NULL
- verification_status
- claimed_at NULL
- created_at / updated_at / deleted_at

## 5.2 business_types
Examples:
- beauty_salon
- barbershop
- beauty_clinic
- medical_clinic
- academy
- store
- independent_provider

Fields:
- id
- code UNIQUE
- name
- vertical_id
- status
- sort_order

## 5.3 verticals
- id
- code UNIQUE (`beauty`,`medical`,`travel`,`pet`,...)
- name
- status
- sort_order

## 5.4 business_verticals
Many-to-many where needed.
- business_id
- vertical_id
- is_primary
- status
Composite unique.

## 5.5 business_locations
- id
- public_id
- business_id FK
- name
- slug
- location_type
- phone_e164
- email
- address_line_1
- address_line_2
- city
- region
- postal_code
- country_code
- latitude
- longitude
- timezone
- is_primary
- is_virtual
- status
- timestamps

Indexes:
- business_id,status
- country_code,region,city
- latitude,longitude strategy / spatial index later
- slug

## 5.6 business_members
Canonical staff/owner/admin/provider membership.
- id
- business_id FK
- user_id FK
- business_location_id FK NULL
- membership_role_id FK
- job_title NULL
- employment_status NULL
- starts_at NULL
- ends_at NULL
- is_primary
- status
- timestamps

Unique rules depend on role/location semantics.

## 5.7 membership_roles
Examples:
owner, business_admin, manager, provider, staff, front_desk, instructor.

## 5.8 provider_profiles
Professional properties of a user.
- id
- user_id FK UNIQUE
- professional_title
- headline
- bio
- years_experience SMALLINT NULL
- accepting_clients BOOL
- verification_status
- status
- timestamps

## 5.9 specialties
- id
- vertical_id
- parent_id NULL
- code
- name
- status

## 5.10 provider_specialties
- provider_profile_id
- specialty_id
- is_primary
- years_experience NULL
- verified_at NULL

## 5.11 provider_licenses
- id
- provider_profile_id
- jurisdiction
- license_type
- license_number_encrypted_or_masked
- issued_at
- expires_at
- verification_status
- status

Sensitive professional identifiers must be permission-controlled.

## 5.12 business_settings
Key/value or structured JSON only for non-relational configuration.
Do not use it to replace core relational data.

---

# 6. Taxonomy & Service Catalog Domain

## 6.1 categories
Hierarchical and vertical-aware.
- id
- vertical_id
- parent_id NULL
- code
- name
- slug
- status
- sort_order

## 6.2 service_catalog
Defines WHAT a service is.
- id
- public_id
- vertical_id
- category_id
- parent_service_id NULL
- code NULL
- title
- slug
- short_description
- description
- default_duration_minutes NULL
- image_media_id NULL
- status
- seo_title NULL
- seo_description NULL
- created_at / updated_at

Does not contain provider-specific price.

## 6.3 business_services
Defines WHO offers the service.
- id
- public_id
- business_id
- business_location_id NULL
- service_catalog_id
- title_override NULL
- description_override NULL
- duration_minutes
- capacity SMALLINT DEFAULT 1
- booking_mode
- requires_confirmation BOOL
- is_online BOOL
- status
- visible_from NULL
- visible_until NULL
- timestamps

Indexes:
- service_catalog_id,status
- business_id,status
- business_location_id,status
- business_id,service_catalog_id

## 6.4 service_prices
Supports fixed/range/from pricing.
- id
- business_service_id
- price_type
- amount DECIMAL(12,2) NULL
- min_amount DECIMAL(12,2) NULL
- max_amount DECIMAL(12,2) NULL
- currency CHAR(3)
- starts_at NULL
- ends_at NULL
- status

## 6.5 service_staff
- business_service_id
- business_member_id
- business_location_id NULL
- duration_override_minutes NULL
- price_override_id NULL
- status

## 6.6 service_options
Add-ons/options.
- id
- business_service_id
- name
- option_type
- price_delta DECIMAL
- duration_delta_minutes
- status

## 6.7 service_resources
Links services to bookable resources such as room/device/chair.
- id
- business_service_id
- resource_type_id
- quantity_required
- status

---

# 7. Scheduling & Booking Domain

This replaces Beauty's separate reserve/day/hour table families.

## 7.1 business_hours
Default opening hours.
- id
- business_location_id
- day_of_week TINYINT
- opens_at TIME
- closes_at TIME
- is_closed
- effective_from NULL
- effective_until NULL

## 7.2 availability_rules
Recurring availability for staff/resource/location.
- id
- owner_type (`business_location`,`business_member`,`resource`)
- owner_id
- day_of_week
- start_time
- end_time
- timezone
- effective_from
- effective_until
- slot_interval_minutes NULL
- status

## 7.3 availability_overrides
Exceptions.
- id
- owner_type
- owner_id
- date
- start_time NULL
- end_time NULL
- override_type (`available`,`unavailable`,`holiday`,`blocked`)
- reason NULL
- status

## 7.4 bookable_resources
Rooms, chairs, devices, beds, etc.
- id
- business_location_id
- resource_type_id
- name
- capacity
- status

## 7.5 resource_types
- id
- code
- name
- vertical_id NULL

## 7.6 appointments
Universal booking header.
- id
- public_id
- appointment_number UNIQUE
- customer_user_id FK NULL
- business_id FK
- business_location_id FK
- referral_id FK NULL
- source (`direct`,`referral`,`admin`,`besmo`,`api`)
- status
- booking_status
- payment_status
- starts_at DATETIME
- ends_at DATETIME
- timezone
- party_size DEFAULT 1
- customer_note TEXT NULL
- internal_note TEXT NULL
- booked_by_user_id NULL
- confirmed_at NULL
- cancelled_at NULL
- cancellation_reason NULL
- completed_at NULL
- created_at / updated_at

Indexes:
- customer_user_id,starts_at
- business_id,starts_at
- business_location_id,starts_at
- status,starts_at
- referral_id

## 7.7 appointment_items
Allows one appointment to include multiple services.
- id
- appointment_id
- business_service_id
- provider_business_member_id NULL
- quantity
- scheduled_start
- scheduled_end
- unit_price DECIMAL(12,2) NULL
- currency CHAR(3)
- status
- notes NULL

## 7.8 appointment_participants
- id
- appointment_id
- user_id NULL
- business_member_id NULL
- participant_role
- status

## 7.9 appointment_resources
- appointment_item_id
- bookable_resource_id
- quantity

## 7.10 appointment_status_history
- id
- appointment_id
- from_status
- to_status
- changed_by_user_id NULL
- reason NULL
- created_at

## 7.11 appointment_holds
Temporary anti-double-booking holds during checkout.
- id
- appointment/public correlation
- business_service_id
- staff/resource refs
- starts_at
- ends_at
- expires_at
- created_by_user_id/session
- status

### Booking invariants
- No double booking of the same exclusive staff/resource.
- Availability is calculated, not pre-generated into hundreds of thousands of hour rows.
- Provider/location timezone is authoritative.
- Referral appointment can only be enabled after referral reaches accepted state.
- Appointment history is never rewritten silently.

---

# 8. Referral & BC Domain

## 8.1 referrals
Evolve current Core table.

- id
- public_id
- referral_number UNIQUE
- referrer_user_id NULL
- referrer_business_id NULL
- referrer_location_id NULL
- receiver_business_id
- receiver_location_id NULL
- customer_user_id NULL
- customer_snapshot_id NULL
- business_service_id NULL
- status
- referral_reward_bc
- customer_discount_type
- customer_discount_value
- customer_discount_currency
- terms_snapshot_json
- terms_snapshot_at
- note
- expires_at
- accepted_at
- declined_at
- completed_at
- cancelled_at
- created_by_user_id
- timestamps

Visibility invariant:
exactly the relevant referrer scope, destination business scope, referred registered customer, plus authorized platform administration.

## 8.2 referral_customer_snapshots
For unregistered or immutable referral-time customer contact.
- id
- first_name
- last_name
- phone_e164
- email
- linked_user_id NULL
- timestamps

## 8.3 referral_status_history
- id
- referral_id
- from_status
- to_status
- actor_user_id
- actor_business_id NULL
- reason
- created_at

## 8.4 service_referral_settings
- id
- business_service_id UNIQUE
- enabled
- reward_bc
- customer_discount_type
- customer_discount_value
- currency
- effective_from NULL
- effective_until NULL
- timestamps

## 8.5 referral_invitations
Retain/evolve for destination/customer registration invitations.

## 8.6 bc_wallets
- id
- owner_type (`user`,`business`)
- owner_id
- currency_code default `BC`
- status
- cached_balance BIGINT
- timestamps
Unique owner_type+owner_id+currency.

## 8.7 bc_ledger_entries
- id
- public_id
- wallet_id
- direction (`credit`,`debit`)
- amount BIGINT positive
- transaction_type
- source_type
- source_id
- referral_id NULL
- counterparty_wallet_id NULL
- status
- description
- idempotency_key UNIQUE
- occurred_at
- created_by_user_id NULL
- created_at

Accounting invariant:
ledger is source of truth; cached balance must reconcile.

## 8.8 bc_transfers
Optional transfer workflow if peer/business transfers are supported.
- id
- from_wallet_id
- to_wallet_id
- amount
- status
- debit_ledger_entry_id
- credit_ledger_entry_id
- timestamps

---

# 9. Commerce & Payment Domain

## 9.1 products
Canonical product.
- id
- public_id
- business_id NULL
- category_id
- brand_id NULL
- title
- slug
- description
- status
- timestamps

## 9.2 product_variants
- id
- product_id
- sku
- option_json or normalized option values
- price DECIMAL
- compare_at_price DECIMAL NULL
- currency
- status

## 9.3 inventory_locations
Map product stock to business locations/warehouses.

## 9.4 inventory_levels
- product_variant_id
- inventory_location_id
- quantity_on_hand
- quantity_reserved
- reorder_level

## 9.5 carts
- id
- user_id NULL
- session_key NULL
- currency
- status
- expires_at

## 9.6 cart_items
Supports product/service purchasable types via strict application contract or separate columns.
- id
- cart_id
- item_type
- product_variant_id NULL
- business_service_id NULL
- quantity
- unit_price
- metadata_json

## 9.7 orders
- id
- public_id
- order_number
- user_id
- business_id NULL
- currency
- subtotal
- discount_total
- tax_total
- fee_total
- grand_total
- order_status
- payment_status
- billing_address_snapshot_json
- shipping_address_snapshot_json
- placed_at
- timestamps

## 9.8 order_items
- id
- order_id
- item_type
- product_variant_id NULL
- business_service_id NULL
- appointment_id NULL
- title_snapshot
- sku_snapshot NULL
- quantity
- unit_price
- discount_total
- tax_total
- total

## 9.9 payments
Provider-independent payment record.
- id
- order_id NULL
- appointment_id NULL
- payer_user_id
- amount
- currency
- provider
- provider_payment_id
- status
- authorized_at
- captured_at
- failed_at
- metadata_json
- timestamps

## 9.10 refunds
- id
- payment_id
- amount
- reason
- provider_refund_id
- status
- processed_at

## 9.11 subscription_plans
Supports Basic / Standard / Advanced and future plans.
- id
- code
- name
- price
- currency
- billing_interval
- trial_days
- status
- feature_json / normalized entitlements later

## 9.12 business_subscriptions
- id
- business_id
- subscription_plan_id
- provider
- provider_subscription_id
- status
- trial_starts_at
- trial_ends_at
- current_period_start
- current_period_end
- cancelled_at

## 9.13 invoices
Canonical invoices / receipts.

---

# 10. Messaging & Notification Domain

## 10.1 conversations
- id
- public_id
- conversation_type
- business_id NULL
- referral_id NULL
- appointment_id NULL
- status
- timestamps

## 10.2 conversation_participants
- conversation_id
- user_id
- business_id NULL
- participant_role
- joined_at
- left_at NULL

## 10.3 messages
- id
- conversation_id
- sender_user_id
- sender_business_id NULL
- message_type
- body
- reply_to_message_id NULL
- sent_at
- edited_at NULL
- deleted_at NULL

Medical PHI-bearing messaging must route to protected Medical messaging rather than generic Core.

## 10.4 message_attachments
- id
- message_id
- media_id
- status

## 10.5 notifications
Retain/evolve current Core notifications.
- id
- user_id
- type
- channel
- title
- body
- action_url NULL
- entity_type/entity_id
- read_at
- sent_at
- status

## 10.6 notification_preferences
Per user/channel/type preferences.

## 10.7 notification_deliveries
Provider delivery log for email/SMS/push:
- notification_id
- provider
- external_id
- status
- attempted_at
- delivered_at
- failure_code

---

# 11. Media / Reviews / Favorites

## media
Central metadata; binaries remain object/file storage.
- id
- public_id
- owner_user_id NULL
- business_id NULL
- disk/provider
- path/key
- mime_type
- size_bytes
- width/height NULL
- visibility
- checksum
- status

## reviews
- id
- reviewer_user_id
- business_id
- business_service_id NULL
- appointment_id NULL
- rating
- title NULL
- body
- status
- timestamps

Verified service review can require completed appointment/order.

## favorites
- user_id
- entity_type
- entity_id
- created_at

---

# 12. Besmo AI Domain

Do not place full long-term semantic memory only in relational tables if vector search is needed; canonical factual entities stay relational.

## ai_agents
- id
- code
- vertical_id NULL
- role_scope
- status
- configuration_version

## ai_sessions
- id
- public_id
- user_id NULL
- business_id NULL
- agent_id
- started_at
- ended_at
- context_json

## ai_messages
- id
- session_id
- role
- content
- created_at

## ai_memories
Reference/index metadata to canonical memory/vector system.
- id
- user_id/business_id
- memory_type
- source_entity_type/id
- vector_reference NULL
- content_summary
- sensitivity_class
- expires_at NULL
- status

## ai_actions
Audit AI actions:
- id
- session_id
- agent_id
- actor_user_id
- action_type
- target_type
- target_id
- request_json
- result_json
- authorization_decision
- status
- created_at

## ai_tool_audit
Tool-level calls, permission result, latency/error metadata.

Medical AI access requires a separate medical authorization policy and PHI audit.

---

# 13. Analytics / Audit Domain

## analytics_events
Product analytics events; avoid PHI.
- event_name
- user_id NULL
- business_id NULL
- session_id NULL
- entity refs
- properties_json
- occurred_at

## audit_logs
Security/business mutation audit:
- actor_user_id
- actor_business_id
- action
- entity_type
- entity_id
- before_json NULL
- after_json NULL
- ip_hash / metadata
- created_at

## login_events
Security log for authentication.

---

# 14. Beauty Vertical Extensions

Beauty must NOT recreate identity/business/service/booking.

## beauty_service_attributes
- service_catalog_id UNIQUE
- applicable_gender/audience taxonomy
- anatomy_required
- consultation_required
- treatment_category metadata

## beauty_anatomy
Hierarchical treatment areas.

## beauty_service_anatomy
- service_catalog_id
- anatomy_id

## beauty_portfolios
- id
- business_id/provider_profile_id
- title
- description
- status

## beauty_portfolio_media
- portfolio_id
- media_id
- sort_order

### Academy
## academy_courses
- id
- business_id
- service_catalog_id NULL
- title
- description
- delivery_mode
- status

## course_sessions
- id
- course_id
- business_location_id NULL
- instructor_business_member_id NULL
- starts_at
- ends_at
- capacity
- status

## course_enrollments
- course_session_id
- user_id
- order_id NULL
- status
- enrolled_at
- completed_at NULL

---

# 15. Vascular / Medical Protected Domain

This domain should use stricter permissions, encryption strategy, audit and backup policy.

## medical_patients
- id
- public_id
- core_user_id FK/logical link UNIQUE NULL
- medical_record_number
- status
- created_at
- updated_at

Do not treat `core_user_id` as sufficient authorization to view medical data.

## patient_demographics
Medical-required demographic data only.

## insurance_policies
- patient_id
- payer
- member_id encrypted/tokenized
- group_number encrypted/tokenized
- coverage dates
- status

## eligibility_checks
- patient_id
- insurance_policy_id
- provider
- request_reference
- result_status
- checked_at
- result_payload_encrypted/reference

## medical_encounters
- patient_id
- core_appointment_id NULL
- clinician_id / mapped provider
- encounter_type
- started_at
- ended_at
- status

## clinical_notes
- encounter_id
- author_medical_staff_id
- note_type
- body_encrypted / secure document reference
- signed_at
- amended_from_id NULL
- status

## prescriptions
Migrate Vascular prescriptions into structured protected record.
- patient_id
- encounter_id NULL
- prescriber_id
- medication fields or secure structured payload
- instructions
- status
- prescribed_at

## medical_documents
- patient_id
- encounter_id NULL
- secure_media_reference
- document_type
- sensitivity
- uploaded_by
- status

## medical_consents
- patient_id
- consent_type
- version
- signed_at
- revoked_at

## care_team_assignments
- patient_id
- medical_staff_id
- role
- starts_at
- ends_at

## medical_messages
Only for PHI-bearing clinical communication.

## phi_access_logs
Append-oriented audit:
- actor_id
- patient_id
- resource_type/id
- action
- purpose
- authorization_basis
- occurred_at
- request_metadata

---

# 16. Search Architecture

Besmo and UI search should not issue arbitrary cross-database scans.

Canonical searchable projections:
- business search
- location search
- service offering search
- provider search
- product search

Source of truth remains relational.
A search index can be added later (OpenSearch/Meilisearch/Elasticsearch/etc.) without changing canonical IDs.

Core query example:
service_catalog
→ business_services
→ business_locations
→ provider/service staff
→ availability engine

---

# 17. Important State Machines

## Referral
draft
→ pending
→ accepted / declined / expired / cancelled
accepted
→ appointment_bookable
→ completed
→ reward_pending
→ rewarded

Exact UI names can differ; DB state transitions must be controlled.

## Appointment
hold/draft
→ pending_confirmation
→ confirmed
→ checked_in
→ in_progress
→ completed

Alternative terminal:
cancelled / no_show

## Payment
pending
→ authorized
→ captured
or failed / cancelled
captured → partially_refunded / refunded

## Business
pending_claim / claimed
→ pending_verification
→ active
→ suspended / archived

---

# 18. Legacy Mapping — Initial Classification

## Core legacy

### users
MODIFY/MIGRATE → canonical `users`.

### main_users
MERGE → canonical `users` + profile/business/provider/address mappings.
This appears to carry legacy Beauty-style attributes and must not remain a second identity source.

### businesses
EVOLVE → canonical `businesses`, then populate location/membership/vertical relations.

### services
REVIEW/SPLIT → likely service catalog/content; map into `service_catalog`.

### referrals family
KEEP + EVOLVE → canonical IDs, state machine, location/service offering refs.

### token_ledger
EVOLVE → `bc_wallets` + `bc_ledger_entries`.

### orders/carts
KEEP concepts; migrate to canonical commerce types after code dependency audit.

---

# 19. Beauty Legacy Mapping — Initial

## tbl_users
MERGE → Core `users` plus user profile/contact/address/business membership/provider profile.

## tbl_services
MERGE → `service_catalog`.

## salon_services
## man_salon_services
## clinic_services
MERGE → `business_services` + `service_prices` + optional Beauty attributes.

No future separation by women/men/clinic table.

## reserves
## man_salon_reserves
## clinic_reserves
MERGE → `appointments` + `appointment_items` + status history.

## personnel/day/hour table families
MIGRATE → `business_members`, `provider_profiles`, `availability_rules`, `availability_overrides`.
Generated hour rows should be retired after reconciliation.

## tbl_products
MIGRATE → `products` + `product_variants` + categories/brands/media.

## tbl_orders
MIGRATE → `orders` + `order_items` + payments/address snapshots.

---

# 20. Vascular Legacy Mapping — Initial

## users
MERGE authentication identity → Core `users`.
Medical link → `medical_patients`.

## personnels
SPLIT:
professional identity/business membership → Core
medical employment/clinical permissions → protected Medical domain as needed.

## patient_infos
MIGRATE → `medical_patients` / protected patient profile.

## services
Public service definitions → Core `service_catalog` + `business_services`.

## prescriptions
ISOLATE/MIGRATE → protected Medical `prescriptions`.

## care_teams
REMODEL → provider/staff profiles + medical care-team assignments.

## messages
CLASSIFY:
non-PHI operational → Core conversation/messages
PHI-bearing → Medical messages

---

# 21. Indexing Baseline

Mandatory index families:

users:
- normalized email
- E.164 phone
- status

businesses:
- slug
- owner/status
- business_type/status

locations:
- business/status
- city/region/country
- geo strategy

business_services:
- service/status
- business/status
- location/status

appointments:
- customer/start
- business/start
- location/start
- provider/start via appointment items
- status/start
- referral

referrals:
- referrer user/business
- receiver business
- customer user
- status/created
- business_service
- expiration

ledger:
- wallet/occurred
- source_type/source_id
- referral
- idempotency

orders/payments:
- user/date
- business/date
- order status
- payment external ID unique

---

# 22. Data Quality Rules

- Convert textual money (`char/varchar`) to DECIMAL during migration with quarantine for invalid rows.
- Convert textual dates/time with parsed validation; never silently coerce malformed values.
- Store phone in normalized E.164 plus optional presentation form.
- Store currency as ISO 4217 code.
- Store timezone as IANA name (`America/Los_Angeles`), not fixed offsets.
- No comma-separated FK lists in canonical relational fields.
- No PHP serialized basket as source of truth; migrate to order items.
- No boolean/status mystery integers without documented enum/state definitions.
- No implicit gender-based table split.
- No direct raw PHI in generic analytics or AI memory.

---

# 23. Migration Strategy

Phase A — Discovery freeze
- app-code dependency audit
- route/model/query inventory
- legacy enum/status dictionaries
- row counts/checksums
- identify orphan records

Phase B — Add canonical Core alongside legacy
- users
- businesses
- locations
- memberships
- taxonomy/catalog
- mapping tables

Phase C — Identity migration
- deterministic matching rules
- duplicate review queue
- legacy ID maps

Phase D — Services/business offerings
- map Beauty and Vascular services
- validate provider/location ownership

Phase E — Scheduling
- migrate future bookings first
- migrate availability rules
- retain historical booking data
- reconcile double-booking anomalies
- switch reads/writes via feature flag/API

Phase F — Referral/BC
- convert service refs to canonical business_service
- map branches → locations
- introduce wallets/ledger reconciliation

Phase G — Commerce
- products/orders/payment normalization

Phase H — Medical isolation
- move clinical data to protected domain
- enforce authorization/audit boundary

Phase I — Besmo/API/search
- canonical API read/write
- AI action permissions

Phase J — Legacy retirement
- dual-read validation
- read-only legacy
- backup/archive
- retire only after reconciliation signoff

---

# 24. What Must Be Read From Laravel Before Implementation

Architecture does not need to wait, but safe migration/code generation DOES require:

1. `app/Models/**`
2. `app/Http/Controllers/**` relevant to users/businesses/services/reservations/orders/referrals
3. `routes/web.php`
4. `routes/api.php`
5. `database/migrations/**`
6. `database/seeders/**`
7. `config/database.php` with secrets REDACTED
8. `config/auth.php`
9. custom repositories/services/query classes
10. raw `DB::table`, `DB::connection`, raw SQL usages
11. middleware/guards/permission code
12. jobs/listeners/events that touch legacy tables
13. payment/notification integration abstractions
14. frontend API client/service files only after backend dependency map

UI screenshots/source are NOT required to complete the database architecture.
UI becomes useful when validating workflows, labels, required fields and state transitions.

---

# 25. Codex Gate

Do NOT ask Codex to implement production migrations yet.

Implementation starts only after:
- canonical ERD reviewed
- Laravel dependency audit completed
- legacy status meanings mapped
- migration keys/matching strategy approved
- scheduling edge cases validated
- medical boundary confirmed

Codex may safely perform READ-ONLY code audit now.

---

# 26. Architectural Completion Status

Core domains now defined:
- Identity ✅
- Business / Provider ✅
- Taxonomy / Services ✅
- Booking / Availability ✅
- Referral / BC ✅
- Commerce / Payments ✅
- Messaging / Notifications ✅
- Media / Reviews ✅
- Besmo AI ✅
- Analytics / Audit ✅
- Beauty extensions ✅
- Vascular protected domain ✅
- Migration framework ✅

Still required before implementation freeze:
- exact legacy code dependency map
- exact legacy status/value dictionaries
- duplicate-user matching rules based on live data characteristics
- full legacy table-by-table mapping for all 196 tables
- ERD visual/export
- migration reconciliation test plan
- API contract definitions
