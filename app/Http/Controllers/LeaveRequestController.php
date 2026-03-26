<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->isAdmin()) {
                $query = LeaveRequest::with('user')->latest();

                if ($request->has('user_id') && $request->user_id) {
                    $query->where('user_id', $request->user_id);
                }

                if ($request->has('date') && $request->date) {
                    $query->whereDate('from_date', '<=', $request->date)
                        ->whereDate('to_date', '>=', $request->date);
                }

                if ($request->has('role_id') && $request->role_id) {
                    $query->whereHas('user.roles', function ($q) use ($request) {
                        $q->where('roles.id', $request->role_id);
                    });
                }

                $leaves = $query->paginate(20)->withQueryString();
                $users = \App\Models\User::orderBy('name')->get();
                $roles = \App\Models\Role::all();
                return view('admin.leaves.index', compact('leaves', 'users', 'roles'));
            }

            $leaves = LeaveRequest::where('user_id', $user->id)->latest()->get();
            return view('user.leaves.index', compact('leaves'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to load leave requests.');
        }
    }

    public function create()
    {
        try {
            return view('user.leaves.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to open leave application form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:casual,sick,annual,unpaid,maternity/paternity,other',
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'reason' => 'required|string|max:500',
            ]);

            $leave = LeaveRequest::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'leave_apply',
                'description' => 'Applied for ' . $request->type . ' leave from ' . $request->from_date . ' to ' . $request->to_date,
                'model_type' => LeaveRequest::class,
                'model_id' => $leave->id,
            ]);

            return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to submit leave request. Please try again.');
        }
    }

    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,rejected,pending',
            ]);

            $leave->update([
                'status' => $request->status,
                'approved_by' => Auth::id(),
                'approved_at' => Carbon::now(),
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'leave_status_update',
                'description' => 'Leave status for user ' . $leave->user->name . ' updated to ' . $request->status,
                'model_type' => LeaveRequest::class,
                'model_id' => $leave->id,
            ]);

            return redirect()->back()->with('success', 'Leave status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update leave status.');
        }
    }
}
