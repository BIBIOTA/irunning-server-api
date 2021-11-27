<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\EventDistance;

use Carbon\Carbon;

class EventController extends Controller
{
    public function getEvents(Request $request) {

        $this->filters = [
            'startDay' => $request->startDay,
            'endDay' => $request->endDay,
            'distances' => $request->distances,
            'keywords' => $request->keywords,
        ];

        if (isset($this->filters['distances']) && is_array($this->filters['distances']) ) {
            $distances = app(EventDistance::class)->get();
            $this->filters['ids'] = [];
            foreach($distances as $distance) {
                $hasDistance = app(EventDistance::class)->distanceFilter($distance, $this->filters['distances']);
                if ($hasDistance) {
                    if (!in_array($distance->event_id, $this->filters['ids'])) {
                        array_push($this->filters['ids'], $distance->event_id);
                    }
                }
            }
        }

        $rows = app(Event::class)->getFilterData($this->filters);

        if ($rows->count() > 0) {

            $rows->getCollection()->transform(function($row){
                $row['distance'] = ($row->distance) ? $row->distance : null;
                return $row;
            });

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $rows], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);   
    }

    public function getIndexEvents(Request $request) {

        $rows = app(Event::class)
            ->where('event_status', 1)
            ->where('event_date', '>=', Carbon::now())
            ->orderBy('event_date', 'ASC')->limit(5)
            ->get();

        if ($rows->count() > 0) {

            $data = $rows->map(function($row){
                return [
                    'event_name' => $row->event_name,
                ];
            });

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);   
    }
}
