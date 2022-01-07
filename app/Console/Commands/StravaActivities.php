<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemberToken;
use App\Http\Controllers\Traits\StravaActivitiesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StravaActivities extends Command
{
    use StravaActivitiesTrait;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
                        $this->getActivitiesDataFromStrava($tokenData);
                        Log::info($tokenData->member_id . 'Strava活動更新完成');
                    } catch (Throwable $e) {
                        Log::info($e);
                    }
                }
            }
        } catch (Throwable $e) {
            Log::info($e);
        }

        return 0;
    }
}
