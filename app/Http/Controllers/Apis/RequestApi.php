<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Traits\Running;

use Illuminate\Support\Facades\Http;

use App\Models\Aqi;
use App\Models\Activity;
use App\Models\City;
use App\Models\District;
use App\Models\Member;
use App\Models\Weather;
use App\Models\Stat;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestApi extends Controller
{
    use Running;

    public function import () {
        $response = Http::get('https://data.epa.gov.tw/api/v1/aqx_p_432?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&sort=ImportDate%20desc&format=json');
        return $response->json();
    }

    public function getAqiList (Request $request) {
        $data = app(Aqi::class)->where('County', $request->County)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getCities (Request $request) {
        $data = app(City::class)->whereNotNull('dataid')->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getDistricts (Request $request) {
        $data = app(District::class)->where('CityName', $request->CityName)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getWeather (Request $request) {
        $row = app(Weather::class)
                ->where('locationsName', $request->CityName)
                ->where('locationName', $request->AreaName)
                ->first();

        if ($row) {

            $data['CityName'] = $row->locationsName;
            $data['AreaName'] = $row->locationName;
            $data['temperature'] = $row->T->value.'°C';
            $data['AT'] = $row->AT->value.'°C';
            $data['PoP6h'] = $row->PoP6h->value.'%';
            $data['CI'] = $row->CI->value;
            $data['Wx'] = $row->Wx->value;
            $data['WxValue'] = $row->Wx->WxDocument->value;
            $data['updated_at'] = $row->updated_at;

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getIndexRunInfo (Request $request) {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $stat = app(Stat::class)
                ->where('user_id', $request->id)
                ->first();
        $activitiesYear = app(Activity::class)
                    ->whereYear('start_date_local', date('Y'))
                    ->where('user_id', $request->id)
                    ->sum('distance');
        $activitiesMonth = app(Activity::class)
            ->whereYear('start_date_local', date('Y'))
            ->whereMonth('start_date_local', date('MM'))
            ->where('user_id', $request->id)
            ->sum('distance');
        $activitiesWeek = app(Activity::class)
            ->whereBetween('start_date_local', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
            )
            ->where('user_id', $request->id)
            ->sum('distance');

        if ($stat && isset($activitiesYear) && isset($activitiesMonth) && isset($activitiesWeek)) {

            $data['totalDistance'] = floor($this->getDistance($stat->distance));

            $data['yearDistance'] = floor($this->getDistance($activitiesYear));

            $data['monthDistance'] = floor($this->getDistance($activitiesMonth));

            $data['weekDistance'] = floor($this->getDistance($activitiesWeek));

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
