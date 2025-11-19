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
        Schema::create('package_service_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('orderitem_id');
            $table->integer('quantity')->nullable();
            $table->integer('package_service_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_service_items');
    }
};
