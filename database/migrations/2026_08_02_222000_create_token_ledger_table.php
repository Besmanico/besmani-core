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
        Schema::create('token_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_id')->index();

            $table->unsignedBigInteger('from_user_id')->nullable()->index();
            $table->unsignedBigInteger('from_business_id')->nullable()->index();
            $table->unsignedBigInteger('to_user_id')->nullable()->index();
            $table->unsignedBigInteger('to_business_id')->nullable()->index();  

            $table->unsignedBigInteger('token_amount');
            $table->enum('transaction_type', [
                'earned',
                'given',
                'settlement',
                'adjustment',
                'reversal',
            ])->index();
            $table->string('status', 30)->default('pending')->index();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_ledger');
    }
};
