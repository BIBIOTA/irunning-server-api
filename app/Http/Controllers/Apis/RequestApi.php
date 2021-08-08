<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Support\Facades\Http;

use App\Models\Aqi;
use App\Models\City;
use App\Models\District;
use App\Models\Weather;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestApi extends Controller
{
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
}
