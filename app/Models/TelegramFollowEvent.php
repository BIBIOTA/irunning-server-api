<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramFollowEvent extends Model
{
    use HasFactory;

    protected $table = 'telegram_follow_event';

    protected $guarded = [];

    public $incrementing = false;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
