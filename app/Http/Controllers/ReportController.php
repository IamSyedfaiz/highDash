<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $query = Attendance::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->from_date) {
            $query->where('date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }

        $attendances = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('attendances', 'users'));
    }

    public function export(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->from_date) {
            $query->where('date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->where('date', '<=', $request->to_date);
        }

        $attendances = $query->get();

        $filename = "attendance_report_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['User', 'Date', 'Clock In', 'Clock Out', 'Duration (Min)', 'Status']);

        foreach ($attendances as $row) {
            fputcsv($handle, [
                $row->user->name,
                $row->date->format('Y-m-d'),
                $row->login_at?->format('H:i:s') ?? '-',
                $row->logout_at?->format('H:i:s') ?? '-',
                $row->work_duration_minutes ?? 0,
                $row->status
            ]);
        }

        fclose($handle);
        exit;
    }
}
