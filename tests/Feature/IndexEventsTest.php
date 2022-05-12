<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexEventsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexEvents()
    {
        $response = $this->json('GET', 'api/index/getIndexEvents');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'link',
                    'event_status',
                    'event_name',
                    'event_info',
                    'event_certificate',
                    'event_date',
                    'event_time',
                    'location',
                    'agent',
                    'participate',
                    'created_at',
                    'updated_at',
                ],
            ]
        ]);
    }
}
