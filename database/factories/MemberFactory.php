<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'strava_id' => env('STRAVA_DEV_ID'),
            'login_from' => 'Strava',
            'email' => null,
            'username' => null,
            'nickname' => null,
            'resource_state' => 2,
            'firstname' => '裕揮',
            'lastname' => '太田',
            'city' => null,
            'state' => null,
            'country' => null,
            'county' => null,
            'district' => null,
            'siteName' => null,
            'sex' => 'M',
            'badge_type_id' => 0,
            'weight' => 56.40,
            'runner_type' => 1,
            'join_rank' => 0,
            'is_register' => 0,
        ];
    }
}
