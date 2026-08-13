<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verticals', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('status', 30)->default('active')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('business_types', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vertical_id')->constrained()->restrictOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('status', 30)->default('active')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('businesses_v2', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('owner_user_id')->nullable()->constrained('canonical_users')->nullOnDelete();
            $table->foreignId('business_type_id')->constrained()->restrictOnDelete();
            $table->string('legal_name')->nullable();
            $table->string('display_name');
            $table->string('slug')->unique();
            $table->string('status', 30)->default('pending_claim')->index();
            $table->text('description')->nullable();
            $table->string('phone_e164', 32)->nullable();
            $table->string('email')->nullable();
            $table->string('website_url')->nullable();
            $table->unsignedBigInteger('logo_media_id')->nullable();
            $table->string('verification_status', 30)->default('unverified');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['business_type_id', 'status']);
        });
        Schema::create('business_locations', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('business_id')->constrained('businesses_v2')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('location_type', 30)->default('physical');
            $table->string('phone_e164', 32)->nullable();
            $table->string('email')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('timezone', 64);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_virtual')->default(false);
            $table->string('status', 30)->default('active');
            $table->timestamps();
            $table->unique(['business_id', 'slug']);
            $table->index(['business_id', 'status']);
            $table->index(['country_code', 'region', 'city']);
        });
        Schema::create('membership_roles', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('status', 30)->default('active')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('business_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses_v2')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('canonical_users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('business_location_id')->nullable()->constrained('business_locations')->nullOnDelete();
            $table->foreignId('membership_role_id')->constrained('membership_roles')->restrictOnDelete();
            $table->string('job_title')->nullable();
            $table->string('employment_status', 30)->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->unique(['business_id', 'user_id', 'business_location_id', 'membership_role_id'], 'business_member_scope_unique');
            $table->index(['user_id', 'status']);
        });
        Schema::create('provider_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('canonical_users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('professional_title')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedSmallInteger('years_experience')->nullable();
            $table->boolean('accepting_clients')->default(false);
            $table->string('verification_status', 30)->default('unverified');
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
        });
        Schema::create('specialties', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vertical_id')->constrained()->restrictOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('specialties')->nullOnDelete();
            $table->string('code', 100);
            $table->string('name', 150);
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->unique(['vertical_id', 'code']);
        });
        Schema::create('provider_specialties', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('provider_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('years_experience')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->unique(['provider_profile_id', 'specialty_id']);
        });
        Schema::create('provider_licenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('provider_profile_id')->constrained()->cascadeOnDelete();
            $table->string('jurisdiction', 100);
            $table->string('license_type', 100);
            $table->text('license_number')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('verification_status', 30)->default('unverified');
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->index(['provider_profile_id', 'jurisdiction']);
        });
        Schema::create('business_verticals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses_v2')->cascadeOnDelete();
            $table->foreignId('vertical_id')->constrained()->restrictOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->string('status', 30)->default('active');
            $table->timestamps();
            $table->unique(['business_id', 'vertical_id']);
        });
        Schema::create('business_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses_v2')->cascadeOnDelete();
            $table->string('key', 100);
            $table->json('value_json')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'key']);
        });
    }

    public function down(): void
    {
        foreach (['business_settings', 'business_verticals', 'provider_licenses', 'provider_specialties', 'specialties', 'provider_profiles', 'business_members', 'membership_roles', 'business_locations', 'businesses_v2', 'business_types', 'verticals'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
