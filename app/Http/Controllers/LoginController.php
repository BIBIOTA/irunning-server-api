<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberToken;
use App\Jobs\GetActivitiesDataFromStrava;
use App\Http\Controllers\Traits\StravaActivitiesTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use StravaActivitiesTrait;

    public function __construct()
    {
        $this->members = new Member();
        $this->memberTokens = new MemberToken();
    }

    public function login(Request $request)
    {
        try {
            // if (env('PRODUCTION') === 'develop') {
            //     if ($request->athlete['id'] !== env('STRAVA_DEV_ID', '93819542')) {
            //         return response()->json([
            //             'status' => false,
            //             'message' => '此網頁尚在開發階段，僅限開發帳號登入', 'data' => null
            //         ], 404);
            //     }
            // }

            $member = $this->createOrUpdateMember($request);

            $this->createOrUpdateToken($request, $member);

            try {
                $tokenData = $this->memberTokens->where('access_token', $request->access_token)->first();

                $this->getStats($member->strava_id, $tokenData);

                $newJob = new GetActivitiesDataFromStrava($tokenData, true);
                dispatch($newJob);

                $data = $this->getJwtToken($member, $request->expires_in);

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

    private function createOrUpdateMember($request)
    {
        try {
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
            return $data;
        } catch (Throwable $e) {
            Log::info($e);
            return response()->json(['status' => false, 'message' => '發生例外錯誤:登入失敗', 'data' => null], 404);
        }
    }

    private function createOrUpdateToken($request, $member)
    {
        try {
            $token = $this->memberTokens->where('member_id', $member->id)->first();

            if ($token) {
                $tokenData = $this->memberTokens->where('member_id', $member->id)->update([
                    'expires_at' => Carbon::parse(intval($request->expires_at))
                                    ->setTimezone('Asia/Taipei')
                                    ->format('Y-m-d H:i:s'),
                    'expires_in' => intval(gmdate('H', $request->expires_in)),
                    'refresh_token' => $request->refresh_token,
                    'access_token' => $request->access_token,
                ]);
            } else {
                $tokenData = $this->memberTokens->create([
                    'id' => uniqid(),
                    'member_id' => $member->id,
                    'expires_at' => Carbon::parse(intval($request->expires_at))
                                    ->setTimezone('Asia/Taipei')
                                    ->format('Y-m-d H:i:s'),
                    'expires_in' => intval(gmdate('H', $request->expires_in)),
                    'refresh_token' => $request->refresh_token,
                    'access_token' => $request->access_token,
                ]);
            }
            return $tokenData;
        } catch (Throwable $e) {
            Log::info($e);
            return response()->json(['status' => false, 'message' => '發生例外錯誤:登入失敗', 'data' => null], 404);
        }
    }

    public function getJwtToken($member, $expired)
    {
        if (!$token = Auth::guard()->fromUser($member)) {
            return response()->json(['status' => false, 'message' => '登入失敗'], 401);
        }
        return $this->respondWithToken($token, $expired);
    }

    protected function respondWithToken($token, $expired)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard()->factory()->getTTL() * floor(($expired / 60))
        ];
    }

    /**
     * Log the user out(Invalidate the Token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout()
    {
        Auth::guard()->logout();
        return response()->json(['status' => true, 'message' => '登出成功']);
    }
}
