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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key referencing users
            $table->foreignId('shipping_address_id')->constrained('addresses')->onDelete('cascade'); // Foreign key for shipping address
            $table->foreignId('billing_address_id')->constrained('addresses')->onDelete('cascade'); // Foreign key for billing address
            $table->string('order_status'); // Status of the order (e.g., pending, completed, cancelled)
            $table->decimal('total_amount', 10, 2); // Total amount of the order
            $table->decimal('shipping_cost', 10, 2)->default(0); // Shipping cost
            $table->decimal('tax_amount', 10, 2)->default(0); // Tax amount
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
