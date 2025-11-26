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
        Schema::create('custome_delete_items', function (Blueprint $table) {
            $table->id();
            $table->integer('package_service_item_id');
            $table->integer('user_id');
            $table->integer('cart_id');
            $table->boolean('status')->default(0);
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custome_delete_items');
    }
};
