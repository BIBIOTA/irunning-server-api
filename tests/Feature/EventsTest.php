<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $dateRangeTest = [
            [
                'startDay' => date('Y-m-d'),
                'endDay' => date('Y-m-d', strtotime("last day of 1 month")),
                'result' => 200,
            ],
            [
                'startDay' => date('Y-m-d', strtotime("-2 day")),
                'endDay' => date('Y-m-d', strtotime("-1 day")),
                'result' => 404,
            ]
        ];
        
        // 1: 全馬, 2: 半馬, 3: 三鐵
        $eventDistances = [1,2,3];

        $dataStructure = [
            'status',
            'message',
            'data' => [
                'current_page',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
                'data' => [
                    '*' => [
                        'id',
                        'link',
                        'event_status',
                        'event_name',
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
                ],
            ]
        ];

        // conditon
        for ($i = 0; $i <= count($eventDistances) - 1; $i++) {
            $eventDistance = $eventDistances[$i];
            for ($j = 0; $j <= count($dateRangeTest) - 1; $j++) {
                $dateRange = $dateRangeTest[$j];
                $pageCount = 1;
                $data = [
                    'event_distance' => $eventDistance,
                    'page' => $pageCount,
                    'startDay' => $dateRange['startDay'],
                    'endDay' => $dateRange['endDay'],
                ];
                for ($page = 1; $page <= $pageCount; $page++) {
                    $response = $this->call('GET', 'api/events', $data);
                    $response->assertStatus($dateRange['result']);
                    if ($dateRange['result'] === 200) {
                        $response->assertJsonStructure($dataStructure);
                        if ($this->hasNextPage($response)) {
                            $pageCount++;
                        }
                    } else {
                        $response->assertJsonStructure($this->falseJsonStructure());
                    }
                }
            }

        } 

        // default
        $pageCount = 1;
        for ($page = 1; $page <= $pageCount; $page++) {
            $response = $this->call('GET', 'api/events', [
                'page' => $pageCount,
            ]);
            $response->assertStatus(200);
            $response->assertJsonStructure($dataStructure);
            if ($this->hasNextPage($response)) {
                $pageCount++;
            }
        }
    }
}
