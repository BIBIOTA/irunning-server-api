<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Message;
use App\Http\Requests\GetNewsRequest;
use Throwable;

class NewsController extends Controller
{
    private $service;

    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }

    public function getNews(GetNewsRequest $request): JsonResponse
    {
        try {
            $data = $this->service->getNews($request->all());
    
            return $this->response($data, Message::SUCCESS);
        } catch (Throwable $e) {
            $this->sendError('function getNews error', $e);
            return $this->response(null, Message::SERVERERROR, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
