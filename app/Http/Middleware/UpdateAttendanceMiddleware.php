<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LoginSession;
use Carbon\Carbon;

class UpdateAttendanceMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $now = now('Asia/Kolkata');
            $date = $now->toDateString();

            // 1. Ensure Attendance record exists for today
            $attendance = Attendance::firstOrNew(['user_id' => $user->id, 'date' => $date]);

            if (!$attendance->exists) {
                $attendance->login_at = $now;
                $attendance->status = 'present';
            }

            // Ensure first login is captured
            if (!$attendance->login_at) {
                $attendance->login_at = $now;
            }

            // 2. Calculate accurate work duration minutes for ONLY today
            $dayStart = $now->copy()->startOfDay();
            $dayEnd = $now->copy()->endOfDay();

            // Get all sessions that touch today
            $sessions = LoginSession::where('user_id', $user->id)
                ->where(function ($query) use ($date) {
                    $query->whereDate('login_at', $date)
                        ->orWhereDate('logout_at', $date)
                        ->orWhereNull('logout_at');
                })->get();

            $totalSeconds = 0;
            foreach ($sessions as $session) {
                if (!$session->login_at)
                    continue;

                $sessLogin = $session->login_at->copy()->setTimezone('Asia/Kolkata');
                $sessLogout = ($session->logout_at ?? $now)->copy()->setTimezone('Asia/Kolkata');

                // Intersection of session with [dayStart, dayEnd]
                $start = $sessLogin->gt($dayStart) ? $sessLogin : $dayStart;
                $end = $sessLogout->lt($dayEnd) ? $sessLogout : $dayEnd;

                if ($end->gt($start)) {
                    $totalSeconds += (int) $end->diffInSeconds($start);
                }
            }

            // Guard against negative or weird values
            $finalMinutes = max(0, (int) floor($totalSeconds / 60));

            $attendance->update([
                'logout_at' => $now, // Acts as "Last seen"
                'work_duration_minutes' => $finalMinutes
            ]);
        }

        return $next($request);
    }
}
