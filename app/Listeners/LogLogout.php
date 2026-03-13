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
        $date = $now->toDateString();

        // 1. Update last open Login Session
        $session = LoginSession::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->orderBy('login_at', 'desc')
            ->first();

        if ($session) {
            $diff = (int) $now->diffInMinutes($session->login_at);
            $session->update([
                'logout_at' => $now,
                'duration_minutes' => max(0, $diff),
            ]);
        }

        // 2. Update Attendance for today with accurate duration
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            $dayStart = $now->copy()->startOfDay();
            $dayEnd = $now->copy()->endOfDay();

            $sessions = LoginSession::where('user_id', $user->id)
                ->where(function ($query) use ($date) {
                    $query->whereDate('login_at', $date)
                        ->orWhereDate('logout_at', $date)
                        ->orWhereNull('logout_at');
                })->get();

            $totalSeconds = 0;
            foreach ($sessions as $s) {
                if (!$s->login_at)
                    continue;

                $sessLogin = $s->login_at->copy()->setTimezone('Asia/Kolkata');
                $sessLogout = ($s->logout_at ?? $now)->copy()->setTimezone('Asia/Kolkata');

                $start = $sessLogin->gt($dayStart) ? $sessLogin : $dayStart;
                $end = $sessLogout->lt($dayEnd) ? $sessLogout : $dayEnd;

                if ($end->gt($start)) {
                    $totalSeconds += abs((int) $end->diffInSeconds($start));
                }
            }

            $attendance->update([
                'logout_at' => $now,
                'work_duration_minutes' => max(0, (int) floor($totalSeconds / 60))
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
