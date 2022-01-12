<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Activity;
use App\Models\MemberToken;
use App\Http\Controllers\Traits\StravaActivitiesTrait;
use App\Http\Controllers\Traits\Running;
use App\Http\Controllers\Traits\MemberTrait;

class ActivityController extends Controller
{
    use Running;
    use StravaActivitiesTrait;
    use MemberTrait;

    public function __construct()
    {
        $this->memberTokens = new MemberToken();
        $this->activities = new Activity();
    }

    public function getActivities(Request $request)
    {
        $member = $this->me();

        $count = $this->activities->where('member_id', $member->id)->count();

        if ($count < 1) {
            $tokenData = $this->memberTokens->where('member_id', $member->id)->first();
            if ($tokenData) {
                $this->getActivitiesDataFromStrava($tokenData);
            } else {
                return response()->json(['status' => false, 'message' => '發生例外錯誤: 無法取得會員Token資料', 'data' => null], 404);
            }
        }

        $this->filters = [
            'id' => $member->id,
            'startDay' => $request->startDay,
            'endDay' => $request->endDay,
        ];

        $data = $this->activities->getFilterData($this->filters);

        if ($data->count() > 0) {
            $data->getCollection()->transform(function ($row) {
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

    public function getActivity(Request $request, string $runningUuId)
    {

        $validator = Validator::make([
            'runningUuId' => $runningUuId,
        ], [
            'runningUuId' => 'required',
        ], [
            'runningUuId.required' => '缺少活動紀錄uuid資料',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $member = $this->me();

        return $this->getActivityFromStrava($member->id, $runningUuId);
    }
}
