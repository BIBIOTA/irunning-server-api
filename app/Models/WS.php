<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WS extends Model
{
    use HasFactory;

    protected $table = 'WS';

    protected $fillable = ['weather_id'];

    public $incrementing = false;

    protected $primaryKey = 'weather_id';

}
