<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * Get a JWT via given credentials.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = Auth::guard()->attempt($credentials)) {
            return response()->json(['status' => false, 'message' => '登入失敗'], 401);
        }
        return $this->respondWithToken($token);
    }
    /**
     * Get a authenticated User.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(
            ['status' => true, 'message' => '資料取得成功', 'data' => Auth::guard()->user()],
        );
    }
    /**
     * Log the user out(Invalidate the Token).
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function logout()
    {
        Auth::guard()->logout();
        return response()->json(['status' => true, 'message'=>'登出成功']);
    }
    /**
     * Refresh a Token.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=>true,
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>Auth::guard()->factory()->getTTL()*60
        ]);
    }
}
