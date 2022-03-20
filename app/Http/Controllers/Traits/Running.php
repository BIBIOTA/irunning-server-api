<?php

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

trait Running
{
    public function runnerType(string $type): ?string
    {
        switch ($type) {
            case 1:
                return '初階跑者';
                break;
            case 2:
                return '中階跑者';
                break;
            case 3:
                return '進階跑者';
                break;
            default:
                return null;
                break;
        }
    }

    public function getDistance(int $distance): int
    {
        return $distance / 1000;
    }

    public function getDistanceIsFloor(int $distance): int
    {
        return $this->floorDec($distance / 1000, 2);
    }

    public function getPace(int $distance, int $movingTime): string
    {
        $hour = gmdate('H', $movingTime);
        $min = gmdate('i', $movingTime);
        $sec = gmdate('s', $movingTime);

        $time = ($hour * 3600) + ($min * 60) + $sec;

        $distanceFloor = $this->floorDec($distance / 1000, 2);

        $pace = date('i:s', $time / (($distanceFloor > 0) ? $distanceFloor : 1));

        return $pace;
    }

    // 四捨五入(值,小數點位數)
    private function floorDec(int $v, int $precision): int
    {
        return round($v, $precision);
    }
}
