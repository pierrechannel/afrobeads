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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key referencing categories
            $table->string('name'); // Name of the product
            $table->text('description')->nullable(); // Description of the product
            $table->decimal('base_price', 10, 2); // Base price of the product
            $table->string('brand')->nullable(); // Brand of the product
            $table->string('gender')->nullable(); // Intended gender for the product (e.g., male, female, unisex)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
