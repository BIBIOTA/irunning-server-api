<?php

namespace App\Services;

use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Exception;

class EventService
{
    private EventRepository $eventRepository;

    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(
        EventRepository $eventRepository,
    ) {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param array $request
     *
     * @return mixed
     */
    public function getEvents(array $filters)
    {
        return $this->eventRepository->getEvents($filters);
    }

    /**
     * @param string $eventName
     *
     * @return void
     */
    public function getEventByEventNameAndDate(string $eventName, string $eventDate)
    {
        return $this->eventRepository->getEventByEventNameAndDate($eventName, $eventDate);
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
    public function updateEvent(string $eventId, array $data): void
    {
        $input = $this->makeEventInput($data, $eventId);

        $distanceInput = $this->makeEventDistanceInput($data, $eventId);

        $this->eventRepository->updateEvent($input, $distanceInput);
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

        $this->storeNewEventToRedis($data);

        return $this->eventRepository->createEvent($input, $distanceInput);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function storeNewEventToRedis(array $data): void
    {
        Redis::lpush('new_event', json_encode($data));
        Redis::expire('new_event', 60 * 60 * 12);
    }

    /**
     * @param array $events
     *
     * @return void
     */
    public function sendNewEventFromRedis(array $events, array $userIds): void
    {
        $response = Http::post(
            env('NODE_URL') . '/api/newEvents',
            [ 'events' => $events, 'userIds' => $userIds ]
        );
        if ($response->status() === 200) {
            return;
        }

        throw new Exception($response->json()['message']);
    }

    /**
     * @param array $input
     *
     * @return void
     */
    public function sendUpdatedEvents(array $input):void
    {
        $response = Http::post(
            env('NODE_URL') . '/api/updatedEvent',
            ['data' => $input],
        );

        if ($response->status() === 200) {
            return;
        }

        throw new Exception($response->json()['message']);
    }

    /**
     *
     * @return array
     */
    public function getUpdatedEventsWithTelegramUser(): array
    {
        return $this->eventRepository->getUpdatedEventsWithTelegramUser();
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
