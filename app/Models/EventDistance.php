<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDistance extends Model
{
    use HasFactory;

    protected $table = 'events_distances';

    protected $guarded = [];

    public function distanceFilter($distance, $filters) {
        foreach($filters as $filter) {
            if ($filter == 1) {
                if (str_contains($distance, '42K')) {
                    return $distance;
                }
                if (str_contains($distance, '42.195K')) {
                    return $distance;
                }
            }
            if ($filter == 2) {
                if (str_contains($distance, '21K')) {
                    return $distance;
                }
                if (str_contains($distance, '21.0975K')) {
                    return $distance;
                }
            }
            if ($filter == 3) {
                if (substr_count($distance, '+') === 2) {
                    return $distance;
                }
            }
        }
        return null;
    }
}
