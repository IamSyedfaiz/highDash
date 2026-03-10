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

            // Safer query: Get all sessions that OVERLAP with today (as per IST)
            $sessions = LoginSession::where('user_id', $user->id)
                ->where('login_at', '<', $dayEnd)
                ->where(function ($query) use ($dayStart) {
                    $query->whereNull('logout_at')
                        ->orWhere('logout_at', '>', $dayStart);
                })->get();

            $totalSeconds = 0;
            foreach ($sessions as $session) {
                if (!$session->login_at)
                    continue;

                // Important: Ensure we are comparing in the same timezone (IST)
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

            // 3. Update Global Attendance Record
            // Logic: 7 hours (420 mins) = Full Day (present), otherwise Half Day (half-day)
            $status = ($finalMinutes >= 420) ? 'present' : 'half-day';

            $attendance->fill([
                'logout_at' => $now, // Acts as "Last seen" for the day
                'work_duration_minutes' => $finalMinutes,
                'status' => $status
            ]);
            $attendance->save();
        }

        return $next($request);
    }
}
