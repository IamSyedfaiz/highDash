<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TechnicalRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tech Permissions
        $permissions = [
            'manage-tasks' => 'Manage Own Tasks',
            'admin-tasks' => 'Administer All Tasks',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // 2. Create Tech Role
        $techRole = Role::firstOrCreate(
            ['slug' => 'technical'],
            ['name' => 'Technical Team', 'description' => 'Development and Tech Support']
        );

        // 3. Assign Permissions
        $techRole->permissions()->syncWithoutDetaching([
            Permission::where('slug', 'manage-tasks')->first()->id
        ]);

        // 4. Update Admin Role to have all permissions
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->syncWithoutDetaching(Permission::all()->pluck('id'));
        }

        // 5. Create a Tech User for testing
        $techUser = User::firstOrCreate(
            ['email' => 'tech@example.com'],
            [
                'name' => 'Tech Specialist',
                'password' => Hash::make('password'),
            ]
        );
        $techUser->roles()->syncWithoutDetaching([$techRole->id]);

        // 6. Create Demo Tasks
        \App\Models\Task::firstOrCreate(
            ['title' => 'Initialize Server Configuration'],
            [
                'user_id' => $techUser->id,
                'created_by' => $techUser->id,
                'description' => 'Setup the production environment and deploy the CRM.',
                'status' => 'closed',
            ]
        );

        \App\Models\Task::firstOrCreate(
            ['title' => 'Optimize Lead Matching Algorithm'],
            [
                'user_id' => $techUser->id,
                'created_by' => $techUser->id,
                'description' => 'Refine the SQL queries for better search performance.',
                'status' => 'started',
            ]
        );
    }
}
