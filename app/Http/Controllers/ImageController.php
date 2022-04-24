<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\File;
use App\Services\ImageService;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    protected $service;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $path
     * @param string $filename
     *
     */
    public function getImage(string $path, string $filename)
    {
        $image = $this->service->getImage($path, $filename);

        if (empty($image)) {
            abort(404);
        }

        return response()->make($image, Response::HTTP_OK, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    /**
     * @param UploadImageRequest $request
     *
     */
    public function uploadImage(UploadImageRequest $request)
    {
        $path = $this->service->uploadImage(new File($request->file('image')), $request->path);

        return response()->json(['image' => $path], Response::HTTP_OK);
    }
}
