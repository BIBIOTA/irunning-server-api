<?php
namespace App\Repositories;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use Throwable;

class EventRepository
{
    private $model;

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function getIndexEvents(): array
    {
        try {
            $rows = $this->model
                ->where('event_status', 1)
                ->where('event_date', '>=', Carbon::now())
                ->orderBy('event_date', 'ASC')->limit(5)
                ->get()
                ->toarray();

            return $rows ?? [];
        } catch (Throwable $e) {
            Log::stack(['repository', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function getIndexEvents error', 'main' => $e]);
        }
    }
}
