<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AT extends Model
{
    use HasFactory;

    protected $table = 'AT';

    protected $fillable = ['weather_id'];
}

