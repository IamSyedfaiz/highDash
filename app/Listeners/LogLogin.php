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
        /** @var \App\Models\User $user */
        $user = $event->user;
        $now = now('Asia/Kolkata');

        // 1. Close any existing open sessions for this user to prevent ghost sessions
        $oldSessions = LoginSession::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->get();

        foreach ($oldSessions as $oldSess) {
            // Use original login date for searching attendance
            $oldDay = $oldSess->login_at->copy()->setTimezone('Asia/Kolkata')->toDateString();
            $lastActivityAttendance = Attendance::where('user_id', $user->id)->where('date', $oldDay)->first();

            // Best guess: If they refreshed the page at 5pm, the attendance 'logout_at' stores that 5pm time.
            $closingTime = ($lastActivityAttendance && $lastActivityAttendance->logout_at) ? $lastActivityAttendance->logout_at : $oldSess->login_at->copy()->addMinutes(1);

            $oldSess->update([
                'logout_at' => $closingTime,
                'duration_minutes' => max(0, (int) $closingTime->diffInMinutes($oldSess->login_at))
            ]);
        }

        // 2. Create New Login Session
        $session = LoginSession::create([
            'user_id' => $user->id,
            'login_at' => $now,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 3. Mark/Ensure Attendance record for today
        // firstOrCreate only sets values if it CREATES. We'll use more robust logic in the middleware,
        // but it's good to have this as a backup.
        Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $now->toDateString()],
            ['login_at' => $now, 'status' => 'present']
        );

        // 4. Log Activity
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
