<?php

namespace Tests\Feature;

use App\Models\BusinessLocation;
use App\Models\BusinessMember;
use App\Models\BusinessType;
use App\Models\CanonicalBusiness;
use App\Models\CanonicalUser;
use App\Models\MembershipRole;
use App\Models\ProviderProfile;
use App\Models\Specialty;
use App\Models\Vertical;
use App\Services\Identity\IdentityReconciliationService;
use App\Services\Migration\CanonicalMigrationService;
use App\Services\Migration\LegacyMapService;
use Database\Seeders\CoreV2Phase1Seeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CoreV2Phase1Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CoreV2Phase1Seeder::class);
    }

    private function business(string $slug): CanonicalBusiness
    {
        return CanonicalBusiness::create(['business_type_id' => BusinessType::first()->id, 'display_name' => $slug, 'slug' => $slug, 'status' => 'active']);
    }

    public function test_legacy_mapping_is_idempotent_and_conflicts_are_prevented(): void
    {
        $service = app(LegacyMapService::class);
        $first = $service->map('beauty', 'tbl_users', 7, 'canonical_user', 42);
        $second = $service->map('beauty', 'tbl_users', 7, 'canonical_user', 42);
        $this->assertTrue($first->is($second));
        $this->expectException(\DomainException::class);
        $service->map('beauty', 'tbl_users', 7, 'canonical_user', 43);
    }

    public function test_business_can_have_multiple_locations(): void
    {
        $business = $this->business('multi-location');
        BusinessLocation::create(['business_id' => $business->id, 'name' => 'One', 'slug' => 'one', 'timezone' => 'Asia/Tehran']);
        BusinessLocation::create(['business_id' => $business->id, 'name' => 'Two', 'slug' => 'two', 'timezone' => 'Asia/Tehran']);
        $this->assertCount(2, $business->fresh()->locations);
    }

    public function test_user_can_belong_to_multiple_businesses_with_different_roles(): void
    {
        $user = CanonicalUser::factory()->create();
        foreach ([[$this->business('first'), 'owner'], [$this->business('second'), 'provider']] as [$business,$role]) {
            BusinessMember::create(['business_id' => $business->id, 'user_id' => $user->id, 'membership_role_id' => MembershipRole::where('code', $role)->value('id')]);
        }
        $this->assertCount(2, $user->fresh()->memberships);
        $this->assertEqualsCanonicalizing(['owner', 'provider'], $user->memberships->map->role->pluck('code')->all());
    }

    public function test_duplicate_business_wide_membership_is_rejected_when_location_is_null(): void
    {
        $business = $this->business('business-wide-unique');
        $membership = [
            'business_id' => $business->id,
            'user_id' => CanonicalUser::factory()->create()->id,
            'membership_role_id' => MembershipRole::where('code', 'manager')->value('id'),
            'business_location_id' => null,
        ];

        BusinessMember::create($membership);
        $this->expectException(QueryException::class);
        BusinessMember::create($membership);
    }

    public function test_location_scoped_membership_is_unique_per_location_but_allowed_at_another_location(): void
    {
        $business = $this->business('location-scope-unique');
        $user = CanonicalUser::factory()->create();
        $role = MembershipRole::where('code', 'provider')->value('id');
        $first = BusinessLocation::create(['business_id' => $business->id, 'name' => 'First', 'slug' => 'first', 'timezone' => 'Asia/Tehran']);
        $second = BusinessLocation::create(['business_id' => $business->id, 'name' => 'Second', 'slug' => 'second', 'timezone' => 'Asia/Tehran']);

        BusinessMember::create(['business_id' => $business->id, 'user_id' => $user->id, 'membership_role_id' => $role, 'business_location_id' => $first->id]);
        BusinessMember::create(['business_id' => $business->id, 'user_id' => $user->id, 'membership_role_id' => $role, 'business_location_id' => $second->id]);
        $this->assertCount(2, $user->fresh()->memberships);

        $this->expectException(QueryException::class);
        BusinessMember::create(['business_id' => $business->id, 'user_id' => $user->id, 'membership_role_id' => $role, 'business_location_id' => $first->id]);
    }

    public function test_database_rejects_a_membership_scope_key_that_does_not_match_its_location(): void
    {
        $business = $this->business('scope-check');
        $location = BusinessLocation::create(['business_id' => $business->id, 'name' => 'Scoped', 'slug' => 'scoped', 'timezone' => 'Asia/Tehran']);

        $this->expectException(QueryException::class);
        DB::table('business_members')->insert([
            'business_id' => $business->id,
            'user_id' => CanonicalUser::factory()->create()->id,
            'business_location_id' => $location->id,
            'membership_scope_key' => 'business',
            'membership_role_id' => MembershipRole::where('code', 'provider')->value('id'),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_provider_can_have_multiple_specialties(): void
    {
        $profile = ProviderProfile::create(['user_id' => CanonicalUser::factory()->create()->id]);
        $vertical = Vertical::first();
        $a = Specialty::create(['vertical_id' => $vertical->id, 'code' => 'a', 'name' => 'A']);
        $b = Specialty::create(['vertical_id' => $vertical->id, 'code' => 'b', 'name' => 'B']);
        $profile->specialties()->attach([$a->id, $b->id]);
        $this->assertCount(2, $profile->fresh()->specialties);
    }

    public function test_public_ids_are_unique(): void
    {
        $a = CanonicalUser::factory()->create();
        $b = CanonicalUser::factory()->create();
        $this->assertNotSame($a->public_id, $b->public_id);
        $this->assertNotNull($this->business('public-id')->public_id);
    }

    public function test_no_existing_legacy_table_is_dropped(): void
    {
        foreach (['users', 'businesses', 'referrals', 'token_ledger'] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table.' must remain');
        }
    }

    public function test_phase_one_does_not_change_production_authentication_models(): void
    {
        $this->assertSame(\App\Models\User::class, config('auth.providers.users.model'));
        $this->assertSame(\App\Models\MainUser::class, config('auth.providers.main_users.model'));
        $this->assertSame('users', config('auth.guards.web.provider'));
        $this->assertSame('main_users', config('auth.guards.mainUsers.provider'));
        $this->assertNotInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, new CanonicalUser);
    }

    public function test_identity_detection_only_creates_review_candidate(): void
    {
        $target = CanonicalUser::factory()->create(['phone_e164' => '+14155552671', 'email_normalized' => 'same@example.com']);
        $result = app(IdentityReconciliationService::class)->detectCandidates('core_legacy', 'main_users', 99, ['phone' => '(415) 555-2671', 'phone_region' => 'US', 'email' => 'SAME@example.com']);
        $this->assertNull($result['explicit_mapping']);
        $this->assertCount(1, $result['candidates']);
        $this->assertSame('pending_review', $result['candidates'][0]->status);
        $this->assertDatabaseMissing('legacy_entity_maps', ['source_table' => 'main_users', 'source_id' => '99']);
        $this->assertDatabaseHas('canonical_users', ['id' => $target->id]);
    }

    public function test_phone_normalization_equates_us_national_and_e164_formats_and_rejects_ambiguity(): void
    {
        $service = app(IdentityReconciliationService::class);
        $this->assertSame('+14155552671', $service->normalizePhone('(415) 555-2671', 'US'));
        $this->assertSame('+14155552671', $service->normalizePhone('+1 415 555 2671'));
        $this->assertNull($service->normalizePhone('(415) 555-2671'));
        $this->assertNull($service->normalizePhone('555-2671', 'US'));
        $this->assertNull($service->normalizePhone('not-a-phone', 'US'));
    }

    public function test_duplicate_verified_phone_creates_multiple_review_candidates_without_mapping(): void
    {
        CanonicalUser::factory()->count(2)->create(['phone_e164' => '+14155552671', 'phone_verified_at' => now()]);

        $result = app(IdentityReconciliationService::class)->detectCandidates(
            'beauty',
            'tbl_users',
            501,
            ['phone' => '(415) 555-2671', 'phone_region' => 'US'],
        );

        $this->assertTrue($result['has_conflict']);
        $this->assertCount(2, $result['candidates']);
        $this->assertTrue(collect($result['candidates'])->every(fn ($candidate) => $candidate->status === 'pending_review'));
        $this->assertDatabaseMissing('legacy_entity_maps', ['source_system' => 'beauty', 'source_table' => 'tbl_users', 'source_id' => '501']);
    }

    public function test_conflicting_verified_phone_and_email_do_not_auto_merge(): void
    {
        CanonicalUser::factory()->create(['phone_e164' => '+14155552671', 'phone_verified_at' => now(), 'email_normalized' => 'phone-owner@example.com']);
        CanonicalUser::factory()->create(['phone_e164' => '+12125550123', 'email_normalized' => 'email-owner@example.com', 'email_verified_at' => now()]);

        $result = app(IdentityReconciliationService::class)->detectCandidates(
            'vascular',
            'users',
            77,
            ['phone' => '+1 415 555 2671', 'email' => 'email-owner@example.com'],
        );

        $this->assertTrue($result['has_conflict']);
        $this->assertCount(2, $result['candidates']);
        $this->assertDatabaseMissing('legacy_entity_maps', ['source_system' => 'vascular', 'source_table' => 'users', 'source_id' => '77']);
    }

    public function test_migration_rollback_keeps_canonical_records(): void
    {
        $batch = app(CanonicalMigrationService::class)->begin('test');
        $user = CanonicalUser::factory()->create();
        app(CanonicalMigrationService::class)->rollBack($batch);
        $this->assertDatabaseHas('canonical_users', ['id' => $user->id]);
        $this->assertSame('rolled_back', $batch->fresh()->status);
    }
}
