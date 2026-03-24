<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create(['name' => 'Laptop', 'price' => 50000]);
        Product::create(['name' => 'Mobile', 'price' => 20000]);
        Product::create(['name' => 'Headphones', 'price' => 3000]);
    }
}