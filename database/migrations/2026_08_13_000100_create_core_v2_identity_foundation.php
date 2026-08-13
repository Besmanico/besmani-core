<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canonical_users', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('display_name', 150)->nullable();
            $table->string('email')->nullable();
            // Intentionally non-unique during reconciliation: duplicate legacy identities
            // must coexist until reviewed and must never be merged by a DB constraint.
            $table->string('email_normalized')->nullable()->index()->comment('Non-unique during identity reconciliation; duplicates require review');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_country_code', 8)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_e164', 32)->nullable()->index()->comment('Non-unique during identity reconciliation; duplicates require review');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->string('locale', 12)->nullable();
            $table->string('timezone', 64)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('canonical_users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->string('gender_code', 30)->nullable();
            $table->unsignedBigInteger('avatar_media_id')->nullable();
            $table->text('bio')->nullable();
            $table->string('preferred_language', 12)->nullable();
            $table->timestamps();
        });

        Schema::create('user_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('canonical_users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('type', 30);
            $table->string('value');
            $table->string('normalized_value')->index();
            $table->boolean('is_primary')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->unique(['user_id', 'type', 'normalized_value']);
        });

        Schema::create('user_consents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('canonical_users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('consent_type', 100);
            $table->string('version', 50);
            $table->timestamp('accepted_at');
            $table->timestamp('revoked_at')->nullable();
            $table->string('source', 100)->nullable();
            $table->json('metadata_json')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'consent_type', 'accepted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_consents');
        Schema::dropIfExists('user_contacts');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('canonical_users');
    }
};
