<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'point_id' => $this->faker->numberBetween(1, 10),
            'scooter_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(3, 12),
            'manager_id' => 2,
            'price' => $this->faker->randomFloat(2, 200, 500),
            'status' => 2,
            'date' => $this->faker->dateTimeThisMonth()
        ];
    }
}
