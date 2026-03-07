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
        try {
            $user = Auth::user();
            $query = Lead::with('assignedUser');

            if ($user->hasRole('calling')) {
                $query->where('assigned_to', $user->id);
            }

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
            $users = User::all();

            return view('leads.index', compact('leads', 'users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load leads list.');
        }
    }

    public function create()
    {
        try {
            if (!Auth::user()->isAdmin() && !Auth::user()->hasRole('manager')) {
                return redirect()->route('leads.index')->with('error', 'Unauthorized to create leads manually.');
            }
            return view('leads.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to open lead creation form.');
        }
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::user()->isAdmin() && !Auth::user()->hasRole('manager')) {
                return redirect()->route('leads.index')->with('error', 'Unauthorized to create leads manually.');
            }
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create lead.');
        }
    }

    public function show(Lead $lead)
    {
        try {
            $lead->load(['assignedUser', 'followUps.user']);
            return view('leads.show', compact('lead'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to display lead details.');
        }
    }

    public function edit(Lead $lead)
    {
        try {
            return view('leads.edit', compact('lead'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to open lead edit form.');
        }
    }

    public function update(Request $request, Lead $lead)
    {
        try {
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

            if ($validated['status'] === 'Drop') {
                $validated['assigned_to'] = null;
            }

            $lead->update($validated);
            return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update lead.');
        }
    }

    public function destroy(Lead $lead)
    {
        try {
            $lead->delete();
            return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete lead.');
        }
    }

    public function quickUpdate(Request $request, Lead $lead)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string',
                'calling_status' => 'required|string',
                'feedback' => 'nullable|string',
            ]);

            if ($validated['status'] === 'Drop') {
                $lead->assigned_to = null;
            }

            $lead->update($validated);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'lead_quick_update',
                'description' => 'Updated status for lead: ' . $lead->company_name,
                'model_type' => Lead::class,
                'model_id' => $lead->id,
            ]);

            return back()->with('success', 'Status updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Quick update failed.');
        }
    }
    public function storeFollowUp(Request $request, Lead $lead)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string',
                'message' => 'required|string',
                'next_follow_up_date' => 'nullable|date|after_or_equal:today',
            ]);

            $lead->followUps()->create([
                'user_id' => Auth::id(),
                'status' => $validated['status'],
                'message' => $validated['message'],
                'next_follow_up_date' => $validated['next_follow_up_date'],
            ]);

            // Update lead status to match follow up result
            $lead->update([
                'calling_status' => $validated['status'],
                'feedback' => $validated['message']
            ]);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'lead_followup',
                'description' => 'Recorded follow-up for lead: ' . $lead->company_name,
                'model_type' => Lead::class,
                'model_id' => $lead->id,
            ]);

            return back()->with('success', 'Follow-up recorded successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to record follow-up.');
        }
    }
}
