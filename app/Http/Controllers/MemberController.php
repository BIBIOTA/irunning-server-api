<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Activity;

use App\Http\Controllers\Traits\Running;

class MemberController extends Controller
{

    public $members;

    use Running;

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
            });
            
            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }
}
