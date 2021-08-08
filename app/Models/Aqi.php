<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aqi extends Model
{
    use HasFactory;

    protected $table = 'aqi';

    protected $fillable = ['weather_id'];
}
