<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'manage-users' => 'Manage Users',
            'manage-roles' => 'Manage Roles',
            'manage-leads' => 'Manage Leads',
            'import-export-leads' => 'Import Export Leads',
            'view-assigned-leads' => 'View Assigned Leads',
            'update-calling-status' => 'Update Calling Status',
        ];

        $permissionModels = [];
        foreach ($permissions as $slug => $name) {
            $permissionModels[$slug] = \App\Models\Permission::create([
                'name' => $name,
                'slug' => $slug
            ]);
        }

        // 2. Create Roles
        $adminRole = \App\Models\Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'System Administrator']);
        $managerRole = \App\Models\Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Lead Manager']);
        $callingRole = \App\Models\Role::create(['name' => 'Calling', 'slug' => 'calling', 'description' => 'Calling Agent']);

        // 3. Assign Permissions to Roles
        $adminRole->permissions()->attach($permissionModels);
        $managerRole->permissions()->attach([$permissionModels['manage-leads'], $permissionModels['view-assigned-leads'], $permissionModels['update-calling-status']]);
        $callingRole->permissions()->attach([$permissionModels['view-assigned-leads'], $permissionModels['update-calling-status']]);

        // 4. Create Users
        $admin = \App\Models\User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => \Illuminate\Support\Facades\Hash::make('password')]);
        $admin->roles()->attach($adminRole);

        $manager = \App\Models\User::create(['name' => 'Manager User', 'email' => 'manager@example.com', 'password' => \Illuminate\Support\Facades\Hash::make('password')]);
        $manager->roles()->attach($managerRole);

        $caller = \App\Models\User::create(['name' => 'Calling Agent', 'email' => 'caller@example.com', 'password' => \Illuminate\Support\Facades\Hash::make('password')]);
        $caller->roles()->attach($callingRole);

        // 5. Create Demo Leads
        \App\Models\Lead::factory(50)->create();
    }
}
