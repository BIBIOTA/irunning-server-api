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
        $hour = gmdate('H', $movingTime) > 0 ? gmdate('H', $movingTime): 1;
        $min = gmdate('i', $movingTime);
        $sec = gmdate('s', $movingTime);

        $pace = gmdate('i:s', (($hour*$min*60) + $sec) / $this->floor_dec($distance / 1000, 2));
        return $pace;
    }
    
    // 無條件捨去(值,小數點位數)
    private function floor_dec($v, $precision){
        $c = pow(10, $precision);
        return floor($v*$c)/$c;
    }
}
