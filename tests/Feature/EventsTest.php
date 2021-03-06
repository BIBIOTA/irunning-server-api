<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\EventDistance;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    private $event;

    private $eventDistance;

    public function setUp(): void
    {
        parent::setUp();

        $this->event = Event::factory()->create();

        $this->eventDistance = EventDistance::factory()->create([
            'event_id' => $this->event->id,
        ]);
    }
    
    public function testEventsSuccessWithPaginate()
    {
        $data = [
            'startDay' => date('Y-m-d'),
            'endDay' => date('Y-m-d', strtotime("last day of 1 month")),
            'page' => 1,
        ];

        $dataStructure = $this->paginationStructure([
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
                'entry_is_end',
                'entry_start',
                'entry_end',
                'created_at',
                'updated_at',
                'distance' => [
                    '*' => [
                        'id',
                        'event_id',
                        'event_distance',
                        'event_price',
                        'event_limit',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);

        $this->json('GET', '/api/events' . '?' . http_build_query($data))
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }

    public function testEventsSuccessWithKeywordsNoPaginate()
    {
        $data = [
            'keywords' => $this->event->event_name,
        ];

        $dataStructure = [
            'status',
            'message',
            'data' => [
                [
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
                    'distance' => [
                        '*' => [
                            'id',
                            'event_id',
                            'event_distance',
                            'event_price',
                            'event_limit',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ]
        ];

        $this->json('GET', '/api/events', $data)
        ->assertStatus(200)
        ->assertJsonStructure($dataStructure);
    }

    public function testEventsWithPaginateValidationFailedWhenInvalidDistancesValue()
    {
        $data = [
            'distances' => [4],
            'startDay' => date('Y-m-d'),
            'endDay' => date('Y-m-d', strtotime("last day of 1 month")),
            'page' => 1,
        ];

        $this->json('GET', '/api/events', $data)
        ->assertStatus(422);
    }

    public function testEventWithPageNotFoundWhenDateRangeBeforeToday()
    {
        $data = [
            'startDay' => date('Y-m-d', strtotime("-2 day")),
            'endDay' => date('Y-m-d', strtotime("-1 day")),
            'page' => 1,
        ];

        $this->json('GET', '/api/events', $data)
        ->assertStatus(404);
    }
}
