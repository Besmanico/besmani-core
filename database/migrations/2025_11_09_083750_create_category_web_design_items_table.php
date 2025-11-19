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
        Schema::create('category_web_design_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price_item', 10, 2)->nullable();
            $table->text('delivery_time')->nullable();
            $table->integer('category_web_design_id');
            $table->text('link')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_web_design_items');
    }
};
