<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;

    protected $table = 'telegram_subscribe_users';

    protected $primaryKey = 'telegram_id';

    protected $guarded = [];

    public $incrementing = false;
}
