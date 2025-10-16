<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $methods = ['credit_card', 'paypal', 'stripe'];

        return [
            'payment_method' => $this->faker->randomElement($methods),
            'status' => $this->faker->randomElement(['pending', 'successful', 'failed']),
            'transaction_id' => strtoupper($this->faker->bothify('TXN###??')),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
