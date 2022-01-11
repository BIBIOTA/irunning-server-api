<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

trait MemberTrait
{
    /**
     * Get a authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function me()
    {
        $data = Auth::guard()->user();
        if (!$data) {
            return false;
        }
        return $data;
    }
}
