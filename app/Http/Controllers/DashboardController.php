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
        try {
            $user = Auth::user();
            if ($user->isAdmin() || $user->hasRole('manager')) {
                return $this->adminDashboard();
            }
            return $this->userDashboard();
        } catch (\Exception $e) {
            // If something fails, at least show a clean error view or redirect
            return view('errors.500'); // Or custom error handling
        }
    }

    private function adminDashboard()
    {
        try {
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
                'topAgents' => User::whereHas('roles', fn($q) => $q->whereIn('slug', ['sales', 'inside_sales', 'field_sales']))
                    ->withCount(['leads' => fn($q) => $q->where('status', 'Existing')])
                    ->orderBy('leads_count', 'desc')->take(5)->get(),
            ];
            return view('admin.dashboard', $data);
        } catch (\Exception $e) {
            return view('admin.dashboard', ['error' => 'Some dashboard components failed to load.']);
        }
    }

    private function userDashboard()
    {
        try {
            $user = Auth::user()->load('currentSession');
            $now = now('Asia/Kolkata');

            $data = [
                'user' => $user,
                'attendance' => Attendance::where('user_id', $user->id)
                    ->where('date', '>=', $now->startOfMonth()->toDateString())
                    ->get(),
                'leaveRequests' => $user->leaveRequests()->latest()->take(5)->get(),
                'assignedLeadsCount' => $user->leads()->count(),
                'newLeadsCount' => $user->leads()->where('status', 'New Lead')->count(),
                'convertedLeadsCount' => $user->leads()->where('status', 'Existing')->count(),
                'todayFollowUps' => $user->followUps()->whereDate('next_follow_up_date', $now->toDateString())->with('lead')->get(),
                'taskStats' => [
                    'pending' => $user->tasks()->where('status', 'pending')->count(),
                    'started' => $user->tasks()->where('status', 'started')->count(),
                    'closed' => $user->tasks()->where('status', 'closed')->count(),
                    'total' => $user->tasks()->count()
                ],
                'stats' => [
                    'present' => Attendance::where('user_id', $user->id)->where('status', 'present')->count(),
                    'absent' => Attendance::where('user_id', $user->id)->where('status', 'absent')->count(),
                    'leave' => Attendance::where('user_id', $user->id)->where('status', 'on_leave')->count(),
                ]
            ];
            return view('user.dashboard', $data);
        } catch (\Exception $e) {
            return view('user.dashboard', ['error' => 'Unable to load your dashboard data.']);
        }
    }
}
