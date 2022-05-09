<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAqiRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Services\AqiService;
use Throwable;

class AqiController extends Controller
{
    private AqiService $service;

    public function __construct(AqiService $service)
    {
        $this->service = $service;
    }

    public function getAqiList(GetAqiRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getAqiList($request->city_id);

            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getAqiList error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
