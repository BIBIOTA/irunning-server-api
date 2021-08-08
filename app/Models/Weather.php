<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';

    protected $fillable = ['id'];

    public $incrementing = false;

    public function T () {
        return $this->hasOne(T::class, 'weather_id');
    }

    public function PoP6h () {
        return $this->hasOne(PoP6h::class, 'weather_id');
    }

    public function CI () {
        return $this->hasOne(CI::class, 'weather_id');
    }

    public function AT () {
        return $this->hasOne(AT::class, 'weather_id');
    }

    public function Wx () {
        return $this->hasOne(Wx::class, 'weather_id');
    }
}
