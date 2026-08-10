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
            // Legacy MainUser data is not managed by this app's users migration.
            $table->unsignedBigInteger('invited_by_user_id')->index();
            $table->string('recipient');
            $table->string('channel', 20);
            $table->string('party', 20)->default('provider');
            $table->string('inviter_name');
            $table->text('message');
            $table->string('status', 30)->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['invited_by_user_id', 'created_at']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('referral_invitations');
    }
};
