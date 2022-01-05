<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->events = new Event();
    }

    public function getIndexEvents(Request $request)
    {
        $rows = $this->events
            ->where('event_status', 1)
            ->where('event_date', '>=', Carbon::now())
            ->orderBy('event_date', 'ASC')->limit(5)
            ->get();

        if ($rows->count() > 0) {
            $data = $rows->map(function ($row) {
                return [
                    'event_name' => $row->event_name,
                ];
            });

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
