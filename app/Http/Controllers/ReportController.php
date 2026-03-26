<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Models\Task;
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

    public function userPerformance(User $user, Request $request)
    {
        try {
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            $leads = Lead::where('assigned_to', $user->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();

            $tasks = Task::where('user_id', $user->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();

            $attendances = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();

            return view('admin.reports.user_performance', compact('user', 'leads', 'tasks', 'attendances', 'month', 'year'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load user performance.');
        }
    }

    public function export(Request $request)
    {
        try {
            $from = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
            $to = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now();

            $usersQuery = User::query();
            if ($request->user_id) {
                $usersQuery->where('id', $request->user_id);
            }
            $users = $usersQuery->get();

            // Build Dates Range
            $dates = [];
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                $dates[$d->format('Y-m-d')] = $d->copy();
            }

            // Fetch Holidays
            $holidays = \App\Models\Holiday::whereBetween('date', [$from->format('Y-m-d'), $to->format('Y-m-d')])->get();

            // Fetch Attendances
            $attRecords = Attendance::whereIn('user_id', $users->pluck('id'))
                ->whereBetween('date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                ->get();

            $attendances = [];
            foreach ($attRecords as $a) {
                $dateKey = $a->date instanceof Carbon ? $a->date->format('Y-m-d') : Carbon::parse($a->date)->format('Y-m-d');
                $attendances[$a->user_id][$dateKey] = $a;
            }

            // Fetch Leaves
            $leaveRecords = \App\Models\LeaveRequest::whereIn('user_id', $users->pluck('id'))
                ->where('status', 'approved')
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('from_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                        ->orWhereBetween('to_date', [$from->format('Y-m-d'), $to->format('Y-m-d')]);
                })->get();

            $leaves = [];
            foreach ($leaveRecords as $lr) {
                $start = Carbon::parse($lr->from_date);
                $end = Carbon::parse($lr->to_date);
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    $leaves[$lr->user_id][$d->format('Y-m-d')] = $lr;
                }
            }

            $data = compact('users', 'dates', 'holidays', 'attendances', 'leaves');
            $filenamePrefix = "attendance_matrix_report_" . date('Y-m-d');

            if ($request->format === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.attendance_matrix', $data)->setPaper('a4', 'landscape');
                return $pdf->download($filenamePrefix . ".pdf");
            }

            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceMatrixExport($data), $filenamePrefix . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Export failed.');
        }
    }

    public function exportLeads(Request $request)
    {
        try {
            $query = Lead::with('assignedUser');
            if ($request->user_id)
                $query->where('assigned_to', $request->user_id);
            if ($request->status)
                $query->where('status', $request->status);

            $leads = $query->get();
            $filename = "leads_report_" . date('Y-m-d') . ".csv";

            return response()->streamDownload(function () use ($leads) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Lead ID', 'Company', 'Contact', 'Phone', 'Status', 'Assigned To', 'Created At']);
                foreach ($leads as $l) {
                    fputcsv($handle, [
                        $l->id,
                        $l->company_name,
                        $l->contact_name,
                        $l->phone,
                        $l->status,
                        $l->assignedUser->name ?? 'Unassigned',
                        $l->created_at->format('Y-m-d H:i')
                    ]);
                }
                fclose($handle);
            }, $filename, ['Content-Type' => 'text/csv']);
        } catch (\Exception $e) {
            return back()->with('error', 'Leads export failed.');
        }
    }

    public function exportTasks(Request $request)
    {
        try {
            $query = Task::with(['user', 'creator']);
            if ($request->user_id)
                $query->where('user_id', $request->user_id);
            if ($request->status)
                $query->where('status', $request->status);

            $tasks = $query->get();
            $filename = "tasks_performance_" . date('Y-m-d') . ".csv";

            return response()->streamDownload(function () use ($tasks) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Task ID', 'Employee', 'Title', 'Status', 'URL', 'Started At', 'Closed At', 'Progress History']);
                foreach ($tasks as $t) {
                    $logs = ActivityLog::where('model_type', Task::class)
                        ->where('model_id', $t->id)
                        ->latest()
                        ->pluck('description')
                        ->map(fn($d) => str_replace('"', "'", $d))
                        ->implode(' || ');

                    fputcsv($handle, [
                        $t->id,
                        $t->user->name,
                        $t->title,
                        strtoupper($t->status),
                        $t->url ?? '-',
                        $t->started_at?->format('Y-m-d H:i') ?? '-',
                        $t->completed_at?->format('Y-m-d H:i') ?? '-',
                        $logs
                    ]);
                }
                fclose($handle);
            }, $filename, ['Content-Type' => 'text/csv']);
        } catch (\Exception $e) {
            return back()->with('error', 'Tasks export failed.');
        }
    }

    private function calculateMinutes($row)
    {
        $minutes = $row->work_duration_minutes ?? 0;
        if ($minutes == 0) {
            $totalSeconds = 0;
            $dayStart = Carbon::parse($row->date)->startOfDay();
            $dayEnd = Carbon::parse($row->date)->endOfDay();
            foreach ($row->loginSessions as $s) {
                $sStart = $s->login_at->copy()->setTimezone('Asia/Kolkata');
                $sEnd = ($s->logout_at ?? now())->copy()->setTimezone('Asia/Kolkata');
                $st = $sStart->gt($dayStart) ? $sStart : $dayStart;
                $ed = $sEnd->lt($dayEnd) ? $sEnd : $dayEnd;
                if ($ed->gt($st))
                    $totalSeconds += $ed->diffInSeconds($st);
            }
            $minutes = floor($totalSeconds / 60);
        }
        return $minutes;
    }
}
