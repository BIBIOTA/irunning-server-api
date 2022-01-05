<?php

namespace App\Console\Commands;

use App\Models\MemberToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Throwable;
use Illuminate\Console\Command;

class RefreashStrava extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:refreashToken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新token';

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
            $datas = app(MemberToken::class)->where('expires_at', '<', Carbon::now())->get();

            if ($datas->count() > 0) {
                foreach ($datas as $data) {
                    $response = Http::post('https://www.strava.com/oauth/token', [
                        'client_id' => '68055',
                        'client_secret' => '4222100739f8aeecfe2bd2c2df077e5ec5a6b46c',
                        'refresh_token' => $data->refresh_token,
                        'grant_type' => 'refresh_token',
                    ]);

                    if ($response) {
                        app(MemberToken::class)->where('id', $data->id)->update([
                            'expires_at' => date('Y-m-d H:i:s', $response['expires_at']),
                            'expires_in' => intval(gmdate('H', $response['expires_in'])),
                            'refresh_token' => $response['refresh_token'],
                            'access_token' => $response['access_token'],
                        ]);
                    }
                }

                Log::info('會員Token更新完成');
            }

            return 0;
        } catch (Throwable $e) {
            Log::info($e);
        }
    }
}
