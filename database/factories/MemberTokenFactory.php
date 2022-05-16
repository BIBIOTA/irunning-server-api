<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberToken>
 */
class MemberTokenFactory extends Factory
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
            'member_id' => $this->faker->uuid(),
            'expires_at' => '2022-05-16 22:55:49',
            'expires_in' => 1,
            'refresh_token' => 'refresh_token',
            'access_token' => 'access_token',
        ];
    }
}
