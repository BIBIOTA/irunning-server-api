<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\StravaActivitiesTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\Message;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class LoginController extends Controller
{
    use StravaActivitiesTrait;

    /**
     * Undocumented variable
     *
     * @var LoginService
     */
    private LoginService $service;

    /**
     * @param LoginService $service
     */
    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->service->login($request->all());

            if ($data) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::LOGINFAILED, Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $e) {
            $this->sendError('function login error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->service->logout();
            return $this->response(null, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function logout error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
