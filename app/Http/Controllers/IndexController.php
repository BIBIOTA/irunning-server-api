<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\EventService;
use App\Http\Responses\Message;
use Throwable;

class IndexController extends Controller
{
    private $service;

    public function __construct(EventService $eventService)
    {
        $this->service = $eventService;
    }

    public function getIndexEvents(Request $request)
    {
        try {
            $data = $this->service->getIndexEvents();

            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getIndexEvents error', $e);
            return $this->responseError(Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
