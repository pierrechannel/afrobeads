<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name' => 'Smartphone',
            'price' => 299.99,
            'image' => 'path/to/product1.jpg',
            'stock' => 100,
        ]);
        Product::create([
            'category_id' => 2,
            'name' => 'Designer Dress',
            'price' => 59.99,
            'image' => 'path/to/product2.jpg',
            'stock' => 50,
        ]);
        // Add more products.
    }
}
