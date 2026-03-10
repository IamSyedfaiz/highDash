<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    protected $userId;
    protected $month;
    protected $year;

    public function __construct($userId, $month, $year)
    {
        $this->userId = $userId;
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Attendance::where('user_id', $this->userId)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'asc');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Login Time',
            'Logout Time',
            'Work Minutes',
            'Hours',
            'Status',
        ];
    }

    public function map($attendance): array
    {
        $loginAt = $attendance->login_at ? \Carbon\Carbon::parse($attendance->login_at)->setTimezone('Asia/Kolkata') : null;
        $logoutAt = $attendance->logout_at ? \Carbon\Carbon::parse($attendance->logout_at)->setTimezone('Asia/Kolkata') : null;

        $totalMinutes = $attendance->work_duration_minutes ?? 0;

        // If DB minutes is 0, recalculate from sessions (just to be safe)
        if ($totalMinutes == 0) {
            $totalSeconds = 0;
            $dayStart = $attendance->date->copy()->startOfDay();
            $dayEnd = $attendance->date->copy()->endOfDay();

            $sessions = $attendance->loginSessions;
            foreach ($sessions as $session) {
                if (!$session->login_at)
                    continue;
                $sStart = $session->login_at->copy()->setTimezone('Asia/Kolkata');
                $sEnd = ($session->logout_at ?? now())->copy()->setTimezone('Asia/Kolkata');

                $start = $sStart->gt($dayStart) ? $sStart : $dayStart;
                $end = $sEnd->lt($dayEnd) ? $sEnd : $dayEnd;

                if ($end->gt($start)) {
                    $totalSeconds += (int) $end->diffInSeconds($start);
                }
            }
            $totalMinutes = floor($totalSeconds / 60);
        }

        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;
        $status = ($totalMinutes >= 420) ? 'Full Day' : 'Half Day';

        return [
            $attendance->date->format('Y-m-d'),
            $loginAt ? $loginAt->format('h:i:s A') : '-',
            $logoutAt ? $logoutAt->format('h:i:s A') : '-',
            $totalMinutes,
            sprintf('%02dh %02dm', $h, $m),
            $status,
        ];
    }
}
