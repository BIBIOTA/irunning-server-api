<?php

namespace App\Services;

use App\Repositories\EventRepository;
use App\Repositories\EventDistanceRepository;

class EventService
{
    private $eventRepository;
    private $eventDistanceRepository;

    public function __construct(EventRepository $eventRepository, EventDistanceRepository $eventDistanceRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->eventDistanceRepository = $eventDistanceRepository;
    }

    /**
     * @param array $request
     *
     * @return mixed
     */
    public function getEvents(array $filters)
    {
        if (isset($filters['distances']) && is_array($filters['distances'])) {
            $distances = $this->eventDistanceRepository->getDistances();
            $filters['ids'] = [];
            foreach ($distances as $distance) {
                $hasDistance = $this->eventDistanceRepository->distanceFilter($distance, $filters['distances']);
                if ($hasDistance) {
                    if (!in_array($distance->event_id, $filters['ids'])) {
                        array_push($filters['ids'], $distance->event_id);
                    }
                }
            }
        }

        return $this->eventRepository->getEvents($filters);
    }

    public function getIndexEvents(): array
    {
        return $this->eventRepository->getIndexEvents();
    }
}
