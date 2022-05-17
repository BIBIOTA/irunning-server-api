<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stat>
 */
class StatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'member_id' => $this->faker->uuid,
            'count' => $this->faker->numberBetween(1, 200),
            'distance' => $this->faker->numberBetween(1, 1000000),
            'moving_time' => $this->faker->numberBetween(1, 100000),
            'elapsed_time' => $this->faker->numberBetween(1, 100000),
            'elevation_gain' => $this->faker->numberBetween(1, 10000),
        ];
    }
}
