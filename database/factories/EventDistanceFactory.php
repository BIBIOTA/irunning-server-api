<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventDistance>
 */
class EventDistanceFactory extends Factory
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
            'event_id' => $this->faker->uuid,
            'event_distance' => 10,
            'event_price' => 1000,
            'event_limit' => 1000,
        ];
    }
}
