<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
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
            'name' => 'Night Run',
            'distance' => $this->faker->numberBetween(1, 30000),
            'moving_time' => $this->faker->numberBetween(1, 3600),
            'elapsed_time' => $this->faker->numberBetween(1, 3600),
            'total_elevation_gain' => $this->faker->numberBetween(1, 20),
            'start_date_local' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'average_speed' => $this->faker->randomFloat(3, 1, 3),
            'max_speed' => $this->faker->randomFloat(2, 1, 10),
            'average_cadence' => $this->faker->randomFloat(2, 0, 90),
            'has_heartrate' => 1,
            'average_heartrate' => $this->faker->randomFloat(2, 150, 200),
            'max_heartrate' => $this->faker->randomFloat(2, 150, 200),
            'elev_high' => $this->faker->randomFloat(2, 0, 1000),
            'summary_polyline' => 'summary_polyline',
        ];
    }
}
