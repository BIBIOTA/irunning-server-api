<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WeatherDocument;
use App\Models\WeatherData;
use App\Models\District;
use App\Jobs\SendEmail;
use Throwable;

class WeatherController extends Controller
{
    public function __construct()
    {
        $this->WeatherDocuments = new WeatherDocument();
        $this->weatherDatas = new WeatherData();
        $this->districts = new District();
    }

    public function getWeather(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'district_id' => 'required',
                ],
                [
                    'district_id.required' => '缺少鄉鎮區id參數',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()[0],
                    'data' => null
                ], 400);
            }

            $district = $this->districts->where('id', $request->district_id)->first();

            $city = $district->city;

            $infoData = [
                'city' => $city->city_name,
                'district' => $district->district_name,
                'updated_at' => $district->updated_at,
            ];

            $weatherDetails = $this->WeatherDocuments->get();

            if ($weatherDetails->count() > 0) {
                $weatherDatas = [];
                foreach ($weatherDetails as $weatherDetail) {
                    $weatherData = $this->weatherDatas->getData($weatherDetail->id, $request->district_id);
                    if ($weatherData) {
                        $weatherDatas[$weatherDetail->name] = $weatherData->value;

                        if ($weatherDetail->name === 'Wx') {
                            $weatherDatas['WxValue'] = $weatherData->wxDocument->text;
                        }
                    } else {
                        $weatherDatas[$weatherDetail->name] = null;
                    }
                }
            }

            $response = array_merge($infoData, $weatherDatas);

            if (count($response) > 0) {
                return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $response], 200);
            }

            return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
        } catch (Throwable $e) {
            Log::channel('controller')->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function getWeather error', 'main' => $e]);
        }
    }
}
