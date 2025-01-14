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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key referencing products
            $table->string('size')->nullable(); // Size of the product variant
            $table->string('color')->nullable(); // Color of the product variant
            $table->string('sku')->unique(); // Stock Keeping Unit for the variant
            $table->integer('stock_quantity')->default(0); // Quantity of the variant in stock
            $table->decimal('price_adjustment', 10, 2)->default(0); // Price adjustment for the variant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
