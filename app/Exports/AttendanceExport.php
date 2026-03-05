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
        $hours = floor($attendance->work_duration_minutes / 60);
        $minutes = $attendance->work_duration_minutes % 60;

        return [
            $attendance->date,
            $attendance->login_at ? $attendance->login_at->format('H:i:s') : '-',
            $attendance->logout_at ? $attendance->logout_at->format('H:i:s') : '-',
            $attendance->work_duration_minutes,
            $hours . 'h ' . $minutes . 'm',
            ucfirst($attendance->status),
        ];
    }
}
