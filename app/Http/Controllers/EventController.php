<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEventsRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Services\EventService;
use Throwable;

class EventController extends Controller
{
    private $service;

    public function __construct(EventService $eventService)
    {
        $this->service = $eventService;
    }

    /**
     * @param GetEventsRequest $request
     *
     * @return JsonResponse
     */
    public function getEvents(GetEventsRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getEvents($request->all());

            if ($data->count() > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getEvents error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
