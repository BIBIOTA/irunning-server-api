<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
            'link' => $this->faker->url,
            'event_status' => $this->faker->boolean(),
            'event_name' => $this->faker->sentence,
            'event_info' => $this->faker->sentence,
            'event_certificate' => $this->faker->numberBetween(1, 3),
            'event_date' => date("Y-m-d", rand(Carbon::now()->timestamp, Carbon::now()->addMonth(2)->timestamp)),
            'event_time' => $this->faker->time,
            'location' => $this->faker->address,
            'agent' => $this->faker->name,
            'participate' => $this->faker->sentence,
            'entry_is_end' => false,
            'entry_start' => date("Y-m-d", Carbon::now()->addDays(7)->timestamp),
            'entry_end' => date("Y-m-d", Carbon::now()->addDays(14)->timestamp),
        ];
    }
}
