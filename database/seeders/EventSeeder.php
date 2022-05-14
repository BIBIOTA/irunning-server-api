<?php

namespace Database\Seeders;

use App\Services\EventService;
use App\Jobs\SendEmail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventSeeder extends Seeder
{
    private EventService $service;

    /**
     * @param EventService $service
     */
    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $response = Http::get(env('NODE_URL') . '/api/events');

            if ($response->status() === 200) {
                $res = $response->json();

                if (isset($res) && count($res) > 0) {
                    if ($res['status'] === true) {
                        if (is_array($res['data'])) {
                            foreach ($res['data'] as $data) {
                                $event = $this->service->getEventByEventName($data['event_name']);

                                if (empty($event)) {
                                    $this->service->createEvent($data);
                                } else {
                                    $this->service->updateEvent($event->id, $data);
                                }
                            }
                        }
                        Log::channel('event')->info('賽事資料更新完成');
                    } else {
                        Log::stack(['event', 'slack'])->error('無法取得賽事資料');
                    }
                } else {
                    Log::stack(['event', 'slack'])->error('無賽事資料');
                }
            } else {
                Log::stack(['event', 'slack'])->error('無法取得賽事資料:無法連線');
            }
        } catch (Throwable $e) {
            Log::stack(['event', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'event error log', 'main' => $e]);
        }
    }
}
