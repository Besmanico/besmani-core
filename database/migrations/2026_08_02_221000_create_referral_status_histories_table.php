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
        Schema::create('referral_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_id')->index();
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30)->index();
            $table->unsignedBigInteger('changed_by_user_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_status_histories');
    }
};
