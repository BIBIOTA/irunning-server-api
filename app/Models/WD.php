<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WD extends Model
{
    use HasFactory;

    protected $table = 'WD';

    protected $fillable = ['weather_id'];


}
