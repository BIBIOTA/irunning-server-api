<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendEmail;
use App\Models\User;
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
        $this->middleware('admin', ['except' => ['login']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'email is required',
                'password.required' => 'password is required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()[0],
                    'data' => null
                ], 400);
            }
        
            $credentials = $request->all();
            if (!$token = Auth::guard('admin')->attempt($credentials)) {
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
            Auth::guard('admin')->logout();
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
            return $this->respondWithToken(Auth::guard('admin')->refresh());
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
                'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60
            ]);
        } catch (Throwable $e) {
            Log::stack(['controller', 'slack'])->critical($e);
            SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'function refresh error', 'main' => $e]);
        }
    }
}
