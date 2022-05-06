<?php

namespace App\Http\Controllers;

use App\Services\BannerService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Http\Requests\GetBannerRequest;
use Throwable;

class BannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    public function getBanners(GetBannerRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getBanners($request->all());
    
            return $this->response($data, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function getBanners error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
