<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
            'total' => $quantity * $product->price,
        ];
    }
}
