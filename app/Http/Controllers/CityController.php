<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\City;

class CityController extends Controller
{
    public function getCities (Request $request) {
        $data = app(City::class)->whereNotNull('dataid')->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

}