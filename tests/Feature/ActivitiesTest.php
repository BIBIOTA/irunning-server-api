<?php

namespace Tests\Feature;

use App\Models\MemberToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {

        $datas = app(MemberToken::class)->get();

        $dataStructure = $this->paginationStructure([
            '*' => [
                'id',
                'name',
                'pace',
                'distance',
                'moving_time',
                'start_date_local',
                'summary_polyline',
            ],
        ]);

        if ($datas->count() > 0) {
            foreach ($datas as $data) {

                $responseRefreashToken = $this->refreshStravaToken($data);

                $formData = [
                    'id' => $data->member_id,
                ];

                $this->paginationTest('GET', 'api/activities', $formData, $dataStructure);
            }
        }
    }
}
