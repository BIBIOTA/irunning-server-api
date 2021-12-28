<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherDetail extends Model
{
    use HasFactory;

    protected $table = 'weather_details';

    protected $fillable = ['id'];

    public $incrementing = false;

}
