<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDistance extends Model
{
    use HasFactory;

    protected $table = 'events_distances';

    protected $guarded = [];
}
