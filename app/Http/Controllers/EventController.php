<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventDistance;

class EventController extends Controller
{
    public function __construct()
    {
        $this->events = new Event();
        $this->eventDistances = new EventDistance();
    }

    public function getEvents(Request $request)
    {
        $this->filters = [
            'startDay' => $request->startDay,
            'endDay' => $request->endDay,
            'distances' => $request->distances,
            'keywords' => $request->keywords,
        ];

        if (isset($this->filters['distances']) && is_array($this->filters['distances'])) {
            $distances = $this->eventDistances->get();
            $this->filters['ids'] = [];
            foreach ($distances as $distance) {
                $hasDistance = $this->eventDistances->distanceFilter($distance, $this->filters['distances']);
                if ($hasDistance) {
                    if (!in_array($distance->event_id, $this->filters['ids'])) {
                        array_push($this->filters['ids'], $distance->event_id);
                    }
                }
            }
        }

        $rows = $this->events->getFilterData($this->filters);

        if ($rows->count() > 0) {
            $rows->getCollection()->transform(function ($row) {
                $row['distance'] = ($row->distance) ? $row->distance : null;
                return $row;
            });

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $rows], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
