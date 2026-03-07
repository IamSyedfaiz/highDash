<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $now = now('Asia/Kolkata');
            $month = $request->month ?? $now->month;
            $year = $request->year ?? $now->year;

            $today = Attendance::where('user_id', $user->id)->where('date', $now->toDateString())->first();

            $attendances = Attendance::with('loginSessions')->where('user_id', $user->id)->whereMonth('date', $month)->whereYear('date', $year)->orderBy('date', 'desc')->get();

            $leaves = LeaveRequest::where('user_id', $user->id)->whereMonth('from_date', $month)->latest()->get();

            // Statistics
            $totalMinutes = $attendances->sum('work_duration_minutes');
            $fullDays = $attendances->where('work_duration_minutes', '>=', 480)->count(); // 8 hours
            $halfDays = $attendances->where('work_duration_minutes', '>=', 240)->where('work_duration_minutes', '<', 480)->count(); // 4-8 hours

            return view('attendance.index', compact('today', 'attendances', 'leaves', 'month', 'year', 'totalMinutes', 'fullDays', 'halfDays'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong while loading attendance data.');
        }
    }

    public function export(Request $request)
    {
        try {
            $user = Auth::user();
            $targetMonth = $request->type === 'last' ? now()->subMonth() : now();
            $month = $targetMonth->month;
            $year = $targetMonth->year;

            if ($request->mode === 'detailed') {
                return Excel::download(new \App\Exports\SessionExport($user->id, $month, $year), 'detailed_logs_' . $targetMonth->format('M_Y') . '.xlsx');
            }

            return Excel::download(new AttendanceExport($user->id, $month, $year), 'attendance_' . $targetMonth->format('M_Y') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export attendance data.');
        }
    }
}
