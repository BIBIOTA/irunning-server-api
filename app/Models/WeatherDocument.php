<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherDocument extends Model
{
    use HasFactory;

    protected $table = 'weather_documents';

    protected $fillable = ['id'];

    public $incrementing = false;
}
