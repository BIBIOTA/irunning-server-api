<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;
use Throwable;

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
     * @return JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);
            if (!$token = Auth::guard()->attempt($credentials)) {
                return response()->json(['status' => false, 'message' => '登入失敗'], 401);
            }
            return $this->respondWithToken($token);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function login error', 'main' => $e]);
        }
    }
    /**
     * Get a authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        try {
            return response()->json(
                ['status' => true, 'message' => '資料取得成功', 'data' => Auth::guard()->user()],
            );
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function me error', 'main' => $e]);
        }
    }
    /**
     * Log the user out(Invalidate the Token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            Auth::guard()->logout();
            return response()->json(['status' => true, 'message' => '登出成功']);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function logout error', 'main' => $e]);
        }
    }
    /**
     * Refresh a Token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(Auth::guard()->refresh());
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function refresh error', 'main' => $e]);
        }
    }
    protected function respondWithToken($token)
    {
        try {
            return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function refresh error', 'main' => $e]);
        }
    }
}
