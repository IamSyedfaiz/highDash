<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('roles')->paginate(15);
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load users list.');
        }
    }

    public function create()
    {
        try {
            $roles = Role::all();
            return view('admin.users.create', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load user creation form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'roles' => ['required', 'array'],
                'sales_target_amount' => ['nullable', 'numeric', 'min:0'],
            ]);

            $randomPassword = \Illuminate\Support\Str::random(10); // Auto-generate secure password

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($randomPassword),
                'sales_target_amount' => $request->sales_target_amount ?? 0,
            ]);

            $user->roles()->attach($request->roles);

            // Dispatch Email with logic
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserCreatedMail($user, $randomPassword));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mailing failed on User Creation: ' . $e->getMessage());
            }

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    public function show(User $user)
    {
        try {
            $user->load(['roles', 'attendances', 'loginSessions', 'activityLogs']);
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load user details.');
        }
    }

    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            $userRoles = $user->roles->pluck('id')->toArray();
            return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load user edit form.');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'roles' => ['required', 'array'],
                'sales_target_amount' => ['nullable', 'numeric', 'min:0'],
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'sales_target_amount' => $request->sales_target_amount ?? 0,
            ]);

            if ($request->filled('password')) {
                $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->roles()->sync($request->roles);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update user.');
        }
    }
    public function leadStats(User $user, $date)
    {
        try {
            $followUps = \App\Models\LeadFollowUp::where('user_id', $user->id)->whereDate('created_at', $date)->get();

            $statusCounts = $followUps->groupBy('status')->map->count();

            $notOpenCount = \App\Models\Lead::where('assigned_to', $user->id)->doesntHave('followUps')->count();

            return response()->json([
                'statuses' => $statusCounts,
                'not_open' => $notOpenCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load stats'], 500);
        }
    }

    public function toggleActive(User $user)
    {
        try {
            // Prevent admin from deactivating themselves if necessary? Maybe skip that logic for now or add a quick check
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot deactivate yourself.');
            }

            $user->update(['is_active' => !$user->is_active]);
            $status = $user->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "User successfully {$status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change user status.');
        }
    }
}
