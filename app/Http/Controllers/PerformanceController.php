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

        // Inside Sales Team Data (Leads)
        $insideSalesStats = [];
        if ($isAdmin || $user->hasRole(['sales', 'inside_sales'])) {
            $insideQuery = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', ['sales', 'inside_sales']);
            });

            if (!$isAdmin) {
                $insideQuery->where('id', $user->id);
            }

            $callingUsers = $insideQuery
                ->withCount([
                    'leads' => function ($q) use ($month, $year) {
                        $q->whereMonth('created_at', $month)->whereYear('created_at', $year);
                    },
                ])
                ->get();

            foreach ($callingUsers as $cu) {
                $cuLeads = Lead::where('assigned_to', $cu->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->select('status', 'prospect_status', DB::raw('count(*) as count'))->groupBy('status', 'prospect_status')->get();

                $untouchedLeadsCount = Lead::where('assigned_to', $cu->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->doesntHave('followUps')->count();

                $insideSalesStats[] = [
                    'user' => $cu,
                    'total' => $cu->leads_count,
                    'untouched' => $untouchedLeadsCount,
                    'by_status' => $cuLeads->groupBy('status')->map->sum('count'),
                    'by_prospect' => $cuLeads->groupBy('prospect_status')->map->sum('count'),
                ];
            }
        }

        // Field Sales Team Data (Leads)
        $fieldSalesStats = [];
        if ($isAdmin || $user->hasRole(['field_sales'])) {
            $fieldQuery = User::whereHas('roles', function ($q) {
                $q->where('slug', 'field_sales');
            });

            if (!$isAdmin) {
                $fieldQuery->where('id', $user->id);
            }

            $fieldUsers = $fieldQuery
                ->withCount([
                    'leads' => function ($q) use ($month, $year) {
                        $q->whereMonth('created_at', $month)->whereYear('created_at', $year);
                    },
                ])
                ->get();

            foreach ($fieldUsers as $cu) {
                $cuLeads = Lead::where('assigned_to', $cu->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->select('status', 'prospect_status', DB::raw('count(*) as count'))->groupBy('status', 'prospect_status')->get();

                $untouchedLeadsCount = Lead::where('assigned_to', $cu->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->doesntHave('followUps')->count();

                $fieldSalesStats[] = [
                    'user' => $cu,
                    'total' => $cu->leads_count,
                    'untouched' => $untouchedLeadsCount,
                    'by_status' => $cuLeads->groupBy('status')->map->sum('count'),
                    'by_prospect' => $cuLeads->groupBy('prospect_status')->map->sum('count'),
                ];
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

            $technicalUsers = $techQuery
                ->withCount([
                    'tasks' => function ($q) use ($month, $year) {
                        $q->whereMonth('created_at', $month)->whereYear('created_at', $year);
                    },
                ])
                ->get();

            foreach ($technicalUsers as $tu) {
                $tuTasks = $tu->tasks()->whereMonth('created_at', $month)->whereYear('created_at', $year)->select('status', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->groupBy('status', 'date')->get();

                $dailyActivity = $tuTasks->groupBy('date')->map(fn($dayTasks) => $dayTasks->sum('count'))->toArray();

                $techStats[] = [
                    'user' => $tu,
                    'total' => $tu->tasks_count,
                    'by_status' => $tuTasks->groupBy('status')->map->sum('count'),
                    'daily' => $dailyActivity,
                ];
            }
        }

        $allUsers = $isAdmin ? User::all() : collect([$user]);

        return view('performance.index', compact('insideSalesStats', 'fieldSalesStats', 'techStats', 'isAdmin', 'allUsers', 'month', 'year'));
    }
}
