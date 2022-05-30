<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aqi>
 */
class AqiFactory extends Factory
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
            'city_id' => app(City::class)->where('city_name', '臺北市')->first('id'),
            'sitename' => '士林',
            'aqi' => 41,
            'pollutant' => '',
            'status' => '良好',
            'aqi' => '良好',
            'so2' => 2.4,
            'co' => 0.25,
            'co_8hr' => 0.2,
            'o3' => 35.6,
            'o3_8hr' => 44.4,
            'pm10' => 25,
            'pm2_5' => 12,
            'no2' => 6.9,
            'nox' => 7.7,
            'no' => 0.8,
            'wind_speed' => 0.6,
            'wind_direc' => 105,
            'publishtime' => '2022/05/30 18:00:00',
            'pm2_5_avg' => 11,
            'pm10_avg' => 22,
            'so2_avg' => 1,
            'longitude' => 121.493806,
            'latitude' => 25.072611,
            'siteid' => 67,
        ];
    }
}
