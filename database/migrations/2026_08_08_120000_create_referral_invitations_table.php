<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('referral_invitations', function (Blueprint $table): void {
        $table->id();

        // Inviter
        $table->unsignedBigInteger('invited_by_user_id')->index();
        $table->unsignedBigInteger('inviter_business_id')->nullable()->index();

        // Recipient
        $table->string('recipient');
        $table->string('recipient_email')->nullable()->index();
        $table->string('recipient_phone', 30)->nullable()->index();

        // Invitation
        $table->string('channel', 20)->default('copy');
        $table->string('party', 20)->default('provider')->index();

        // Secure token
        $table->string('token_hash', 64)->unique();

        // Content
        $table->string('inviter_name');
        $table->text('message');

        // Status
        $table->string('status', 30)->default('pending')->index();
        $table->text('failure_reason')->nullable();

        // Lifecycle
        $table->timestamp('sent_at')->nullable();
        $table->timestamp('opened_at')->nullable();
        $table->timestamp('accepted_at')->nullable();
        $table->timestamp('expires_at')->nullable()->index();

        $table->timestamps();

        $table->index(
            ['invited_by_user_id', 'created_at'],
            'referral_invitations_inviter_created_index'
        );
    });
}
 
    public function down(): void
    {
        Schema::dropIfExists('referral_invitations');
    }
};
