<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Aqi;
use Tests\TestCase;

class AqiTest extends TestCase
{
    use RefreshDatabase;

    public function testAqi()
    {
        $aqi = Aqi::factory()->create();

        $response = $this->call('GET', 'api/aqi', [
            'city_id' => $aqi->city_id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'city_id',
                    'sitename',
                    'aqi',
                    'pollutant',
                    'status',
                    'so2',
                    'co',
                    'co_8hr',
                    'o3',
                    'o3_8hr',
                    'pm10',
                    'pm2_5',
                    'no2',
                    'wind_speed',
                    'wind_direc',
                    'publishtime',
                    'pm2_5_avg',
                    'pm10_avg',
                    'so2_avg',
                    'longitude',
                    'latitude',
                    'siteid',
                ],
            ]
        ]);
    }
}
