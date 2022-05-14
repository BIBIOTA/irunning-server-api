<?php

namespace App\Http\Controllers;

use App\Http\Requests\TelegramSubScribeRequest;
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
