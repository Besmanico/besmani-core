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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_number')->unique();
            $table->unsignedBigInteger('referrer_user_id')->nullable()->index();
            $table->unsignedBigInteger('referrer_business_id')->nullable()->index();
            $table->unsignedBigInteger('referrer_branch_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_user_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_business_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_branch_id')->nullable()->index();
            $table->unsignedBigInteger('customer_user_id')->nullable()->index();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_phone', 30)->nullable()->index();
            $table->string('customer_email')->nullable();
            $table->unsignedBigInteger('section_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('service_id')->nullable()->index();
            $table->string('reward_type', 50)->nullable();
            $table->unsignedBigInteger('token_amount')->default(0);
            $table->text('note')->nullable();
            $table->string('status', 30)->default('pending')->index();
            $table->date('expiration_date')->nullable()->index();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
