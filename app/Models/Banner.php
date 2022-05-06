<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    public function scopeVerified(Builder $query): Builder
    {
        return $query;
    }

    protected $guarded = [];
}
