<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Aqi;

class AqiController extends Controller
{

    public function __construct()
    {
        $this->aqis = new Aqi;
    }

    public function getAqiList (Request $request) {
        $data = $this->aqis->where('city_id', $request->city_id)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
