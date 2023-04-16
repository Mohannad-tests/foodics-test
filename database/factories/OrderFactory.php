<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, string>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
        ];
    }
}
