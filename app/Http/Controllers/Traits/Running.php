<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

trait Running
{
    public function getDistance($distance)
    {
        return $distance / 1000;
    }
}
