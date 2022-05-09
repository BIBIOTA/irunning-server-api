<?php
namespace App\Repositories;

use App\Models\Activity;
use App\Http\Controllers\Traits\Running;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityRepository
{
    use Running;

    private Activity $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    public function checkHasMemberActivities(string $memberId): bool
    {
        return $this->model->where('member_id', $memberId)->exists();
    }

    public function getFilterData(
        array $filters,
        string $orderBy = 'start_date_local',
        string $order = 'DESC'
    ): LengthAwarePaginator {
        $query = $this->model->newModelQuery();

        $query->where('member_id', $filters['id']);

        if (!empty($filters['startDay']) && !empty($filters['endDay'])) {
            $query->where('start_date_local', '>=', $filters['startDay'] . ' 00:00:00')
            ->where('start_date_local', '<=', $filters['endDay'] . ' 23:59:59');
        }

        $query->orderBy($orderBy, $order);

        $results = $query->paginate($filters['rows'] ?? 10);
        $results->appends($filters);

        $results->getCollection()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
                'pace' => $this->getPace($row->distance, $row->moving_time),
                'distance' => $this->getDistanceIsFloor($row->distance),
                'moving_time' => $row->moving_time,
                'start_date_local' => $row->start_date_local,
                'summary_polyline' => $row->summary_polyline,
            ];
        });

        return $results;
    }
}
