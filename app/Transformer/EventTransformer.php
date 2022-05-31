<?php

namespace App\Transformer;

class EventTransformer
{
    public function transform(object $event)
    {
        $event->distance = $this->transformDistance($event->distance);
        return $event;
    }

    private function transformDistance(object $distances)
    {
        $distances->map(function ($distance) {
            if (isset($distance->distance)) {
                $distance->distance = (string)$distance->distance . 'K';
            }
        });
    }
}
