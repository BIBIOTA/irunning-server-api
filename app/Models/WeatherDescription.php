<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherDescription extends Model
{
    use HasFactory;

    protected $table = 'WeatherDescription';

    protected $fillable = ['weather_id'];


}
