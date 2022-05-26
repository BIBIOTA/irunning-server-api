<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $guarded = [];

    public $incrementing = false;

    public function distance()
    {
        return $this->hasMany(EventDistance::class, 'event_id');
    }

    public function telegramFollowEvent()
    {
        return $this->hasMany(TelegramFollowEvent::class);
    }
}
