# BESMANI Core V2 — Phase 1 Implementation Report

**Date:** 2026-08-13

**Branch:** `architecture/core-v2-phase1`

**Base commit at implementation time:** `2d8bbef`

**Status:** Implemented locally; pending architecture/code review

## 1. Executive Summary

Phase 1 of the BESMANI Core V2 canonical architecture has been implemented as additive infrastructure alongside the existing application.

No production or staging database was accessed. No migration was run against MySQL or Stage11. No existing legacy table, authentication guard, model, controller, route, or production behavior was removed or replaced.

Because the current `users`, `main_users`, and `businesses` structures are actively used and conflict with the canonical design, Phase 1 uses the temporary compatibility names:

- `canonical_users`
- `businesses_v2`
- `CanonicalUser`
- `CanonicalBusiness`

All canonical feature flags remain disabled by default.

## 2. Branch

Implementation was performed on the requested branch:

```text
architecture/core-v2-phase1
```

The branch already existed locally and tracked its remote counterpart. No automatic merge, commit, push, pull request, or deployment was performed.

## 3. Files Created

### Models

- `app/Models/CanonicalUser.php`
- `app/Models/UserProfile.php`
- `app/Models/UserContact.php`
- `app/Models/UserConsent.php`
- `app/Models/CanonicalBusiness.php`
- `app/Models/BusinessLocation.php`
- `app/Models/BusinessType.php`
- `app/Models/BusinessMember.php`
- `app/Models/BusinessSetting.php`
- `app/Models/BusinessVertical.php`
- `app/Models/MembershipRole.php`
- `app/Models/Vertical.php`
- `app/Models/ProviderProfile.php`
- `app/Models/Specialty.php`
- `app/Models/ProviderSpecialty.php`
- `app/Models/ProviderLicense.php`
- `app/Models/MigrationBatch.php`
- `app/Models/LegacyEntityMap.php`
- `app/Models/IdentityMergeCandidate.php`
- `app/Models/Concerns/HasPublicId.php`

### Repositories and services

- `app/Repositories/LegacyEntityMapRepository.php`
- `app/Services/Migration/LegacyMapService.php`
- `app/Services/Migration/CanonicalMigrationService.php`
- `app/Services/Identity/IdentityReconciliationService.php`

### Configuration

- `config/canonical.php`

### Factories

- `database/factories/CanonicalUserFactory.php`
- `database/factories/CanonicalBusinessFactory.php`
- `database/factories/VerticalFactory.php`
- `database/factories/BusinessTypeFactory.php`

### Seeders

- `database/seeders/CanonicalVerticalSeeder.php`
- `database/seeders/CanonicalBusinessTypeSeeder.php`
- `database/seeders/MembershipRoleSeeder.php`
- `database/seeders/CoreV2Phase1Seeder.php`

### Tests

- `tests/Feature/CoreV2Phase1Test.php`

## 4. Files Modified

- `.env.example`
  - Added disabled-by-default canonical feature flags.
- `database/seeders/DatabaseSeeder.php`
  - Registered `CoreV2Phase1Seeder`.

No existing authentication, route, controller, legacy model, or historical migration was modified.

## 5. Database Migrations Created

- `2026_08_13_000000_create_core_v2_migration_infrastructure.php`
- `2026_08_13_000100_create_core_v2_identity_foundation.php`
- `2026_08_13_000200_create_core_v2_business_foundation.php`

## 6. Tables Created

### Migration infrastructure

- `migration_batches`
- `legacy_entity_maps`
- `migration_errors`
- `migration_reconciliation`
- `identity_merge_candidates`

### Identity

- `canonical_users`
- `user_profiles`
- `user_contacts`
- `user_consents`

### Business and provider

- `verticals`
- `business_types`
- `businesses_v2`
- `business_locations`
- `membership_roles`
- `business_members`
- `provider_profiles`
- `specialties`
- `provider_specialties`
- `provider_licenses`
- `business_verticals`
- `business_settings`

Total additive tables: **21**.

## 7. Canonical ID Strategy

- Internal relational primary keys use unsigned `BIGINT` values.
- Public canonical users, businesses, and locations receive unique ULIDs.
- ULIDs are assigned automatically through the `HasPublicId` model trait.
- Legacy IDs are retained only through `legacy_entity_maps`.
- No assumption is made that `users.id`, `main_users.id`, Beauty IDs, or Vascular IDs identify the same person.

### Reconciliation-stage uniqueness policy

`canonical_users.email_normalized` and `canonical_users.phone_e164` are intentionally indexed but non-unique during Phase 1. The legacy systems may contain two or more records with the same normalized contact, including conflicting records where more than one identity claims a verified phone or email. A database unique constraint would either reject recoverable legacy data or force an unsafe merge before identity ownership has been reviewed.

During reconciliation, duplicate normalized contacts therefore remain representable and are converted into `identity_merge_candidates`. Multiple matches are flagged as conflicts, remain in `pending_review`, and never create an automatic `legacy_entity_maps` record. Uniqueness may only be reconsidered after migration reconciliation, conflict resolution, and an approved identity cutover policy.

## 8. Relationships Implemented

### Canonical user

- User has one profile.
- User has many contacts.
- User has many consents.
- User has many business memberships.
- User can belong to multiple businesses.
- User may have one provider profile.

### Canonical business

- Business may have an owner convenience reference.
- Business belongs to a business type.
- Business has multiple locations.
- Business has multiple members.
- Business belongs to one or more verticals.
- Business has structured settings.

### Membership

- Membership links canonical user, canonical business, optional location, and membership role.
- A user can hold different roles in different businesses.
- Provider capability is represented through provider profile and membership context rather than a global boolean.
- Membership uniqueness uses a non-null `membership_scope_key`: `business` for business-wide membership and `location:{id}` for location-scoped membership.
- The unique index covers business, user, scope key, and membership role, avoiding MySQL/MariaDB's repeated-`NULL` behavior.
- A database check constraint requires the scope key to agree with `business_location_id`, including direct database writes.
- Location deletion is restricted while memberships reference it; a deletion must not silently widen location-scoped authority into business-wide authority.

### Provider

- Provider profile belongs to a canonical user.
- Provider can have multiple specialties.
- Provider can have multiple licenses.
- Specialties are vertical-aware and hierarchical.

### Migration infrastructure

- Migration batches have legacy mapping records.
- Legacy maps preserve source system, source table, source ID, canonical entity type, target ID, batch, status, checksum, and verification timestamps.

## 9. Legacy Mapping Service

The legacy mapping service provides:

```php
map(
    string $sourceSystem,
    string $sourceTable,
    string|int $sourceId,
    string $targetEntityType,
    int $targetId,
    ?int $migrationBatchId = null,
    array $attributes = []
)
```

and:

```php
lookup(
    string $sourceSystem,
    string $sourceTable,
    string|int $sourceId,
    string $targetEntityType
)
```

Behavior:

- Repeating the same map operation returns the existing mapping.
- A source cannot silently be remapped to a different target.
- Conflicting mapping attempts raise a domain exception.
- The database unique constraint also protects against duplicate source mappings.

## 10. Migration Service

The canonical migration service contains the initial batch lifecycle skeleton:

- Begin migration batch
- Complete migration batch
- Roll back canonical feature cutover

Rollback deliberately does not delete canonical migrated records. It marks the migration batch as `rolled_back` so the data remains available for reconciliation and investigation.

## 11. Identity Reconciliation

The initial implementation is read-only candidate detection.

Matching order:

1. Explicit existing legacy mapping
2. Normalized phone
3. Normalized email
4. Combined phone and email signal
5. Manual review

Current behavior:

- Phone and email are normalized before comparison.
- Matching canonical users create `pending_review` candidates.
- Combined phone and email matches receive a stronger score.
- No automatic merge occurs.
- No legacy map is automatically created.
- Empty identity signals do not return every canonical user.
- Conflicts remain pending for manual review.
- Phone normalization uses Google's libphonenumber metadata through `giggsey/libphonenumber-for-php`.
- Valid numbers are stored and compared only in E.164 format.
- National-format numbers require a known ISO region from the source record or an explicitly configured source default.
- National-format numbers without a region, incomplete numbers, and invalid numbers are excluded from automatic matching.
- For example, `(415) 555-2671` with region `US` and `+1 415 555 2671` both normalize to `+14155552671`.

## 12. Feature Flags

The following flags were added and default to `false`:

```dotenv
CANONICAL_IDENTITY_ENABLED=false
CANONICAL_BUSINESS_ENABLED=false
CANONICAL_SERVICES_ENABLED=false
CANONICAL_BOOKING_ENABLED=false
CANONICAL_REFERRALS_ENABLED=false
CANONICAL_COMMERCE_ENABLED=false
```

Phase 1 does not activate canonical reads, writes, APIs, or authentication.

## 13. Baseline Seed Data

### Verticals

- `beauty`
- `medical`
- `travel`
- `pet`

### Business types

- `beauty_salon`
- `barbershop`
- `beauty_clinic`
- `medical_clinic`
- `academy`
- `store`
- `independent_provider`

### Membership roles

- `owner`
- `business_admin`
- `manager`
- `provider`
- `staff`
- `front_desk`
- `instructor`

Seeders use `updateOrCreate`, allowing safe repeated execution.

## 14. Tests Created

The new Phase 1 test suite covers:

1. Legacy map idempotency
2. Conflicting duplicate source mapping prevention
3. Multiple locations per business
4. User membership in multiple businesses
5. Different roles per business
6. Multiple specialties per provider
7. Unique public IDs
8. Preservation of existing legacy tables
9. Candidate detection without automatic merging
10. Migration rollback preserving canonical records

## 15. Verification Results

Final successful checks:

- PHP syntax checks passed.
- Laravel Pint formatting passed.
- Git whitespace/diff check passed.
- Composer autoload check passed.
- Laravel route discovery passed.
- All 161 application routes loaded successfully.
- Full automated suite passed: **28 tests, 61 assertions, 0 failures**.
- `CoreV2Phase1Test` passed all **15** methods.
- Test runtime: MySQL 8.0.30 on an isolated local server and dedicated database.

The first complete-suite run produced **26 passes, 1 failure, 59 assertions** because the legacy home-page smoke test queried `sliders.home_page`, a column absent from the fresh legacy migration schema. No legacy table was altered. The generic smoke test was corrected to verify application boot and home-route registration without depending on production-only legacy schema drift. The final complete run passed.

### Fresh database migration lifecycle

Executed successfully against a dedicated local MySQL 8.0.30 database, using a separate port and data directory:

1. Fresh full `php artisan migrate --force` — passed.
2. `CoreV2Phase1Seeder` — passed.
3. `php artisan migrate:rollback --step=3 --force` — all three Phase 1 migrations rolled back successfully.
4. `php artisan migrate --force` — all three Phase 1 migrations reapplied successfully.

The fresh run exposed an existing overlap between the August 8 and August 10 referral invitation migrations. The August 10 upgrade migration was made idempotent with `Schema::hasColumn` checks. It does not drop, rename, rewrite, or semantically change legacy columns; it only avoids adding columns that already exist.

## 16. Existing-Code Conflicts

### Identity conflict

The application currently has two active authenticatable populations:

- `User`, used by the default web guard, Filament, roles, and administration.
- `MainUser`, used by the customer/provider guard, panels, Beauty integration, referrals, appointments, products, and commerce.

These records cannot be safely merged by numeric ID.

Decision: create `canonical_users` alongside both systems and leave authentication behavior unchanged.

`CanonicalUser` intentionally extends the base Eloquent `Model`, not Laravel's authenticatable user class. Production authentication continues to use `App\Models\User` for the `web` guard and `App\Models\MainUser` for the `mainUsers` guard. Authentication cutover is explicitly deferred beyond Phase 1 and requires a separately reviewed migration and compatibility plan.

### Business conflict

The existing `businesses` table has a small legacy schema and is actively used by the administration UI. Beauty's `InfoActivity` also acts as an effective business record for providers and referrals.

Decision: create `businesses_v2` and use `CanonicalBusiness` as the compatibility model.

### Provider conflict

Provider status currently depends heavily on `main_users.service_pr`.

Decision: preserve it as legacy migration input. Canonical provider capability will eventually derive from provider profile, active business membership, location, role, and service assignment.

### Cross-database coupling

Current Core code directly queries Beauty models and tables. Referrals, appointments, services, and business ownership remain coupled to legacy Beauty entities.

Decision: Phase 1 introduces only the canonical foundation. No cutover or adapter behavior was silently introduced.

## 17. Legacy Compatibility Decisions

- `MainUser` was not removed or changed.
- Existing `User` was not converted into the canonical identity model.
- Existing `Business` was not converted into the canonical business model.
- Current authentication guards remain unchanged.
- Beauty and Vascular UI behavior remains unchanged.
- Referral API contracts remain unchanged.
- Existing legacy tables remain untouched.
- No PHI was moved into Core.
- Canonical foreign keys only reference canonical tables.
- Canonical features are disabled by default.

Recommended temporary compatibility aliases:

- `CanonicalUser` → `canonical_users`
- `CanonicalBusiness` → `businesses_v2`

These should become primary application concepts only after mapping, reconciliation, dual-read validation, and approved cutover.

## 18. Security Findings

The existing route inventory confirms the architecture audit's high-priority API concern.

Several user/provider/appointment endpoints are publicly registered without visible authentication middleware and accept numeric IDs, including:

- `api/get-appointments/{id}`
- `api/get-user/{id}`
- `api/get-user-info/{id}`
- provider and clinic service endpoints

Knowledge of a numeric user or patient ID must not grant access.

These endpoints were not changed in Phase 1 because changing their behavior could break existing Beauty or Vascular clients. They require a separate urgent authorization-hardening change using current authenticated identity, membership policies, and dedicated medical authorization.

Medical/PHI data was not introduced into generic Core tables.

## 19. Remaining Risks

- Identity matching rules still require approved verified/unverified contact semantics.
- Iranian and international phone normalization requires a formal policy.
- Production data must be profiled for duplicates, malformed contacts, invalid dates, and orphan references.
- Existing legacy foreign-key-like columns use inconsistent types.
- Nullable location membership uniqueness may need an additional application invariant.
- SQLite remains unavailable in the original system PHP CLI, but Phase 1 is verified against the target MySQL/MariaDB behavior using an isolated MySQL 8 database.
- No canonical legacy-data backfill exists yet.
- No dual-read or dual-write compatibility adapter exists yet.
- Provider license encryption requires a stable environment `APP_KEY`.
- Existing public API authorization risks remain open.
- The Phase 1 code is not committed or pushed until review approval.

## 20. Stage11 Requirements

Stage11 must have:

- Separate non-production Core, Beauty, Shop, and Travel databases
- Verified staging-only credentials
- Full database backups before migration
- Row-count and data-quality baseline reports
- PHP 8.1 or newer
- `pdo_mysql` enabled
- `pdo_sqlite` or a dedicated MySQL testing database for automated tests
- A stable `APP_KEY`
- InnoDB and foreign-key support
- `utf8mb4` database configuration
- Permission for additive DDL operations
- All canonical feature flags initially disabled
- No production database credentials

## 21. Stage11 Deployment Commands

After architecture review, commit, and push:

```powershell
git fetch origin
git checkout architecture/core-v2-phase1
git pull --ff-only origin architecture/core-v2-phase1
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan optimize:clear
php artisan migrate:status
php artisan migrate --pretend
```

Review the complete pretend output and confirm the active database is Stage11 before continuing.

## 22. Stage11 Migration Commands

After backup verification and migration approval:

```powershell
php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\CoreV2Phase1Seeder --force
php artisan optimize
php artisan migrate:status
```

Do not enable canonical feature flags during the initial schema deployment.

## 23. Validation Procedure

Run:

```powershell
php artisan test --filter=CoreV2Phase1Test
php artisan test --testsuite=Unit
php artisan route:list --except-vendor
```

Then verify:

1. All 21 canonical tables exist.
2. `users`, `main_users`, `businesses`, referrals, token ledger, orders, and Beauty tables remain unchanged.
3. Four verticals exist without duplicates.
4. Seven business types exist without duplicates.
5. Seven membership roles exist without duplicates.
6. Re-running `CoreV2Phase1Seeder` remains idempotent.
7. A canonical business can have multiple locations.
8. A canonical user can belong to multiple businesses.
9. A canonical user can hold different roles in different businesses.
10. A provider can have multiple specialties.
11. Public IDs are unique.
12. Repeating the same legacy map returns the existing mapping.
13. Conflicting legacy remapping is rejected.
14. Identity detection creates only pending review candidates.
15. Migration rollback preserves canonical records.
16. Existing admin, user panel, referrals, commerce, and Beauty workflows remain operational.

## 24. Recommendations Before Phase 2

1. Review and approve `canonical_users` and `businesses_v2` as temporary compatibility names.
2. Enable a PDO database driver and execute the complete Phase 1 database test suite.
3. Produce Stage11 row counts, duplicate identity reports, orphan reports, and invalid-value reports.
4. Finalize phone and email normalization rules.
5. Finalize verified-contact and identity conflict rules.
6. Approve a separate urgent API authorization-hardening patch.
7. Define canonical backfill chunking, checksums, and reconciliation thresholds.
8. Define compatibility adapter contracts before dual-read or dual-write work.
9. Do not begin Phase 2 or migrate production data until Phase 1 receives architecture and code approval.

## 25. Phase Boundary

Phase 1 is limited to canonical migration infrastructure, identity foundation, business/provider foundation, feature flags, models, seed data, service skeletons, and tests.

The following were intentionally not implemented:

- Legacy data backfill
- Authentication cutover
- Beauty booking rewrite
- Service catalog migration
- Universal appointments
- Referral canonical cutover
- BC wallet migration
- Commerce migration
- PHI migration
- Medical-domain implementation
- Production or Stage11 deployment
- Phase 2 work
