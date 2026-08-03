<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referral_partners', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_user_id')->nullable()->index();
            $table->unsignedBigInteger('owner_business_id')->nullable()->index();
            $table->unsignedBigInteger('partner_user_id')->nullable()->index();
            $table->unsignedBigInteger('partner_business_id')->nullable()->index();

            $table->unsignedBigInteger('total_sent')->default(0);
            $table->unsignedBigInteger('total_received')->default(0);
            $table->unsignedBigInteger('total_earned_tokens')->default(0);
            $table->unsignedBigInteger('total_given_tokens')->default(0);
            $table->timestamp('last_referral_at')->nullable()->index();
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_partners');
    }
};
