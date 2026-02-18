<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * order_id is only set when linked to an order; at addToCart time there is no order yet.
     */
    public function up(): void
    {
        Schema::table('cart_services', function (Blueprint $table) {
            $table->integer('order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_services', function (Blueprint $table) {
            $table->integer('order_id')->nullable(false)->change();
        }); 
    }
};
