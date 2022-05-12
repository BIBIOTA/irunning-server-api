<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GetActivitiesRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Services\ActivityService;
use App\Http\Controllers\Traits\Running;
use App\Http\Controllers\Traits\MemberTrait;
use Throwable;

class ActivityController extends Controller
{
    use Running;
    use MemberTrait;

    private ActivityService $service;

    public function __construct(ActivityService $service)
    {
        $this->service = $service;
    }

    public function getActivities(GetActivitiesRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getActivities($request->all());

            if ($data->count() > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getActivities error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param string $runningUuId
     *
     * @return JsonResponse
     */
    public function getActivity(Request $request, string $runningUuId): JsonResponse
    {
        try {
            $member = $this->me();

            $data = $this->service->getActivityFromStrava($member->id, $runningUuId);

            return $this->response($data, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function getActivity error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
