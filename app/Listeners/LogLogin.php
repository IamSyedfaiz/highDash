<?php

namespace App\Listeners;

use App\Models\LoginSession;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class LogLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $now = now('Asia/Kolkata');

        // 1. Create Login Session
        $session = LoginSession::create([
            'user_id' => $user->id,
            'login_at' => $now,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 2. Mark Attendance (only if not already marked for today IST)
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $now->toDateString()],
            ['login_at' => $now, 'status' => 'present']
        );

        // 3. Log Activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged into the system at ' . $now->toDateTimeString(),
            'model_type' => LoginSession::class,
            'model_id' => $session->id,
            'properties' => ['ip' => request()->ip(), 'ua' => request()->userAgent()]
        ]);
    }
}
