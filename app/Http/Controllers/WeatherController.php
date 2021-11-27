<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Weather;

class WeatherController extends Controller
{

    public function __construct()
    {
        $this->weathers = new Weather;
    }

    public function getWeather (Request $request) {
        $row = $this->weathers
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
            $data['Wx'] = $row->Wx->WxDocument->text;
            $data['WxValue'] = $row->Wx->value;
            $data['updated_at'] = $row->updated_at;

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
