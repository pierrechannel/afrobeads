<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Electronics', 'image' => 'path/to/image1.jpg']);
        Category::create(['name' => 'Fashion', 'image' => 'path/to/image2.jpg']);
        Category::create(['name' => 'Home & Living', 'image' => 'path/to/image3.jpg']);
    }

    }

