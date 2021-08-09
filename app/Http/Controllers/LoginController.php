<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberToken;

use Illuminate\Http\Request;


class LoginController extends Controller
{
    public function login(Request $request) {
        $member = app(Member::class)->where('strava_id', $request->athlete->id)->first();

        if (!$member) {
            $member = app(Member::class)->create([
                'id' => uniqid(),
                'strava_id' => uniqid(),
                'username' => $request->athlete->username,
                'resource_state' => $request->athlete->resource_state,
                'firstname' => $request->athlete->firstname,
                'lastname' => $request->athlete->athlete,
                'city' => $request->athlete->city,
                'state' => $request->athlete->state,
                'country' => $request->athlete->country,
                'sex' => $request->athlete->sex,
                'badge_type_id' => $request->athlete->badge_type_id,
                'float' => $request->athlete->float,
            ]);
        } else {
            $member->update([
                'username' => $request->athlete->username,
                'resource_state' => $request->athlete->resource_state,
                'firstname' => $request->athlete->firstname,
                'lastname' => $request->athlete->athlete,
                'city' => $request->athlete->city,
                'state' => $request->athlete->state,
                'country' => $request->athlete->country,
                'sex' => $request->athlete->sex,
                'badge_type_id' => $request->athlete->badge_type_id,
                'float' => $request->athlete->float,
            ]);
        }
        app(MemberToken::class)->where('strava_id',$request->athlete->id)->updateOrCreate([
            'expires_at' => date('Y-m-d H:i:s',$request->expires_at),
            'expires_in' => intval(gmdate('H',$request->expires_in)),
            'refresh_token' => $request->refresh_token,
            'access_token' => $request->access_token,
        ]);

        return response()->json(['status' => true, 'message' => '登入成功', 'data' => $member], 200);
    }
}
