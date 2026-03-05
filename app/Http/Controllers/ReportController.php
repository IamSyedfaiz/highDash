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
        try {
            $users = User::all();
            $query = Attendance::with(['user', 'loginSessions']);

            if ($request->user_id) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->from_date) {
                $query->where('date', '>=', $request->from_date);
            }

            if ($request->to_date) {
                $query->where('date', '<=', $request->to_date);
            }

            $attendances = $query->latest()->paginate(20)->withQueryString();

            return view('admin.reports.index', compact('attendances', 'users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load report data.');
        }
    }

    public function export(Request $request)
    {
        try {
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

            return response()->streamDownload(function () use ($attendances) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['User', 'Date', 'Clock In', 'Clock Out', 'Duration (Min)', 'Status']);

                foreach ($attendances as $row) {
                    fputcsv($handle, [
                        $row->user->name,
                        is_string($row->date) ? $row->date : $row->date->format('Y-m-d'),
                        $row->login_at?->setTimezone('Asia/Kolkata')->format('H:i:s') ?? '-',
                        $row->logout_at?->setTimezone('Asia/Kolkata')->format('H:i:s') ?? '-',
                        $row->work_duration_minutes ?? 0,
                        $row->status
                    ]);
                }
                fclose($handle);
            }, $filename, ['Content-Type' => 'text/csv']);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export report.');
        }
    }
}
