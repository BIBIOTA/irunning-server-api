<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RH extends Model
{
    use HasFactory;

    protected $table = 'RH';

    protected $fillable = ['weather_id'];

}
