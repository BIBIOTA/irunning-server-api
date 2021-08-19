<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T extends Model
{
    use HasFactory;

    protected $table = 'T';

    protected $fillable = ['weather_id'];

    public $incrementing = false;

    protected $primaryKey = 'weather_id';

}
