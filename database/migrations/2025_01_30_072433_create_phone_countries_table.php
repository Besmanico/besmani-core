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
        Schema::create('phone_countries', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name_fa');
            $table->string('name_en');
            $table->string('flag')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_countries');
    }
};
