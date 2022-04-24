<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ImageService
{
    /**
     * @param string $path
     * @param string $name
     *
     * @return string|null
     */
    public function getImage(string $path, string $name): ?string
    {
        $storage = Storage::disk('s3');

        $filePath = $path . '/'. $name;

        if (!$storage->exists($filePath)) {
            return null;
        }

        return $storage->get($filePath);
    }

    /**
     * @param File $image
     * @param string $path
     *
     * @return string
     */
    public function uploadImage(File $image, string $path): string
    {
        $storage = Storage::disk('s3');

        $path = $storage->put($path, $image);

        return $path;
    }
}
