<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberToken;

use App\Http\Controllers\Traits\StravaActivitiesTrait;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    use StravaActivitiesTrait;

    public function __construct()
    {
        $this->members = new Member;
        $this->memberTokens = new MemberToken;
    }

    public function login(Request $request) {
        try {
            
            if (env('PRODUCTION') === 'develop') {
                if ($request->athlete['id'] !== env('STRAVA_DEV_ID', '93819542')) {
                    return response()->json(['status' => false, 'message' => '此網頁尚在開發階段，僅限開發帳號登入', 'data' => null], 404); 
                }
            }

            $athlete = $request->athlete;
            $data = $this->members->where('strava_id', $athlete['id'])->first();

            if ($data) {
                $data->update([
                    'resource_state' => $athlete['resource_state'],
                    'firstname' => $athlete['firstname'],
                    'lastname' => $athlete['lastname'],
                    'city' => $athlete['city'],
                    'state' => $athlete['state'],
                    'country' => $athlete['country'],
                    'sex' => $athlete['sex'],
                    'badge_type_id' => $athlete['badge_type_id'],
                    'weight' => $athlete['weight'],
                ]);
            } else {
                $data = $this->members->create([
                    'id' => uniqid(),
                    'strava_id' => $athlete['id'],
                    'resource_state' => $athlete['resource_state'],
                    'firstname' => $athlete['firstname'],
                    'lastname' => $athlete['lastname'],
                    'city' => $athlete['city'],
                    'state' => $athlete['state'],
                    'country' => $athlete['country'],
                    'sex' => $athlete['sex'],
                    'badge_type_id' => $athlete['badge_type_id'],
                    'weight' => $athlete['weight'],
                ]);
            }

            $token = $this->memberTokens->where('user_id',$data->id)->first();

            if ($token) {
                $tokenData = $this->memberTokens->where('user_id',$data->id)->update([
                    'expires_at' => Carbon::parse(intval($request->expires_at))->setTimezone('Asia/Taipei')->format('Y-m-d H:i:s'),
                    'expires_in' => intval(gmdate('H',$request->expires_in)),
                    'refresh_token' => $request->refresh_token,
                    'access_token' => $request->access_token,
                ]);
            } else {
                $tokenData = $this->memberTokens->create([
                    'id' => uniqid(),
                    'user_id' => $data->id,
                    'expires_at' => Carbon::parse(intval($request->expires_at))->setTimezone('Asia/Taipei')->format('Y-m-d H:i:s'),
                    'expires_in' => intval(gmdate('H',$request->expires_in)),
                    'refresh_token' => $request->refresh_token,
                    'access_token' => $request->access_token,
                ]);
            }

            try {
                $tokenData = $this->memberTokens->where('access_token', $request->access_token)->first();
    
                $this->getStats($data->strava_id, $tokenData);

                $this->getActivitiesDataFromStrava($tokenData, true);
                Log::info($token->user_id.'Strava活動更新完成');

                $data['expires_at'] = Carbon::parse(intval($request->expires_at));
    
                return response()->json(['status' => true, 'message' => '登入成功', 'data' => $data], 200);
            } catch (Throwable $e) {
                Log::info($e);
                return response()->json(['status' => false, 'message' => '發生例外錯誤:Strava資料取得失敗', 'data' => null], 404);   
            }


        } catch (Throwable $e) {
            Log::info($e);
            return response()->json(['status' => false, 'message' => '發生例外錯誤:登入失敗', 'data' => null], 404);   
        }
    }
}
