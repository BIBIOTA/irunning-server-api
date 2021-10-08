<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Stat;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

trait StravaActivitiesTrait
{
    public function getActivitiesDataFromStrava($token)
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
          } else {
              $hasData = false;
          }
          $page++;
      }
    }

    public function getStats($token) {
        $response = Http::withToken($token->access_token)->get('https://www.strava.com/api/v3/athletes/28179653/stats');

        $resdatas = $response->json();

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