<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            return view('admin.roles.index', compact('roles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load roles list.');
        }
    }

    public function create()
    {
        try {
            $permissions = Permission::all();
            return view('admin.roles.create', compact('permissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load role creation form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles',
                'description' => 'nullable|string',
                'permissions' => 'required|array',
            ]);

            $role = Role::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            $role->permissions()->attach($request->permissions);

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create role.');
        }
    }

    public function edit(Role $role)
    {
        try {
            $permissions = Permission::all();
            $rolePermissions = $role->permissions->pluck('id')->toArray();
            return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load role edit form.');
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'permissions' => 'required|array',
            ]);

            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $role->permissions()->sync($request->permissions);

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update role.');
        }
    }
}
