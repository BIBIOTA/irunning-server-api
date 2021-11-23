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

    public function getDistanceIsFloor($distance)
    {
        return $this->floor_dec($distance / 1000, 2);
    }

    public function getPace($distance, $movingTime)
    {
        $hour = gmdate('H', $movingTime);
        $min = gmdate('i', $movingTime);
        $sec = gmdate('s', $movingTime);

        $time=($hour*3600)+($min*60)+$sec;

        $pace = date('i:s', $time / $this->floor_dec($distance / 1000, 2));
        return $pace;
    }
    
    // 四捨五入(值,小數點位數)
    private function floor_dec($v, $precision){
        return round($v, $precision);
    }
}
