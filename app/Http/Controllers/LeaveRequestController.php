<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            if ($user->isAdmin()) {
                $leaves = LeaveRequest::with('user')->latest()->get();
                return view('admin.leaves.index', compact('leaves'));
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
                'type' => 'required|string',
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
