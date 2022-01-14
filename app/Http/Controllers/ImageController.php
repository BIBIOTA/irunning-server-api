<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    public function getWeatherImage($dayOrNight, $number)
    {
        try {
            $path = '/weather' . '/' . $dayOrNight . '/' . $number . '.svg';
            if (Storage::disk('s3')->exists($path)) {
                $image = Storage::disk('s3')->get($path);
                return response($image)->header('Content-Type', 'image/svg+xml');
            }
            abort(404);
        } catch (NotFoundHttpException $e) {
            Log::info(['message' => '缺少圖片', 'request' => [ 'dayOrNight' => $dayOrNight, 'number' => $number ]]);
            return abort(404);
        } catch (Throwable $e) {
            Log::critical($e);
            return abort(500, $e);
        }
    }
}
