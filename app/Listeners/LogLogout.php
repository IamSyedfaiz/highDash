<?php

namespace App\Listeners;

use App\Models\LoginSession;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;
        if (!$user)
            return;

        $now = now('Asia/Kolkata');

        // 1. Update last Login Session
        $session = LoginSession::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->orderBy('login_at', 'desc')
            ->first();

        if ($session) {
            $session->update([
                'logout_at' => $now,
                'duration_minutes' => $now->diffInMinutes($session->login_at),
            ]);
        }

        // 2. Update Attendance for today IST
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $now->toDateString())
            ->first();

        if ($attendance) {
            $attendance->update([
                'logout_at' => $now,
                'work_duration_minutes' => $now->diffInMinutes($attendance->login_at),
            ]);
        }

        // 3. Log Activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'description' => 'User logged out at ' . $now->toDateTimeString(),
            'model_type' => LoginSession::class,
            'model_id' => $session ? $session->id : null,
        ]);
    }
}
