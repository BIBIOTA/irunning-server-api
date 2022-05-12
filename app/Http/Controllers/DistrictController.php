<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDistrictRequest;
use App\Services\DistrictService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use Throwable;

class DistrictController extends Controller
{
    private DistrictService $service;

    /**
     * @param DistrictService $service
     */
    public function __construct(DistrictService $service)
    {
        $this->service = $service;
    }

    /**
     * @param GetDistrictRequest $request
     *
     * @return JsonResponse
     */
    public function getDistricts(GetDistrictRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getDistricts($request->city_id);

            if (count($data) > 0) {
                return $this->response($data, Message::SUCCESS);
            }

            return $this->response(null, Message::NOTFOUND, Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            $this->sendError('function getDistricts error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
