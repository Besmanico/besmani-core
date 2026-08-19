# BESMANI — Master Architecture, Database Audit & Implementation Brief
**Status:** Master working specification for Codex and BESMANI developers  
**Updated:** 2026-08-19

This is the architecture source of truth for the current BESMANI Core modernization. It consolidates the recent product decisions and the Aug 18–19 production audit. It is NOT permission to destructively migrate production.

## Product direction
BESMANI is Core-first and modular. Core owns shared Identity/Access, Business Network, Services/Offerings, Scheduling/Appointments, Referrals, BC Economy, Commerce/Payments, My Page, Messaging/Notifications, Events/Promotions, Media, Analytics and AI orchestration. Beauty, Vascular and future Travel/Pet modules add only vertical-specific behavior/data. Shared screens/functions are implemented once and reused; dashboards may combine common Core modules with vertical-specific modules.

## Two primary monetization/differentiation engines
1. **Provider–Customer Network:** discovery, provider/customer connections, referrals/introductions, messaging, network visibility, BC rewards and future promotional/advertising utility. My Page should favor useful cards/list + filters/activity, with optional graph visualization rather than forcing a tree.
2. **AI Style / Try-On / Pre-Visit Intent:** customer can capture/upload selfie/photo/video, select/generate desired hair/nail/beard/beauty style, save final look to My Page and attach/share it with an appointment. The destination provider can review relevant profile/final look before the visit and prepare supplies/staff/equipment.

## Events/promotions
Support platform/business events such as Employee Day, Nurses Day, healthcare-worker appreciation, weekly kids/family offers and business-specific special days. Model reusable promotions with eligibility, recurrence and business/vertical/service/location targeting.

## Healthcare/Vascular boundary
Use Core for appropriate shared identity, business relationship, referral, scheduling and integration metadata. Avoid unnecessary PHI storage. Prefer established EMR/EHR/eligibility integrations for clinical records/sensitive workflows. Do not move PHI into generic Core for architectural uniformity.

# Production audit — confirmed legacy reality

## Databases
- `besmani_core`: 55 tables, ~3.98 MB.
- `besmani_s001beauty`: 98 tables, ~110.99 MB.
- `p001vascular_db`: 46 tables, ~2.57 MB.
- `w001stage_doctor`: 122 tables, ~59.33 MB.
- `s001clinic_doctorweb2.ir`: 122 tables, ~44.20 MB.
- `s002stage_wp_bq2bq`: 12 tables, ~0.78 MB.
- Stage11 DBs exist: `s011stage_besmani_core_stage11`, `s011stage_stage11_beauty`; they appeared unpopulated in the audited table-size query.

Core DB config has default `mysql`, `travel_mysql`, `shop_mysql`, `beauty_mysql`. Vascular remains separate; no equivalent Core `vascular_mysql` was observed in the audited config.

## Identity/authentication
Legacy identity is split across `main_users` (~1,254), Core `users` (~21), and Beauty `tbl_users` (~111). Operational user/provider features heavily use `MainUser` and `Auth::guard('mainUsers')`. Auth config has `web -> users -> App\Models\User` and `mainUsers -> main_users -> App\Models\MainUser`.

**Rule:** `main_users` is not disposable. Identity migration requires compatibility/reconciliation, not sudden replacement. `service_pr=1` currently acts as a practical provider indicator (~1,063 records during audit), but it is legacy evidence, not the future Provider model.

## Business profiles / `info_activity`
Beauty `info_activity` is a critical Legacy Business/Activity Profile structure: ~1,251 active profiles, ~1,027 distinct owners. Activity distribution: Women's Salon 792; Men's Salon 207; Store 94; Beauty Clinic 74; Women's Academy 32; Men's Academy 9; Men's Store 2; unknown/null 1.

No owner had duplicate active profiles for the same activity in the tested grouping; no duplicate owner/activity/address combination was found. Among 154 examined multi-profile owners: 119 had one distinct non-empty name vs 35 multiple; 102 one address vs 52 multiple; 139 one phone vs 15 multiple.

**Critical conclusion:** `info_activity` is a Legacy Business Profile. It is unsafe to convert every row 1:1 into canonical Businesses and unsafe to merge all rows merely by `user_id`. Migration needs deterministic matching plus exception/manual review.

## Staff
Legacy personnel counts observed: clinic 80 rows/70 owners/12 colleague users; women 407/392/17; men 159/150/3. Map toward canonical BusinessMembership/Staff while preserving necessary vertical metadata.

## Services
Legacy services are vertical-specific and often linked by owner/user IDs. Future `ServiceOffering` must belong to canonical `business_id`, optionally `location_id`/staff, and carry business-specific price, duration, availability and referral settings.

## Appointments
Actual structures: Beauty `reserves`, `man_salon_reserves`, `clinic_reserves`; Vascular `appointments`, `working_schedules`. Approximate Beauty booking rows at audit: 12 Women's Salon, 17 Men's Salon, 49 Clinic. Move shared scheduling toward one canonical lifecycle with vertical extensions.

## Referrals
Preserve Referral MVP behavior: User/Provider referral dashboard; Admin/Owner config in BESMANI Admin; flow sections Referral From, Referral Destination, Customer Details, Referral Details; real searchable destination combobox; visibility to sender, destination and referred registered customer; customer sees referral even if they did not create it; referral-linked appointment enabled after destination acceptance; BC earned after completion. Current implementation still depends in places on legacy MainUser/info_activity/owner service discovery and must be adapted without breaking MVP.

## BC
Preserve ledger principle. Generalize BC beyond referral: referral rewards, platform rewards, promotions, advertising/featured placement, BESMANI services, adjustments/reversals and future programs. Do not permanently require every ledger entry to have `referral_id`; use generalized source/reference semantics and idempotent award/reversal logic.

## Commerce
Core has `orders`; Beauty has legacy `tbl_orders`. Future canonical domain: Cart, Order, OrderItem, Invoice, Payment, Refund/Adjustment. Payment tracks gateway/provider, amount, status, external reference, paid timestamp and refund lifecycle.

## Roles/permissions
Observed 4 roles, 265 permissions, 6 `model_has_roles`, 0 `model_has_permissions`. Existing `model_has_permissions.permission_id` is an AUTO_INCREMENT primary key, atypical for a permission pivot; review but do not normalize production ad hoc.

## Runtime/deployment
Observed Laravel 10.50.0, PHP 8.3.33 for inspected app, Composer 2.10.2, MariaDB 10.11.18, Apache/PHP-FPM, PHP 8.2/8.3 FPM, n8n running; Redis/Supervisor not observed in filtered active-service list. Laravel reported Environment `local`, Debug OFF, URL `besmani.com/`, config/events/routes not cached, views cached. Production should eventually identify as `production` after validation.

No BESMANI-user cron and no active Laravel queue worker/Horizon/scheduler were observed. Future reminders, notifications, referrals, BC, Customer.io, promotions and AI/background jobs require an intentional Scheduler/Queue plan. Mixed root/besmani ownership was observed in some application artifacts/symlinks; normalize during deployment hardening, not ad hoc feature work.

# Canonical target

```text
BESMANI CORE
├── Identity & Access: Users, Auth, Roles, Permissions, Profiles
├── Business Network: Businesses, Memberships/Staff, Locations, Verticals
├── Services: Catalog, ServiceOfferings, Pricing, Referral Settings
├── Scheduling: Availability, Working Schedules, Appointments, Staff Assignment
├── Referral Network: Sender, Destination Business, Customer, Offering, Acceptance, Appointment, Completion
├── BC Economy: General Ledger, Rewards, Credits, Promotions, Advertising
├── Commerce: Cart, Order, Items, Invoice, Payment, Refund
├── My Page / Network: Profile, Relationships, Referrals, Appointments, Saved Styles
└── Platform: Messaging, Notifications, Events, Media, Analytics, AI
```

Beauty, Vascular, Travel, Pet and future verticals attach to Core and do not duplicate canonical User, Business, Referral, Appointment, Messaging or Payment infrastructure.

# Canonical data principles
- Person = User/Identity. Customer and Provider are capabilities/relationships, not duplicate mutually exclusive users.
- User belongs to zero/many Businesses through BusinessMembership.
- Business can have multiple members/staff/owners according to authorization.
- Never assume `info_activity.id == business.id`.
- Vertical participation is explicit/extensible.
- Location is independent from Business Profile/Vertical.
- Catalog Service != Business ServiceOffering.
- ServiceOffering points to Business and optionally Location/Staff.
- Referral points to canonical parties, Business and ServiceOffering.
- BC is an auditable/general ledger.
- Keep source media, generated media, consent/visibility, style session and appointment attachment separable.

# Legacy-to-canonical mapping
```text
main_users                         -> User/Identity mapping
users                              -> Admin/internal identity reconciliation
Beauty tbl_users                   -> identity reconciliation; never blindly duplicate
info_activity                      -> LegacyBusinessProfile mapping
activity                           -> Vertical/category mapping
*_personnels                       -> BusinessMembership / Staff
salon/clinic/course service tables -> ServiceCatalog + ServiceOffering
Beauty reserve tables              -> Appointment
Vascular appointments              -> Appointment + vascular extension/integration
working_schedules                  -> Availability/Schedule
referral_*                         -> Canonical Referral domain
token_ledger                       -> General BC Ledger
orders / tbl_orders                -> Canonical Commerce mapping
```

Every mapping should preserve source DB/table, legacy ID, canonical ID, migration batch/version, timestamps, confidence/status for non-trivial matching and exception/review reason.

# Non-destructive migration strategy
`Legacy Production -> Compatibility/Mapping Layer -> Canonical Core -> Stage11 -> Reconciliation -> Compatibility Test -> Controlled Production Cutover -> Legacy retirement after stability.`

1. Review this spec against current GitHub code.
2. Add canonical schema alongside legacy; no initial destructive rename/drop.
3. Build repeatable/idempotent import/mapping commands/services.
4. Populate Stage11 from controlled/sanitized production copy.
5. Reconcile identity, business/orphans, staff, services, appointments, referrals, BC and commerce.
6. Introduce canonical reads module-by-module.
7. If temporary dual-write is needed, make it explicit and monitored.
8. Cut over only after Stage11 passes and rollback exists.
9. Retire legacy only after production stability and retention agreement.

# Codex/developer rules
1. Read current models/migrations/routes/Livewire/Filament/policies/tests before modifying.
2. No production-destructive migration in first implementation.
3. Never guess legacy table semantics.
4. Never treat `main_users` as disposable.
5. Never treat every `info_activity` as an independent canonical Business.
6. Canonical ServiceOffering uses `business_id`, not only owner/user ID.
7. Do not duplicate shared Core modules in verticals.
8. Preserve Referral MVP behavior while migrating identifiers.
9. Preserve BC history and generalize sources.
10. Do not expand PHI storage without explicit review.
11. Use transactions/idempotency for migration, BC, payment and referral completion.
12. New canonical tables use InnoDB, appropriate indexes and stable FKs; do not force FKs onto legacy MyISAM.
13. Platform roles and business-membership roles/scopes are distinct.
14. Keep legacy IDs traceable.
15. Add tests for mapping and critical lifecycle behavior.
16. Never commit `.env`, credentials, API keys or sensitive production dumps.
17. Use branches/PRs; do not develop directly against production.
18. Document unproven assumptions.

# My Page
Customer: profile, appointments, referrals, network, saved/final AI styles, relevant media, BC activity. Provider/business: network relationships, incoming/outgoing referrals, appointments/customer-preparation views subject to authorization, customer final style attached to appointment, events/promotions and business tools.

# AI Style / Try-On direction
Potential concepts after code review: `style_sessions`, `style_media`, `style_variants`, `saved_looks`, `appointment_style_attachments`. Track owner/user, source media, generated/selected result, category, visibility/consent, appointment attachment, destination-business access and retention/deletion. Provider access is scoped to relevant appointment/business; never expose unrelated private media.

# Events / Promotions direction
Potential concepts: Event/Occasion, Promotion, PromotionEligibility, PromotionBusiness, PromotionServiceOffering, recurrence/schedule and targeting. Support platform-created and business-created promotions with permissions.

# Deployment/operations workstream
Separately plan: proper production environment, Laravel Scheduler cron, reliable queue workers, intentional cache/queue backend, supervision/restart, logs/monitoring, ownership/permissions cleanup, config/route cache, backup+restore test, Stage11 procedure and health checks. n8n can orchestrate integrations but does not replace application-domain queues or canonical DB logic.

# Definition of done
Existing users authenticate; provider/business ownership reconciles; every legacy business profile maps or is flagged; staff survives; services point to correct business; appointments preserve parties/status/time; referrals preserve all three visibility scopes/lifecycle; BC balances reproduce from ledger history; commerce reconciles; Beauty/Vascular consume shared Core; Stage11 passes; rollback documented; no secrets exposed; post-cutover monitoring stable.

# Immediate next action for Codex/developer
**Do not immediately rewrite the application.** First inspect the repository and produce a gap report with: already correct / partial / conflicting / missing / risky-destructive existing migrations. Then propose canonical ERD/table list and migration batches. Explicitly identify every proposed destructive operation and wait for review before destructive migration or production cutover.

You may improve implementation details when a stronger solution exists, but preserve product requirements and migration-safety principles. If repository/schema evidence conflicts with an assumption here, report the evidence rather than silently forcing it. The goal is to evolve the real BESMANI platform safely, not to impose a greenfield schema that loses legacy reality.
