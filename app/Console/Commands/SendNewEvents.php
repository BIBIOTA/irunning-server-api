<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendEmail;
use App\Services\EventService;
use App\Services\TelegramUserService;
use App\Enum\SubscribeOptionEnum;
use Throwable;

class SendNewEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send redis new events to nodeJs and telegram';

    protected EventService $eventService;

    protected TelegramUserService $telegramUserService;

    public function __construct(EventService $service, TelegramUserService $telegramUserService)
    {
        parent::__construct();

        $this->eventService = $service;
        $this->telegramUserService = $telegramUserService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $redisData = Redis::lrange('new_event', 0, -1);

            if (count($redisData) > 0) {
                $events = array_map(function ($item) {
                    return json_decode($item, true);
                }, $redisData);
                $userIds = $this->telegramUserService->getUserByOption(SubscribeOptionEnum::NEWEVENTS);
                $this->eventService->sendNewEventFromRedis($events, $userIds);
                Log::channel('event')->info('已傳送新賽事資料');
            };
        } catch (Throwable $e) {
            Log::stack(['event', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'command events:send error log', 'main' => $e]);
        }
        return 0;
    }
}
