<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\Message;
use App\Services\ActivityService;
use App\Services\LoginService;
use App\Jobs\GetActivitiesDataFromStrava;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class LoginController extends Controller
{

    /**
     * Undocumented variable
     *
     * @var LoginService
     */
    private LoginService $loginService;

    private ActivityService $activityService;

    /**
     * @param LoginService $service
     */
    public function __construct(LoginService $loginService, ActivityService $activityService)
    {
        $this->loginService = $loginService;
        $this->activityService = $activityService;
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->loginService->login($request->all());
            
            $token = $data['jwtToken'];

            $member = $data['member'];

            $memberToken = $data['memberToken'];

            if ($token && $memberToken) {
                $this->activityService->getStats($member->strava_id, $memberToken);

                $newJob = new GetActivitiesDataFromStrava($memberToken, $this->activityService, true);
                dispatch($newJob);

                return $this->response($token, Message::SUCCESS);
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
            $this->loginService->logout();
            return $this->response(null, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function logout error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
