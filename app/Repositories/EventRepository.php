<?php
namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;
use Throwable;

class EventRepository
{
    private $model;

    public function __construct(Event $event)
    {
        $this->model = $event;
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
        $query = $this->model->newModelQuery();

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

    public function getIndexEvents(): array
    {
        try {
            $rows = $this->model
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
}
