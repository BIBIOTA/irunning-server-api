<?php
namespace App\Repositories;

use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityRepository
{
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

        return $results;
    }

    /**
     * @param array $formData
     *
     * @return void
     */
    public function updateOrCreateActivities(array $formData): void
    {
        $this->model->updateOrCreate([
            'id' => $formData['id']
        ], $formData);
    }
}
