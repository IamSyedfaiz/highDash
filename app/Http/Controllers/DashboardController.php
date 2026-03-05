<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\ActivityLog;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->hasRole('manager')) {
            return $this->adminDashboard();
        }
        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $today = now('Asia/Kolkata')->toDateString();
        $data = [
            'totalUsers' => User::count(),
            'todayAttendance' => Attendance::where('date', $today)->count(),
            'pendingLeaves' => LeaveRequest::where('status', 'pending')->count(),
            'totalLeads' => Lead::count(),
            'unassignedLeads' => Lead::whereNull('assigned_to')->count(),
            'droppedLeads' => Lead::where('status', 'Drop')->count(),
            'recentActivities' => ActivityLog::with('user')->latest()->take(10)->get(),
            'leadStats' => Lead::selectRaw('status, count(*) as count')->groupBy('status')->get(),
            'topAgents' => User::whereHas('roles', fn($q) => $q->where('slug', 'calling'))
                ->withCount(['leads' => fn($q) => $q->where('status', 'Existing')])
                ->orderBy('leads_count', 'desc')->take(5)->get(),
        ];
        return view('admin.dashboard', $data);
    }

    private function userDashboard()
    {
        $user = Auth::user();
        $now = now('Asia/Kolkata');

        $data = [
            'attendance' => Attendance::where('user_id', $user->id)
                ->where('date', '>=', $now->startOfMonth()->toDateString())
                ->get(),
            'leaveRequests' => LeaveRequest::where('user_id', $user->id)->latest()->take(5)->get(),
            'assignedLeadsCount' => $user->leads()->count(),
            'newLeadsCount' => $user->leads()->where('status', 'New Lead')->count(),
            'convertedLeadsCount' => $user->leads()->where('status', 'Existing')->count(),
            'stats' => [
                'present' => Attendance::where('user_id', $user->id)->where('status', 'present')->count(),
                'absent' => Attendance::where('user_id', $user->id)->where('status', 'absent')->count(),
                'leave' => Attendance::where('user_id', $user->id)->where('status', 'on_leave')->count(),
            ]
        ];
        return view('user.dashboard', $data);
    }
}
