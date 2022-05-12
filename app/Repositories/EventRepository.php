<?php
namespace App\Repositories;

use App\Models\Event;
use App\Models\EventDistance;
use Carbon\Carbon;
use Throwable;

class EventRepository
{
    private Event $eventModel;
    private EventDistance $eventDistanceModel;

    /**
     * @param Event $event
     * @param EventDistance $eventDistance
     */
    public function __construct(Event $event, EventDistance $eventDistance)
    {
        $this->eventModel = $event;
        $this->eventDistanceModel = $eventDistance;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $sort
     *
     * @return void
     */
    public function getEvents(array $filters, string $orderBy = 'event_date', string $sort = 'ASC')
    {
        $query = $this->eventModel->newModelQuery();

        $query->where('event_date', '>=', Carbon::now());

        if (is_array($filters) && count($filters) > 0) {
            if (!empty($filters['startDay']) && !empty($filters['endDay'])) {
                $query->where('event_date', '>=', $filters['startDay'])
                ->where('event_date', '<=', $filters['endDay']);
            }
            if (!empty($filters['keywords'])) {
                $query->where(function ($query) use ($filters) {
                    $query->where('event_name', 'like', '%' . $filters['keywords'] . '%')
                    ->orWhere('location', 'like', '%' . $filters['keywords'] . '%');
                });
            }
            if (!empty($filters['ids']) && is_array($filters['ids'])) {
                $query->whereIn('id', $filters['ids']);
            }
        }

        $query->orderBy($orderBy, $sort);

        $results = $query->paginate($filters['rows'] ?? 30);

        $results->appends($filters);

        $results->getCollection()->transform(function ($event) {
            $event['distance'] = $event->distance;

            return $event;
        });

        return $results;
    }

    /**
     * @param string $eventName
     *
     * @return Event|null
     */
    public function getEventByEventName(string $eventName): ?Event
    {
        return $this->eventModel->where('event_name', $eventName)->first();
    }

    /**
     *
     * @return array
     */
    public function getIndexEvents(): array
    {
        try {
            $rows = $this->eventModel
                ->where('event_status', 1)
                ->where('event_date', '>=', Carbon::now())
                ->orderBy('event_date', 'ASC')->limit(5)
                ->get()
                ->toarray();

            return $rows ?? [];
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param array $eventInput
     * @param array $distanceInput
     *
     * @return void
     */
    public function updateEvent(array $eventInput, array $distanceInput): void
    {
        $this->eventModel->where('id', $eventInput['id'])->update($eventInput);

        $this->eventDistanceModel->where('event_id', $eventInput['id'])->delete();

        $this->createEventDistance($distanceInput);
    }

    /**
     * @param array $input
     * @param array $distanceInput
     *
     * @return void
     */
    public function createEvent(array $eventInput, array $distanceInput): void
    {
        $this->eventModel->create($eventInput);
        $this->createEventDistance($distanceInput);
    }

    /**
     * @param array $distanceInput
     *
     * @return void
     */
    public function createEventDistance(array $distanceInput): void
    {
        foreach ($distanceInput as $distance) {
            $this->eventDistanceModel->create($distance);
        }
    }
}
