<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $guarded = [];

    public $incrementing = false;

    public function getFilterData($filters, $orderBy='event_date', $order='ASC') {
        $query = $this->newModelQuery();

        if (is_array($filters) && count($filters) > 0) {
            if (!empty($filters['startDay']) && !empty($filters['endDay'])) {
                $query->where('event_date', '>=', $filters['startDay'])
                ->where('event_date', '<=', $filters['endDay']);
            }
            if (!empty($filters['keywords'])) {
                $query->where(function ($query) use($filters) {
                    $query->where('event_name', 'like', '%'. $filters['keywords'].'%')
                    ->orWhere('location', 'like', '%'. $filters['keywords'].'%');
                });
            }
            if (!empty($filters['ids']) && is_array($filters['ids'])) {
                $query->whereIn('id', $filters['ids']);
            }
        }

        $query->orderBy($orderBy, $order);

        $results = $query->paginate($filters['rows']??30);
        $results->appends($filters);

        return $results;
    }

    public function distance() {
        return $this->hasMany(EventDistance::class, 'event_id');
    }
}
