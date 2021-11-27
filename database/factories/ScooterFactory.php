<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScooterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'num' => $this->faker->regexify('[A-Z][0-9]{3}[A-Z]{2}'),
            'point_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
