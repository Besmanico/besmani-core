<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_referral_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('service_type', 50);
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('provider_user_id')->index();
            $table->unsignedBigInteger('business_id')->nullable()->index();
            $table->boolean('enabled')->default(false)->index();
            $table->unsignedInteger('reward_bc')->default(0);
            $table->string('discount_type', 20)->default('none');
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->string('discount_currency', 3)->nullable();
            $table->timestamps();
            $table->unique(['service_type', 'service_id'], 'service_referral_settings_service_unique');
        });

        Schema::table('referrals', function (Blueprint $table): void {
            $table->unsignedInteger('referral_reward_bc')->default(0)->after('token_amount');
            $table->string('customer_discount_type', 20)->default('none')->after('referral_reward_bc');
            $table->decimal('customer_discount_value', 12, 2)->default(0)->after('customer_discount_type');
            $table->string('customer_discount_currency', 3)->nullable()->after('customer_discount_value');
            $table->timestamp('referral_terms_snapshot_at')->nullable()->after('customer_discount_currency');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
        DB::table('referrals')->whereIn('status', ['pending', 'accepted'])->update([
            'referral_reward_bc' => (int) config('referrals.completion_coin_award', 100),
            'referral_terms_snapshot_at' => now(),
        ]);

        Schema::table('referral_invitations', function (Blueprint $table): void {
            $table->unsignedBigInteger('inviter_business_id')->nullable()->after('invited_by_user_id')->index();
            $table->string('recipient_email')->nullable()->after('recipient');
            $table->string('recipient_phone', 30)->nullable()->after('recipient_email');
            $table->char('token_hash', 64)->nullable()->after('party')->unique();
            $table->timestamp('accepted_at')->nullable()->after('sent_at');
            $table->timestamp('expires_at')->nullable()->after('accepted_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('referral_invitations', function (Blueprint $table): void {
            $table->dropUnique(['token_hash']);
            $table->dropIndex(['inviter_business_id']);
            $table->dropIndex(['expires_at']);
            $table->dropColumn(['inviter_business_id', 'recipient_email', 'recipient_phone', 'token_hash', 'accepted_at', 'expires_at']);
        });
        Schema::table('referrals', function (Blueprint $table): void {
            $table->dropColumn(['referral_reward_bc', 'customer_discount_type', 'customer_discount_value', 'customer_discount_currency', 'referral_terms_snapshot_at', 'cancelled_at']);
        });
        Schema::dropIfExists('service_referral_settings');
    }
};
