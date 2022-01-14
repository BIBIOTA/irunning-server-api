<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use App\Jobs\SendEmail;
use Throwable;

class CityController extends Controller
{
    public function __construct()
    {
        $this->cities = new City();
    }

    public function getCities(Request $request)
    {
        try {
            $data = $this->cities->whereNotNull('dataid')->get();

            if ($data->count() > 0) {
                return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
            }

            return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
        } catch (Throwable $e) {
            Log::channel('controller')->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function getCities error', 'main' => $e]);
        }
    }
}
