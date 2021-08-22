<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDistance extends Model
{
    use HasFactory;

    protected $table = 'events_distances';

    protected $guarded = [];

    public function distanceFilter($distances, $filters) {
        foreach($distances as $distance) {
            foreach($filters as $filter) {
                if ($filter == 1) {
                    if (str_contains($distance, '42K')) {
                        return $distances;
                    }
                    if (str_contains($distance, '42.195K')) {
                        return $distances;
                    }
                }
                if ($filter == 2) {
                    if (str_contains($distance, '21K')) {
                        return $distances;
                    }
                    if (str_contains($distance, '21.0975K')) {
                        return $distances;
                    }
                }
                if ($filter == 3) {
                    if (substr_count($distance, '+') === 2) {
                        return $distances;
                    }
                }
            }
        }
        return null;
    }
}
