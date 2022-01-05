<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDistance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $response = Http::get(env('NODE_URL') . '/api/events');

            if ($response->status() === 200) {
                $res = $response->json();

                $eventsColumns = DB::getSchemaBuilder()->getColumnListing('events');
                $eventsDistancesColumns = DB::getSchemaBuilder()->getColumnListing('events_distances');

                if (count($res) > 0) {
                    app(Event::class)->truncate();
                    app(EventDistance::class)->truncate();


                    if ($res['status'] === true) {
                        if (is_array($res['data'])) {
                            foreach ($res['data'] as $data) {
                                $id = uniqid();
                                $formData = [
                                    'id' => $id,
                                ];
                                $distanceFormData = [];
                                foreach ($data as $key => $value) {
                                    if (in_array($key, $eventsColumns)) {
                                        $formData[$key] = $value;
                                    }
                                    if ($key === 'distances') {
                                        foreach ($data[$key] as $distance) {
                                            $distanceData = [
                                                'id' => uniqid(),
                                                'event_id' => $id,
                                            ];
                                            foreach ($distance as $key => $value) {
                                                if (in_array($key, $eventsDistancesColumns)) {
                                                    $distanceData[$key] = $value;
                                                }
                                            }
                                            array_push($distanceFormData, $distanceData);
                                        }
                                    }
                                }
                                app(Event::class)->create($formData);
                                if (count($distanceFormData) > 0) {
                                    foreach ($distanceFormData as $data) {
                                        app(EventDistance::class)->create($data);
                                    }
                                }
                            }
                        }
                        Log::info('賽事資料更新完成');
                    } else {
                        Log::info('無法取得賽事資料');
                        Log::info($res['message']);
                    }
                } else {
                    Log::info('無法取得賽事資料');
                }
            } else {
                Log::info('無法取得賽事資料:無法連線');
            }


            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (Throwable $e) {
            Log::info($e);
        }
    }
}
