<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TelegramFollowEvent>
 */
class TelegramFollowEventFactory extends Factory
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
            'telegram_id' => $this->faker->unique()->randomNumber(),
            'event_id' => $this->faker->uuid,
        ];
    }
}
