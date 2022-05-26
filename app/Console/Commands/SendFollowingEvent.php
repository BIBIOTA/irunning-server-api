<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventService;

class SendFollowingEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:follow_events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Telegram user following event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(EventService $service)
    {
        $data = $service->getUpdatedEventsWithTelegramUser();

        $sendData = [];
        foreach ($data as $event) {
            if (count($event['telegram_follow_event']) > 0) {
                $users = $event['telegram_follow_event'];
                unset($event['telegram_follow_event']);
                foreach ($users as $user) {
                    $sendData[$user['telegram_id']][] = $event;
                }
            }
        }

        if (count($sendData) > 0) {
            $service->sendUpdatedEvents($sendData);
        }

        return 0;
    }
}
