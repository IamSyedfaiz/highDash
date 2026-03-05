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
        $user = Auth::user();
        if ($user->isAdmin()) {
            $leaves = LeaveRequest::with('user')->latest()->get();
            return view('admin.leaves.index', compact('leaves'));
        }

        $leaves = LeaveRequest::where('user_id', $user->id)->latest()->get();
        return view('user.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('user.leaves.create');
    }

    public function store(Request $request)
    {
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
            'description' => 'Applied for ' . $request->type . ' leave',
            'model_type' => LeaveRequest::class,
            'model_id' => $leave->id,
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function updateStatus(Request $request, LeaveRequest $leaf)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $leaf->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'leave_status_update',
            'description' => 'Leave status for user ' . $leaf->user->name . ' updated to ' . $request->status,
            'model_type' => LeaveRequest::class,
            'model_id' => $leaf->id,
        ]);

        return redirect()->back()->with('success', 'Leave status updated successfully.');
    }
}
