<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Laptop', 'price' => 1500],
            ['name' => 'Smartphone', 'price' => 800],
            ['name' => 'Headphones', 'price' => 150],
            ['name' => 'Keyboard', 'price' => 100],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
