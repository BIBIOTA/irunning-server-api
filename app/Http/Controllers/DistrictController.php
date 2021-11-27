<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\District;

class DistrictController extends Controller
{

    public function __construct()
    {
        $this->districts = new District;
    }

    public function getDistricts (Request $request) {
        $data = $this->districts->where('CityName', $request->CityName)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
