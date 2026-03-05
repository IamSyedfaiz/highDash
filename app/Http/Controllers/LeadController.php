<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with('assignedUser');

        // Lead Logic: If Calling Agent, only show assigned leads
        if ($user->hasRole('calling')) {
            $query->where('assigned_to', $user->id);
        }

        // Filtering
        if ($request->company)
            $query->where('company_name', 'like', '%' . $request->company . '%');
        if ($request->status)
            $query->where('status', $request->status);
        if ($request->city)
            $query->where('city', 'like', '%' . $request->city . '%');
        if ($request->business_type)
            $query->where('business_type', $request->business_type);
        if ($request->source)
            $query->where('lead_source', $request->source);
        if ($request->assigned_to)
            $query->where('assigned_to', $request->assigned_to);

        $leads = $query->latest()->paginate(15)->withQueryString();
        $users = User::all(); // For filtering/allocation dropdowns

        return view('leads.index', compact('leads', 'users'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'phone_1' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'address' => 'nullable|string',
            'business_type' => 'required|string',
            'lead_source' => 'nullable|string',
        ]);

        Lead::create($validated);

        return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'phone_1' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'address' => 'nullable|string',
            'business_type' => 'required|string',
            'lead_source' => 'nullable|string',
            'status' => 'required|string',
            'calling_status' => 'nullable|string',
            'feedback' => 'nullable|string',
        ]);

        // Workflow: If status is Drop, unassign
        if ($validated['status'] === 'Drop') {
            $validated['assigned_to'] = null;
        }

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }

    // Quick Update for Calling Workflow
    public function quickUpdate(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'calling_status' => 'required|string',
            'feedback' => 'nullable|string',
        ]);

        if ($validated['status'] === 'Drop') {
            $lead->assigned_to = null;
        }

        $lead->update($validated);

        return back()->with('success', 'Status updated.');
    }
}
