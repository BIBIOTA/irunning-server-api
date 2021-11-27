<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Aqi;

class AqiController extends Controller
{
    public function getAqiList (Request $request) {
        $data = app(Aqi::class)->where('County', $request->County)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
