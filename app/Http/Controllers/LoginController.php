<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberToken;

use Illuminate\Http\Request;


class LoginController extends Controller
{
    public function login(Request $request) {
        $athlete = $request->athlete;
        $data = app(Member::class)->where('strava_id', $athlete['id'])->first();
        if ($data) {
            $data->update([
                'username' => $athlete['username'],
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
            $data = app(Member::class)->create([
                'id' => uniqid(),
                'strava_id' => $athlete['id'],
                'username' => $athlete['username'],
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

        $token = app(MemberToken::class)->where('user_id',$data->id)->first();

        if ($token) {
            app(MemberToken::class)->where('user_id',$data->id)->update([
                'expires_at' => date('Y-m-d H:i:s',$request->expires_at),
                'expires_in' => intval(gmdate('H',$request->expires_in)),
                'refresh_token' => $request->refresh_token,
                'access_token' => $request->access_token,
            ]);
        } else {
            app(MemberToken::class)->create([
                'id' => uniqid(),
                'user_id' => $data->id,
                'expires_at' => date('Y-m-d H:i:s',$request->expires_at),
                'expires_in' => intval(gmdate('H',$request->expires_in)),
                'refresh_token' => $request->refresh_token,
                'access_token' => $request->access_token,
            ]);
        }

        return response()->json(['status' => true, 'message' => '登入成功', 'data' => $data], 200);
    }
}
