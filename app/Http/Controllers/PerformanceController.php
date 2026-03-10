<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use DB;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin() || $user->hasRole('manager');

        $targetUserId = $request->user_id ?? ($isAdmin ? null : $user->id);
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // Calling Team Data (Leads)
        $callingStats = [];
        if ($isAdmin || $user->hasRole('calling')) {
            $callingQuery = User::whereHas('roles', function ($q) {
                $q->where('slug', 'calling');
            });

            if (!$isAdmin) {
                $callingQuery->where('id', $user->id);
            }

            $callingUsers = $callingQuery->withCount([
                'leads' => function ($q) use ($month, $year) {
                    $q->whereMonth('created_at', $month)->whereYear('created_at', $year);
                }
            ])->get();

            foreach ($callingUsers as $cu) {
                $cuLeads = Lead::where('assigned_to', $cu->id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->select('status', 'prospect_status', DB::raw('count(*) as count'))
                    ->groupBy('status', 'prospect_status')
                    ->get();

                $cuStats = [
                    'user' => $cu,
                    'total' => $cu->leads_count,
                    'by_status' => $cuLeads->groupBy('status')->map->sum('count'),
                    'by_prospect' => $cuLeads->groupBy('prospect_status')->map->sum('count'),
                ];
                $callingStats[] = $cuStats;
            }
        }

        // Technical Team Data (Tasks)
        $techStats = [];
        if ($isAdmin || $user->hasRole('technical')) {
            $techQuery = User::whereHas('roles', function ($q) {
                $q->where('slug', 'technical');
            });

            if (!$isAdmin) {
                $techQuery->where('id', $user->id);
            }

            $techUsers = $techQuery->get();

            foreach ($techUsers as $tu) {
                $tuTasks = Task::where('user_id', $tu->id)
                    ->whereMonth('task_date', $month)
                    ->whereYear('task_date', $year)
                    ->select('status', DB::raw('count(*) as count'), 'task_date')
                    ->groupBy('status', 'task_date')
                    ->get();

                $tuStats = [
                    'user' => $tu,
                    'total' => $tuTasks->sum('count'),
                    'by_status' => $tuTasks->groupBy('status')->map->sum('count'),
                    'daily' => $tuTasks->groupBy(function ($item) {
                        return $item->task_date->format('Y-m-d');
                    })->map->sum('count')
                ];
                $techStats[] = $tuStats;
            }
        }

        $allUsers = $isAdmin ? User::all() : collect([$user]);

        return view('performance.index', compact('callingStats', 'techStats', 'isAdmin', 'allUsers', 'month', 'year'));
    }
}
