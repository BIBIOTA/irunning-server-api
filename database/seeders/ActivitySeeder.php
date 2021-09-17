<?php

namespace Database\Seeders;

use App\Models\MemberToken;
use App\Models\Activity;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Activity::class)->truncate();

        $tokens = app(MemberToken::class)->get();

        $columns = DB::getSchemaBuilder()->getColumnListing('activities');

        if ($tokens->count() > 0) {
            foreach($tokens as $token) {
                $page = 1;
                $hasData = true;
                while($hasData) {
                    $response = Http::withToken($token->access_token)->get('https://www.strava.com/api/v3/athlete/activities?after=0&per_page=200&page='.$page);
    
                    $resdatas = $response->json();

                    if (count($resdatas) > 0) {
                        foreach($resdatas as $data) {
                            $formData = [
                                'user_id' => $token->user_id,
                            ];
                            foreach($data as $key => $value) {
                                if(in_array($key, $columns)) {
                                    if ($key === 'start_date_local') {
                                        $time_raw = strtotime($value);
                                        $time_mysql = Carbon::parse($time_raw);
                                        $formData[$key] = $time_mysql;
                                    } else {
                                        $formData[$key] = $value;
                                    }
                                }
                                if ($key === 'map') {
                                    $formData['summary_polyline'] = $value['summary_polyline'];
                                }
                            }
                            if ($data['type'] === 'Run') {
                                app(Activity::class)->create($formData);
                            }
                        }
                    } else {
                        $hasData = false;
                    }
                    $page++;
                }
            }
        }


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
