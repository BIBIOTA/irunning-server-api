<?php

namespace App\Http\Controllers;

use App\Services\CityService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use Throwable;

class CityController extends Controller
{
    private CityService $service;

    public function __construct(CityService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getCities(): JsonResponse
    {
        try {
            $data = $this->service->getCities();

            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getCities error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
