<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kra;
use App\Models\User;

class KraController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $query = Kra::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $kras = $query->latest()->get();
        return view('admin.kra.index', compact('kras', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|numeric|min:0'
        ]);

        Kra::create($validated);
        return back()->with('success', 'KRA created successfully.');
    }

    public function update(Request $request, Kra $kra)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|numeric|min:0'
        ]);

        $kra->update($validated);
        return back()->with('success', 'KRA updated successfully.');
    }

    public function destroy(Kra $kra)
    {
        $kra->delete();
        return back()->with('success', 'KRA deleted successfully.');
    }

    public function updateTarget(Request $request, User $user)
    {
        $request->validate([
            'sales_target_amount' => 'required|numeric|min:0'
        ]);

        $user->update([
            'sales_target_amount' => $request->sales_target_amount
        ]);

        return back()->with('success', 'Monthly target for ' . $user->name . ' updated successfully.');
    }
}
