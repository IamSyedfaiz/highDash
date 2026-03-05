<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'login_at',
        'logout_at',
        'work_duration_minutes',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loginSessions()
    {
        return $this->hasMany(LoginSession::class, 'user_id', 'user_id')
            ->whereDate('login_at', $this->date);
    }
}
