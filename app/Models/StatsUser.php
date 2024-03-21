<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatsUser extends Model{
    protected $table = 'stats_users';

    protected $fillable = [
        'haircuts', 'points', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(CustomUser::class, 'user_id');
    }
}
