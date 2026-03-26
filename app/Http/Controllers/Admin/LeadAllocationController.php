<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\User;

class LeadAllocationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Lead::whereNull('assigned_to')->whereNot('status', 'Drop');

            // Add search filters
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('company_name', 'like', '%' . $request->search . '%')
                        ->orWhere('city', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $unassignedLeads = $query->latest()->paginate(20)->withQueryString();

            $callingUsers = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', ['sales', 'inside_sales', 'field_sales']);
            })->withCount('leads')->get();

            $recentlyAssigned = Lead::whereNotNull('assigned_to')
                ->with('assignedUser')
                ->latest('updated_at')
                ->take(10)
                ->get();

            return view('admin.leads.allocation', compact('unassignedLeads', 'callingUsers', 'recentlyAssigned'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load lead allocation data.');
        }
    }

    public function allocate(Request $request)
    {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to allocate leads.');
        }
    }
}
