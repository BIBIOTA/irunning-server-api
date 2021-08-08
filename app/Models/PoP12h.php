<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoP12h extends Model
{
    use HasFactory;

    protected $table = 'PoP12h';

    protected $fillable = ['weather_id'];

}
