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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('img');
            $table->string('description');
            $table->text('meta');
            $table->text('keywords');
            $table->integer('category_id');
            $table->string('create_at');
            $table->string('technical_code');
            $table->string('times');
            $table->boolean('status')->default(0);
            $table->boolean('locked')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
