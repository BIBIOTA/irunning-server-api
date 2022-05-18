<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Models\WeatherDocument;
use App\Models\WeatherData;
use App\Models\District;
use App\Jobs\SendEmail;
use Carbon\Carbon;
use Throwable;

class WeatherController extends Controller
{
    public function __construct()
    {
        $this->WeatherDocuments = new WeatherDocument();
        $this->weatherDatas = new WeatherData();
        $this->districts = new District();
    }

    public function getWeather(Request $request): JsonResponse
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

            $data = array_merge($infoData, $weatherDatas);

            $data['imageUrl'] = $this->getWeatherImage('day', intval($data['Wx']));

            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getWeather error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRamdomWeatherImage(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'count' => 'required',
                ],
                [
                    'count.required' => '缺少圖片數量參數',
                    'count.number' => 'count muse be number',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()[0],
                    'data' => null
                ], 400);
            }

            $urls = [];

            for ($i = 1; $i <= $request->count; $i++) {
                $WxValue = rand(1, 42);
                if ($WxValue !== 40) {
                    array_push($urls, $this->getWeatherImage($this->getDayOrNight(), $WxValue));
                }
            }

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $urls], 200);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(
                env('ADMIN_MAIL'),
                ['title' => 'function getRamdomWeatherImage error', 'main' => $e]
            );
        }
    }

    private function getWeatherImage(string $dayOrNight, int $number)
    {
        try {
            $path = '/weather' . '/' . $dayOrNight . '/' . $number . '.svg';
            if (Storage::disk('s3')->exists($path)) {
                return Storage::disk('s3')->url($path);
            }
        } catch (NotFoundHttpException $e) {
            Log::info(['message' => '缺少圖片', 'request' => [ 'dayOrNight' => $dayOrNight, 'number' => $number ]]);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
        }
        return null;
    }

    private function getDayOrNight()
    {
        $hour = intval(Carbon::now()->format('H'));
        if ($hour > 6 && $hour < 18) {
            return 'day';
        } else {
            return 'night';
        }
    }
}
