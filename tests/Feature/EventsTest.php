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

        $dataStructure = $this->paginationStructure([
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
        ]);

        // conditon
        for ($i = 0; $i <= count($eventDistances) - 1; $i++) {
            $eventDistance = $eventDistances[$i];
            for ($j = 0; $j <= count($dateRangeTest) - 1; $j++) {
                $dateRange = $dateRangeTest[$j];
                $data = [
                    'event_distance' => $eventDistance,
                    'startDay' => $dateRange['startDay'],
                    'endDay' => $dateRange['endDay'],
                ];
                $this->paginationTest('GET', 'api/events', $data, $dataStructure);
            }
        }

        // default
        $this->paginationTest('GET', 'api/events', [], $dataStructure);
    }
}
