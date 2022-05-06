<?php
namespace App\Repositories;

use App\Models\EventDistance;

class EventDistanceRepository
{
    private $model;

    public function __construct(EventDistance $eventDistance)
    {
        $this->model = $eventDistance;
    }

    public function getDistances()
    {
        return $this->model->all();
    }

    /**
     * @param object $distance
     * @param array $filters
     *
     * @return void
     */
    public function distanceFilter(object $distance, array $filters)
    {
        foreach ($filters as $filter) {
            if ($filter == 1) {
                if (str_contains($distance->event_distance, '42K')) {
                    return $distance;
                }
                if (str_contains($distance->event_distance, '42.195K')) {
                    return $distance;
                }
            }
            if ($filter == 2) {
                if (str_contains($distance->event_distance, '21K')) {
                    return $distance;
                }
                if (str_contains($distance->event_distance, '21.0975K')) {
                    return $distance;
                }
            }
            if ($filter == 3) {
                if (substr_count($distance->event_distance, '+') === 2) {
                    return $distance;
                }
            }
        }
        return null;
    }
}
