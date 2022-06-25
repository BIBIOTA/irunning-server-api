<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Jobs\SendEmail;
use Illuminate\Http\Response;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function response($data, string $message, $status = Response::HTTP_OK)
    {
        $contentStatus = $status === Response::HTTP_OK ? true : false;

        $content = [
            'status' => $contentStatus,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($content, $status);
    }

    protected function sendError(string $message, Throwable $e)
    {
        Log::stack(['controller', 'slack'])->critical($e);
    }
}
