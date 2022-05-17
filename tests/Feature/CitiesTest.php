<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitiesTest extends TestCase
{
    use RefreshDatabase;
    
    public function testCities()
    {
        $response = $this->call('GET', 'api/cities');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'city_name',
                    'dataid',
                ],
            ]
        ]);
    }
}
