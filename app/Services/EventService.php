<?php

namespace App\Services;

use App\Repositories\EventRepository;
use App\Repositories\EventDistanceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventService
{
    private EventRepository $eventRepository;
    private EventDistanceRepository $eventDistanceRepository;

    /**
     * @param EventRepository $eventRepository
     * @param EventDistanceRepository $eventDistanceRepository
     */
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

    /**
     * @param string $eventName
     *
     * @return void
     */
    public function getEventByEventName(string $eventName)
    {
        return $this->eventRepository->getEventByEventName($eventName);
    }

    /**
     *
     * @return array
     */
    public function getIndexEvents(): array
    {
        return $this->eventRepository->getIndexEvents();
    }

    /**
     * @param string $eventId
     * @param array $data
     *
     * @return void
     */
    public function updateEvent(string $eventId, array $data)
    {
        $input = $this->makeEventInput($data, $eventId);

        $distanceInput = $this->makeEventDistanceInput($data, $eventId);

        return $this->eventRepository->updateEvent($input, $distanceInput);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function createEvent(array $data)
    {
        $id = uniqid();
        $input = $this->makeEventInput($data, $id);

        $distanceInput = $this->makeEventDistanceInput($data, $id);

        return $this->eventRepository->createEvent($input, $distanceInput);
    }

    /**
     * @param array $data
     * @param string $eventId
     *
     * @return array
     */
    private function makeEventInput(array $data, string $eventId): array
    {
        $eventsColumns = DB::getSchemaBuilder()->getColumnListing('events');

        $input = [
            'id' => $eventId,
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $eventsColumns)) {
                $input[$key] = $value;
            }
        }

        return $input;
    }

    /**
     * @param array $data
     * @param string $eventId
     *
     * @return array
     */
    private function makeEventDistanceInput(array $data, string $eventId): array
    {
        $eventsDistancesColumns = DB::getSchemaBuilder()->getColumnListing('events_distances');

        $distanceInput = [];
        foreach ($data['distances'] as $distance) {
            $distanceData = [
                        'id' => uniqid(),
                        'event_id' => $eventId,
                    ];
            foreach ($distance as $key => $value) {
                if (in_array($key, $eventsDistancesColumns)) {
                    $distanceData[$key] = $value;
                }
            }
            array_push($distanceInput, $distanceData);
        }

        return $distanceInput;
    }
}
