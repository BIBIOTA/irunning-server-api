<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $guarded = [];

    public $incrementing = false;

    public function memberToken () {
        return $this->belongsTo(MemberToken::class, 'id','user_id');
    }
}
