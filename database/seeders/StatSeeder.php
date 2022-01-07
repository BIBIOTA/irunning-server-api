<?php

namespace Database\Seeders;

use App\Models\MemberToken;
use App\Models\Stat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class StatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Stat::class)->truncate();

        $tokens = app(MemberToken::class)->get();

        if ($tokens->count() > 0) {
            foreach ($tokens as $token) {
                $response = Http::withToken($token->access_token)->get('https://www.strava.com/api/v3/athletes/28179653/stats');

                $resdatas = $response->json();

                $allRunTotals = $resdatas['all_run_totals'];

                $formData = [
                    'id' => uniqid(),
                    'member_id' => $token->member_id,
                ];

                foreach ($allRunTotals as $key => $value) {
                    $formData[$key] = $value;
                }

                app(Stat::class)->create($formData);
            }
        }


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
