<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Stat;
use App\Models\MemberToken;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait StravaActivitiesTrait
{
    public function getActivitiesDataFromStrava ($token, $onlyOnePage = false)
    {

      $columns = DB::getSchemaBuilder()->getColumnListing('activities');

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
                    if (is_array($data)) {
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
                            app(Activity::class)->updateOrCreate([
                                'id'=>$formData['id']
                            ], $formData);
                        }
                    }
                }
            } else {
                $hasData = false;
            }
            if (!$onlyOnePage) {
                $page++;
            } else {
                break;
            }
        }
        Log::info($token->user_id.'Strava活動更新完成');
    }

    public function getStats($stravaId, $token) {
        
        $response = Http::withToken($token->access_token)->get('https://www.strava.com/api/v3/athletes/'.$stravaId.'/stats');

        $resdatas = $response->json();

        if (isset($resdatas['message']) && $resdatas['message'] === 'Forbidden') {
            Log::info($resdatas);
            Log::info('取得Strava資料發生錯誤');
            Log::info($token);
        } else {
            $allRunTotals = $resdatas['all_run_totals'];
    
            $formData = [
                'user_id' => $token->user_id,
            ];
    
            foreach($allRunTotals as $key => $value) {
                $formData[$key] = $value;
            }
    
            $data = app(Stat::class)->where('user_id', $token->user_id)->first();
            
            if($data) {
                app(Stat::class)->where('user_id', $token->user_id)->update($formData);
            } else {
                $formData['id'] = uniqid();
                app(Stat::class)->create($formData);
            }
        }
    }

    public function getActivityFromStrava($userId, $stravaActivityId) {
        $token = app(MemberToken::class)->where('user_id', $userId)->first();

        if ($token) {
            $response = Http::withToken($token->access_token)->get('https://www.strava.com/api/v3/activities/'.$stravaActivityId);

            if ($response->status() === 200) {
                $data = $response->json();
                $time_raw = strtotime($data['start_date_local']);
                $time_mysql = gmdate('Y-m-d H:i:s',$time_raw);
                $data['start_date_local'] = $time_mysql;
                $data['pace'] = $this->getPace($data['distance'], $data['moving_time']);
                $data['distance'] = $this->getDistanceIsFloor($data['distance']);
                return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
            } else {
                Log::info($response);
                return response()->json(['status' => false, 'message' => '發生例外錯誤:無法取得Strava資料', 'data' => null], 404);
            }

        } else {
            return response()->json(['status' => false, 'message' => '無法取得登入資料', 'data' => null], 404);
        }
    }

}
