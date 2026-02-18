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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('cart_id');
            $table->string('tracking_code');
            $table->decimal('total_payment', 10, 2)->default(0);
            $table->decimal('tax_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('cuote')->default(0);
            $table->integer('invoice')->default(0);
            $table->string('pay_method');
            $table->string('contact_name');
            $table->string('billing_address');
            $table->string('shipping_address');
            $table->string('signature_client');
            $table->string('signature_date');
            $table->boolean('is_admin')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('cancel')->default(0);
            $table->enum('order_status', ['Pending', 'processing', 'Done', 'Cancelled'])->default('Pending');
            $table->enum('payment_status', ['Pending', 'Paid', 'Unpaid', 'Partially Paid','Overpaid'])->default('Pending');
            $table->integer('progress')->default(0);
            $table->decimal('free_price', 10, 2)->nullable();
            $table->dateTime('free_price_date')->nullable();
 
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
