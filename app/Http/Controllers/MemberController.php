<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;
use App\Models\Member;
use App\Models\MemberToken;
use App\Models\Stat;
use App\Http\Controllers\Traits\Running;
use App\Http\Controllers\Traits\StravaActivitiesTrait;

class MemberController extends Controller
{
    use Running;
    use StravaActivitiesTrait;

    public $members;


    public function __construct()
    {
        $this->members = new Member();
        $this->memberTokens = new MemberToken();
        $this->activities = new Activity();
        $this->stats = new Stat();
    }

    /* ============   admin  ============= */

    public function index(Request $request)
    {
        $filters = [
            'username' => $request->username ?? null,
        ];

        $data = $this->members->index($filters);

        if ($data->count() > 0) {
            $data->getCollection()->transform(function ($row) {
                return $this->memberDataProcess($row);
            });

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function view(Request $request, string $memberUuid)
    {
        $validator = Validator::make(
            [
                'memberUuid' => $memberUuid,
            ],
            [
                'memberUuid' => 'required',
            ],
            [
                'memberUuid.required' => '缺少uuid資料',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $member = $this->members->find($memberUuid);

        if ($member) {
            $data['member'] = $this->memberDataProcess($member);

            $activities = $this->activities
                            ->where('member_id', $memberUuid)
                            ->orderBy('start_date_local', 'DESC')
                            ->get();

            if ($activities->count() > 0) {
                $data['activities'] = $activities->map(function ($row) {
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

    public function runningInfo(Request $request, string $memberUuid, string $runningUuId)
    {
        $validator = Validator::make([
            'memberUuid' => $memberUuid,
            'runningUuId' => $runningUuId,
        ], [
            'memberUuid' => 'required',
            'runningUuId' => 'required',
        ], [
            'runningUuId.required' => '缺少會員uuid資料',
            'memberUuid.required' => '缺少跑步紀錄uuid資料',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        return $this->getActivityFromStrava($memberUuid, $runningUuId);
    }

    /* ============   client  ============= */

    public function read(Request $request, string $memberUuid)
    {
        $validator = Validator::make(
            [
                'memberUuid' => $memberUuid,
            ],
            [
                'memberUuid' => 'required',
            ],
            [
                'memberUuid.required' => '缺少uuid資料',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $member = $this->members->find($memberUuid);

        if ($member) {
            $data = $this->memberDataProcessforClientRead($member);

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function update(Request $request, string $memberUuid)
    {
        $form = [
            'username' => $request->username,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'county' => $request->county,
            'district' => $request->district,
            'runner_type' => $request->runnerType,
            // 'join_rank' => $request->joinRank,
        ];

        $validator = Validator::make($form, [
            'username' => 'required',
            'nickname' => 'required',
            'email' => 'required',
            'county' => 'required',
            'district' => 'required',
            'runner_type' => 'required',
            // 'join_rank'=>'required',
        ], [
            'username.required' => '請填寫姓名',
            'nickname.required' => '請填寫暱稱',
            'email.required' => '請填寫email',
            'county.required' => '請填寫居住城市',
            'district.required' => '請填寫居住鄉鎮區',
            'runner_type.required' => '請填寫跑步經驗',
            // 'join_rank.required'=>'發生例外錯誤:缺少join_rank參數',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $member = $this->members->find($memberUuid);

        if ($member) {
            if ($member->is_register === 1) {
                $member->update($form);
            } else {
                $form['is_register'] = 1;
                $member->update($form);
            }
            return response()->json(['status' => true, 'message' => '資料更新成功', 'data' => null], 200);
        } else {
            return response()->json(['status' => false, 'message' => '查無會員資料', 'data' => null], 404);
        }
    }

    public function getIndexRunInfo(Request $request, string $memberUuid)
    {
        $stat = $this->stats->where('member_id', $memberUuid)->first();


        $activitiesCount = $this->activities->where('member_id', $memberUuid)->count();

        if ($activitiesCount === 0) {
            $tokenData = $this->memberTokens->where('member_id', $memberUuid)->first();
            if ($tokenData) {
                $this->getActivitiesDataFromStrava($tokenData);
            } else {
                return response()->json(['status' => false, 'message' => '發生例外錯誤: 無法取得會員Token資料', 'data' => null], 404);
            }
        }

        $activitiesYear = $this->activities
                    ->getActivitiesYear($memberUuid);

        $activitiesMonth = $this->activities
                    ->getActivitiesMonth($memberUuid);

        $activitiesWeek = $this->activities
                    ->getActivitiesWeek($memberUuid);

        if ($stat && isset($activitiesYear) && isset($activitiesMonth) && isset($activitiesWeek)) {
            $data['totalDistance'] = floor($this->getDistance($stat->distance));

            $data['yearDistance'] = floor($this->getDistance($activitiesYear));

            $data['monthDistance'] = floor($this->getDistance($activitiesMonth));

            $data['weekDistance'] = floor($this->getDistance($activitiesWeek));

            return response()->json(['status' => true, 'message' => '取得資料成功', 'data' => $data], 200);
        }

        return response()->json(['status' => false, 'message' => '查無任何資料', 'data' => null], 404);
    }

    public function updateMemberLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'county' => 'required',
            'district' => 'required',
            'siteName' => 'required',
            'id' => 'required',
        ], [
            'county.required' => '居住地資料更新失敗:缺少縣市參數',
            'district.required' => '居住地資料更新失敗:缺少鄉鎮區參數',
            'siteName.required' => '居住地資料更新失敗:缺少空氣品質測量站參數',
            'id.required' => '居住地資料更新失敗:缺少會員參數',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 400);
        }

        $member = $this->members->where('id', $request->id)->first();

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

    private function memberDataProcess(object $row)
    {
        $stat = $row->stat;

        $activitiesMonth = $this->activities->getActivitiesMonth($row->id);

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

    private function memberDataProcessforClientRead(object $row)
    {
        return [
            'id' => $row->id,
            'username' => $row->username ?? '',
            'nickname' => $row->nickname ?? '',
            'email' => $row->email,
            'county' => $row->county,
            'district' => $row->district,
            'runnerType' => $row->runner_type,
            'joinRank' => $row->join_rank,
        ];
    }
}
