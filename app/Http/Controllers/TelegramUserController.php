<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramSubScribeRequest;
use App\Http\Requests\TelegramFollowEventRequest;
use App\Http\Requests\GetFollowingEventRequest;
use App\Services\TelegramUserService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use Throwable;

class TelegramUserController extends Controller
{
    private TelegramUserService $service;

    /**
     * @param TelegramUserService $service
     */
    public function __construct(TelegramUserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param TelegramFollowEventRequest $request
     *
     * @return JsonResponse
     */
    public function followEvent(TelegramFollowEventRequest $request): JsonResponse
    {
        try {
            $result = $this->service->followEvent($request->all());

            if ($result) {
                return $this->response(null, Message::SUCCESS);
            } else {
                return $this->response(['code' => 'IR401'], Message::DATAEXISTS, Response::HTTP_BAD_REQUEST);
            }
        } catch (Throwable $e) {
            $this->sendError('function followEvent error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TelegramFollowEventRequest $request
     *
     * @return JsonResponse
     */
    public function unfollowEvent(TelegramFollowEventRequest $request): JsonResponse
    {
        try {
            $this->service->unfollowEvent($request->all());

            return $this->response(null, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function unfollowEvent error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param GetFollowingEventRequest $request
     *
     * @return JsonResponse
     */
    public function getFollowingEvent(GetFollowingEventRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getFollowingEvent($request->userId);
            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function followEvent error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TelegramSubScribeRequest $request
     *
     * @return JsonResponse
     */
    public function subscribe(TelegramSubScribeRequest $request): JsonResponse
    {
        try {
            $this->service->subscribe($request->userId, $request->option);

            return $this->response(null, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function subscribe error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param TelegramSubScribeRequest $request
     *
     * @return JsonResponse
     */
    public function unsubscribe(TelegramSubScribeRequest $request): JsonResponse
    {
        try {
            $this->service->unsubscribe($request->userId, $request->option);

            return $this->response(null, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function unsubscribe error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
