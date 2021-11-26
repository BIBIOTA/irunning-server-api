<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\Activity;

use App\Http\Controllers\Traits\Running;
use App\Http\Controllers\Traits\StravaActivitiesTrait;

class MemberController extends Controller
{

    public $members;

    use Running;
    use StravaActivitiesTrait;

    public function __construct()
    {
        $this->members = new Member;
        $this->activity = new Activity;
    }

    public function index(Request $request) {
        $filters = [
            'username' => $request->username ?? null,
        ];

        $data = $this->members->index($filters);

        if ($data->count() > 0) {

            $data->getCollection()->transform(function($row){

                return $this->memberDataProcess($row);
                
            });
            
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function view (Request $request, $memberUuid) {

        $validator = Validator::make(
            [
                'memberUuid' => $memberUuid,
            ], [
            'memberUuid'=>'required',
        ], [
            'memberUuid.required'=>'缺少uuid資料',
        ]);
        if ($validator->fails()){
            return response()->json(['status'=>false, 'message'=>$validator->errors()->all()[0], 'data'=>null], 400);
        }

        $member = $this->members->find($memberUuid);

        if ($member) {

            $data['member'] = $this->memberDataProcess($member);

            $this->filters = [
                'id' => $memberUuid,
            ];
    
            $activities = app(Activity::class)
                        ->where('user_id', $memberUuid)
                        ->orderBy('start_date_local', 'DESC')
                        ->get();

            if ($activities->count() > 0) {
                $data['activities'] = $activities->map(function($row){
                    return [
                        'id' => $row->id,
                        'distance' => $this->getDistanceIsFloor($row->distance),
                        'movingTime' => $row->moving_time,
                        'time' => $row->start_date_local,
                    ];
                });
            }

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);

        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);

    }

    public function runningInfo (Request $request, $memberUuid, $runningUuId) {

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

    private function memberDataProcess($row) {

        $stat = $row->stat;
        
        $activitiesMonth = $this->activity->getActivitiesMonth($row->id);

        $runningStatus = [
            'totalDistance' => 0,
            'monthDistance' => 0,
        ];

        if ($stat && isset($activitiesMonth)) {

            $runningStatus['totalDistance'] = floor($this->getDistance($stat->distance));
            $runningStatus['monthDistance'] = floor($this->getDistance($activitiesMonth));

        }

        return [
            'id' => $row->id,
            'username' => $row->username ?? '未填寫',
            'loginFrom' => $row->login_from,
            'totalDistance' => $runningStatus['totalDistance'],
            'monthDistance' => $runningStatus['monthDistance'],
            'runnerType' => $this->runnerType($row->runner_type),
            'lastLoginAt' => $row->memberToken->updated_at,
        ];
    }
}
