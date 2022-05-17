<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\EventDistance;
use Tests\TestCase;

class IndexEventsTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->event = Event::factory()->create([
            'event_status' => 1,
        ]);

        $this->eventDistance = EventDistance::factory()->create([
            'event_id' => $this->event->id,
        ]);
    }

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
