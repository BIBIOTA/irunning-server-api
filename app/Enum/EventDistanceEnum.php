<?php
namespace App\Enum;

class EventDistanceEnum
{
    const MARATHON = 1;
    const HALF_MARATHON = 2;
    const TRIATHLON = 3;

    public static function getTypes()
    {
        return [self::MARATHON, self::HALF_MARATHON, self::TRIATHLON];
    }
}
