<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $guarded = [];

    public $incrementing = false;

    public function index($filters, $orderBy = 'created_at', $order = 'DESC')
    {
        $query = $this->newModelQuery();

        if (is_array($filters) && count($filters) > 0) {
            if (isset($filters['username'])) {
                $query->where('username', 'like', '%' . $filters['username'] . '%');
            }
        }

        $query->orderBy($orderBy, $order);

        $results = $query->paginate($filters['rows'] ?? 10);
        $results->appends($filters);

        return $results;
    }

    public function stat()
    {
        return $this->belongsTo(Stat::class, 'id', 'member_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'id', 'member_id');
    }

    public function memberToken()
    {
        return $this->belongsTo(MemberToken::class, 'id', 'member_id');
    }
}
