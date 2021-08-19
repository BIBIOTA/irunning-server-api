<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wx extends Model
{
    use HasFactory;

    protected $table = 'Wx';

    protected $fillable = ['weather_id'];

    public $incrementing = false;

    protected $primaryKey = 'weather_id';

    public function WxDocument () {
        return $this->belongsTo(WxDocument::class, 'value', 'text');
    }
}
