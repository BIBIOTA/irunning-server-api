<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\District;
use App\Jobs\SendEmail;
use Throwable;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->districts = new District();
    }

    public function getDistricts(Request $request)
    {
        try {
            $data = $this->districts->where('city_id', $request->city_id)->get();

            if ($data->count() > 0) {
                return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
            }

            return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function getDistricts error', 'main' => $e]);
        }
    }
}
