<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\User;

class LeadAllocationController extends Controller
{
    public function index()
    {
        $unassignedLeads = Lead::whereNull('assigned_to')->whereNot('status', 'Drop')->get();
        $callingUsers = User::whereHas('roles', function ($q) {
            $q->where('slug', 'calling');
        })->get();

        $recentlyAssigned = Lead::whereNotNull('assigned_to')->with('assignedUser')->latest('updated_at')->take(10)->get();

        return view('admin.leads.allocation', compact('unassignedLeads', 'callingUsers', 'recentlyAssigned'));
    }

    public function allocate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
        ]);

        Lead::whereIn('id', $request->lead_ids)->update([
            'assigned_to' => $request->user_id,
            'status' => 'New Lead' // Reset status to active if it was generic
        ]);

        return back()->with('success', count($request->lead_ids) . ' leads allocated successfully.');
    }
}
