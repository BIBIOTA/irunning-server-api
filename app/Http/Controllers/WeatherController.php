<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Weather;

class WeatherController extends Controller
{

    public function __construct()
    {
        $this->weathers = new Weather;
    }

    // TODO svg圖片及快取處理
    public function getWeatherImage (Request $request) {

        $validator = Validator::make($request->all(), [
            'dayOrNight'=>'required',
            'value'=>'required',
        ], [
            'dayOrNight.required'=>'取得天氣圖片失敗:缺少dayOrNight參數',
            'value.required'=>'取得天氣圖片失敗:缺少value參數',
        ]);

        if ($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()->all()[0], 'data'=>null], 400);
        }

        if (true === true) {

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => null], 200);
            
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
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
