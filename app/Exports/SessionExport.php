<?php

namespace App\Exports;

use App\Models\LoginSession;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SessionExport implements FromQuery, WithHeadings, WithMapping
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
        return LoginSession::where('user_id', $this->userId)
            ->whereMonth('login_at', $this->month)
            ->whereYear('login_at', $this->year)
            ->orderBy('login_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Login Time',
            'Logout Time',
            'Duration (Minutes)',
            'Duration (HH:MM)',
            'IP Address',
            'Browser Info',
        ];
    }

    public function map($session): array
    {
        $duration = $session->duration_minutes ?? 0;

        // If it's the current session, the duration in DB might be 0, we could calculate it
        if (!$session->logout_at) {
            $duration = now()->diffInMinutes($session->login_at);
        }

        $h = floor($duration / 60);
        $m = $duration % 60;

        return [
            $session->login_at->copy()->setTimezone('Asia/Kolkata')->format('Y-m-d'),
            $session->login_at->copy()->setTimezone('Asia/Kolkata')->format('h:i:s A'),
            $session->logout_at ? $session->logout_at->copy()->setTimezone('Asia/Kolkata')->format('h:i:s A') : 'STILL ACTIVE',
            $duration,
            $h . 'h ' . $m . 'm',
            $session->ip_address,
            $session->user_agent,
        ];
    }
}
