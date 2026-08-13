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
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_identity_detection_only_creates_review_candidate(): void
    {
        $target = CanonicalUser::factory()->create(['phone_e164' => '+15551234567', 'email_normalized' => 'same@example.com']);
        $result = app(IdentityReconciliationService::class)->detectCandidates('core_legacy', 'main_users', 99, ['phone' => '+1 555 123 4567', 'email' => 'SAME@example.com']);
        $this->assertNull($result['explicit_mapping']);
        $this->assertCount(1, $result['candidates']);
        $this->assertSame('pending_review', $result['candidates'][0]->status);
        $this->assertDatabaseMissing('legacy_entity_maps', ['source_table' => 'main_users', 'source_id' => '99']);
        $this->assertDatabaseHas('canonical_users', ['id' => $target->id]);
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
