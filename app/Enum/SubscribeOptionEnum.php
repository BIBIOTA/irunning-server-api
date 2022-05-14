<?php
namespace App\Enum;

class SubscribeOptionEnum
{
    const NEWEVENTS = 'subscribe_new_events';

    public static function getOptions()
    {
        return [self::NEWEVENTS];
    }
}
