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
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'roles' => ['required', 'array'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->attach($request->roles);

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
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
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
            $followUps = \App\Models\LeadFollowUp::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->get();

            $statusCounts = $followUps->groupBy('status')->map->count();

            $notOpenCount = \App\Models\Lead::where('assigned_to', $user->id)
                ->doesntHave('followUps')
                ->count();

            return response()->json([
                'statuses' => $statusCounts,
                'not_open' => $notOpenCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load stats'], 500);
        }
    }
}
