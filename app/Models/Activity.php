<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $guarded = [];

    public function getFilterData($filters, $orderBy='start_date_local', $order='DESC') {
        $query = $this->newModelQuery();

        if (is_array($filters) && count($filters) > 0) {
            $query->where('user_id', $filters['id']);
            if (!empty($filters['startDay']) && !empty($filters['endDay'])) {
                $query->where('start_date_local', '>=', $filters['startDay'].' 00:00:00')
                ->where('start_date_local', '<=', $filters['endDay'].' 23:59:59');
            }
        }

        $query->orderBy($orderBy, $order);

        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }
}
