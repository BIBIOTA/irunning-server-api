<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemberToken;
use App\Services\ActivityService;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StravaActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得活動紀錄';


    protected ActivityService $activityService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ActivityService $activityService)
    {
        parent::__construct();

        $this->activityService = $activityService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $tokens = app(MemberToken::class)->get();

            if ($tokens->count() > 0) {
                foreach ($tokens as $tokenData) {
                    try {
                        $this->activityService->getActivitiesDataFromStrava($tokenData);
                        Log::channel('strava')->info($tokenData->member_id . 'Strava活動更新完成');
                    } catch (Throwable $e) {
                        Log::stack(['strava', 'slack'])->critical($e);
                    }
                }
            }
        } catch (Throwable $e) {
            Log::stack(['strava', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'strava activities error log', 'main' => $e]);
        }

        return 0;
    }
}
