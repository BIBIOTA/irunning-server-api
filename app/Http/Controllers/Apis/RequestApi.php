<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Traits\Running;

use Illuminate\Support\Facades\Http;

use App\Models\Aqi;
use App\Models\Activity;
use App\Models\City;
use App\Models\District;
use App\Models\Event;
use App\Models\EventDistance;
use App\Models\Member;
use App\Models\MemberToken;
use App\Models\Weather;
use App\Models\Stat;

use App\Http\Controllers\Traits\StravaActivitiesTrait;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestApi extends Controller
{
    use Running;
    use StravaActivitiesTrait;

    public function getCities (Request $request) {
        $data = app(City::class)->whereNotNull('dataid')->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getDistricts (Request $request) {
        $data = app(District::class)->where('CityName', $request->CityName)->get();

        if ($data->count() > 0) {
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getWeather (Request $request) {
        $row = app(Weather::class)
                ->where('locationsName', $request->CityName)
                ->where('locationName', $request->AreaName)
                ->first();

        if ($row) {

            $data['CityName'] = $row->locationsName;
            $data['AreaName'] = $row->locationName;
            $data['temperature'] = $row->T->value.'°C';
            $data['AT'] = $row->AT->value.'°C';
            $data['PoP6h'] = $row->PoP6h->value.'%';
            $data['CI'] = $row->CI->value;
            $data['Wx'] = $row->Wx->WxDocument->text;
            $data['WxValue'] = $row->Wx->value;
            $data['updated_at'] = $row->updated_at;

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getIndexRunInfo (Request $request) {
        
        $stat = app(Stat::class)
                ->where('user_id', $request->id)
                ->first();

        $activities = app(Activity::class)
                    ->where('user_id', $request->id)
                    ->get();
        
        if ($activities->count() === 0) {
            $tokenData = app(MemberToken::class)->where('user_id', $request->id)->first();
            if ($tokenData) {
                $this->getActivitiesDataFromStrava($tokenData);
            } else {
                return response()->json(['status' => false, 'message' => '發生例外錯誤: 無法取得會員Token資料', 'data' => null], 404);
            }
        }

        $activitiesYear = app(Activity::class)
                    ->getActivitiesYear($request->id);

        $activitiesMonth = app(Activity::class)
                    ->getActivitiesMonth($request->id);

        $activitiesWeek = app(Activity::class)
                    ->getActivitiesWeek($request->id);

        if ($stat && isset($activitiesYear) && isset($activitiesMonth) && isset($activitiesWeek)) {

            $data['totalDistance'] = floor($this->getDistance($stat->distance));

            $data['yearDistance'] = floor($this->getDistance($activitiesYear));

            $data['monthDistance'] = floor($this->getDistance($activitiesMonth));

            $data['weekDistance'] = floor($this->getDistance($activitiesWeek));

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function updateMemberLocation (Request $request) {
        
        $validator = Validator::make($request->all(), [
            'county'=>'required',
            'district'=>'required',
            'siteName'=>'required',
            'id'=>'required',
        ], [
            'county.required'=>'居住地資料更新失敗:缺少縣市參數',
            'district.required'=>'居住地資料更新失敗:缺少鄉鎮區參數',
            'siteName.required'=>'居住地資料更新失敗:缺少空氣品質測量站參數',
            'id.required'=>'居住地資料更新失敗:缺少會員參數',
        ]);
        if ($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()->all()[0], 'data'=>null], 400);
        }

        $member = app(Member::class)->where('id', $request->id)->first();

        if ($member) {
            $member->county = $request->county;
            $member->district = $request->district;
            $member->siteName = $request->siteName;
            $member->save();

            $member->memberToken;

            return response()->json(['status' => true, 'message' => '會員居住地資料更新成功', 'data' => $member], 200);
        }

        return response()->json(['status' => false, 'message' => '居住地資料更新失敗:無法取得會員資料', 'data' => null], 404);
    }

    public function getActivities (Request $request) {

        $count = app(Activity::class)->where('user_id', $request->id)->count();

        if ($count < 1) {
            $tokenData = app(MemberToken::class)->where('user_id', $request->id)->first();
            if ($tokenData) {
                $this->getActivitiesDataFromStrava($tokenData);
            } else {
                return response()->json(['status' => false, 'message' => '發生例外錯誤: 無法取得會員Token資料', 'data' => null], 404);
            }
        }

        $this->filters = [
            'id' => $request->id,
            'startDay' => $request->startDay,
            'endDay' => $request->endDay,
        ];

        $data = app(Activity::class)->getFilterData($this->filters);

        if ($data->count() > 0) {

            $data->getCollection()->transform(function($row){
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'pace' => $this->getPace($row->distance, $row->moving_time),
                    'distance' => $this->getDistanceIsFloor($row->distance),
                    'moving_time' => $row->moving_time,
                    'start_date_local' => $row->start_date_local,
                    'summary_polyline' => $row->summary_polyline,
                ];
            });
            
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function getActivity (Request $request, $memberUuid, $runningUuId) {

        $validator = Validator::make([
            'memberUuid'=> $memberUuid,
            'runningUuId'=> $runningUuId,
        ], [
            'memberUuid'=>'required',
            'runningUuId'=>'required',
        ], [
            'runningUuId.required'=>'缺少會員uuid資料',
            'memberUuid.required'=>'缺少跑步紀錄uuid資料',
        ]);
        if ($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()->all()[0], 'data'=>null], 400);
        }

        return $this->getActivityFromStrava($memberUuid, $runningUuId);

    }

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
