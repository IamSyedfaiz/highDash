<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lead;
use App\Models\Role;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\LoginSession;
use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\LeadFollowUp;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 1. Create or ensure some users and roles exist
        $roles = Role::pluck('name', 'id')->toArray();
        if (empty($roles)) {
            $roleCalling = Role::create(['name' => 'calling', 'description' => 'Calling Team']);
            $roleTechnical = Role::create(['name' => 'technical', 'description' => 'Technical Team']);
            $roles = Role::pluck('name', 'id')->toArray();
        }

        $roleIds = array_keys($roles);

        // Ensure we have at least 10 users
        $usersCount = User::count();
        if ($usersCount < 10) {
            for ($i = 0; $i < 10 - $usersCount; $i++) {
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'), // password
                ]);
                $user->roles()->attach($faker->randomElement($roleIds));
            }
        }

        $users = User::all();
        $userIds = $users->pluck('id')->toArray();

        $this->command->info('Seeding User Metadata (Attendances, Leaves, Sessions, Tasks)...');

        // Seeding user metadata
        $attendances = [];
        $sessions = [];
        $activityLogs = [];
        $leaves = [];
        $tasks = [];

        foreach ($users as $user) {
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::today()->subDays($i);

                $attendances[] = [
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'login_at' => $date->copy()->setHour(9)->setMinute(rand(0, 30)),
                    'logout_at' => $date->copy()->setHour(17)->setMinute(rand(0, 59)),
                    'work_duration_minutes' => 480,
                    'status' => 'present',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $sessions[] = [
                    'user_id' => $user->id,
                    'duration_minutes' => 480,
                    'ip_address' => $faker->ipv4,
                    'user_agent' => substr($faker->userAgent, 0, 255),
                    'login_at' => $date->copy()->setHour(9)->setMinute(rand(0, 30)),
                    'logout_at' => $date->copy()->setHour(17)->setMinute(rand(0, 59)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $activityLogs[] = [
                    'user_id' => $user->id,
                    'action' => 'login',
                    'description' => 'User logged in',
                    'model_type' => null,
                    'model_id' => null,
                    'properties' => json_encode(['ip' => $faker->ipv4]),
                    'created_at' => $date->copy()->setHour(9)->setMinute(rand(0, 30)),
                    'updated_at' => now(),
                ];
            }

            for ($i = 0; $i < 3; $i++) {
                $leaves[] = [
                    'user_id' => $user->id,
                    'type' => $faker->randomElement(['sick', 'casual', 'annual', 'unpaid', 'other']),
                    'from_date' => Carbon::today()->addDays(rand(1, 10))->format('Y-m-d'),
                    'to_date' => Carbon::today()->addDays(rand(11, 15))->format('Y-m-d'),
                    'reason' => $faker->sentence,
                    'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                    'approved_by' => $faker->randomElement($userIds),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            for ($i = 0; $i < 5; $i++) {
                $tasks[] = [
                    'user_id' => $user->id,
                    'created_by' => $userIds[array_rand($userIds)],
                    'title' => substr($faker->sentence, 0, 255),
                    'description' => $faker->paragraph,
                    'url' => $faker->url,
                    'status' => $faker->randomElement(['pending', 'started', 'closed']),
                    'task_date' => Carbon::today()->format('Y-m-d'),
                    'started_at' => now(),
                    'completed_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($attendances, 1000) as $chunk) {
            Attendance::insert($chunk);
        }
        foreach (array_chunk($sessions, 1000) as $chunk) {
            LoginSession::insert($chunk);
        }
        foreach (array_chunk($activityLogs, 1000) as $chunk) {
            ActivityLog::insert($chunk);
        }
        foreach (array_chunk($leaves, 1000) as $chunk) {
            LeaveRequest::insert($chunk);
        }
        foreach (array_chunk($tasks, 1000) as $chunk) {
            Task::insert($chunk);
        }


        $this->command->info('Seeding 10,000 Leads in chunks...');
        $businessTypes = ['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'];
        $leadStatuses = ['Pending', 'New Lead', 'Existing', 'Drop'];
        $callingStatuses = ['Call Answered', 'Busy / Callback', 'Not Answered', 'Interested', 'Not Interested', 'Switched Off', 'Wrong Number'];

        $totalLeads = 10000;
        $batchSize = 1000;

        for ($i = 0; $i < $totalLeads / $batchSize; $i++) {
            $leads = [];
            for ($j = 0; $j < $batchSize; $j++) {
                $assignedTo = $userIds[array_rand($userIds)];
                $leads[] = [
                    'company_name' => substr($faker->company, 0, 255),
                    'name' => substr($faker->name, 0, 255),
                    'contact_name' => substr($faker->name, 0, 255),
                    'designation' => substr($faker->jobTitle, 0, 255),
                    'add_distribution' => substr($faker->word, 0, 255),
                    'keywords' => substr($faker->word, 0, 255),
                    'email' => $faker->unique()->safeEmail,
                    'phone' => substr($faker->phoneNumber, 0, 15),
                    'phone_1' => substr($faker->phoneNumber, 0, 15),
                    'phone_2' => null,
                    'city' => substr($faker->city, 0, 50),
                    'state' => substr($faker->state, 0, 50),
                    'address' => substr($faker->address, 0, 255),
                    'business_type' => $faker->randomElement($businessTypes),
                    'lead_source' => 'Website',
                    'status' => $faker->randomElement($leadStatuses),
                    'prospect_status' => 'None',
                    'calling_status' => $faker->randomElement($callingStatuses),
                    'feedback' => substr($faker->sentence, 0, 255),
                    'assigned_to' => $assignedTo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('leads')->insert($leads);
            $this->command->info("Inserted batch " . ($i + 1));
        }

        $this->command->info('Seeding Follow Ups...');
        $leadIds = DB::table('leads')->pluck('id')->random(5000)->toArray();
        $followUps = [];
        foreach ($leadIds as $id) {
            $followUps[] = [
                'lead_id' => $id,
                'user_id' => $userIds[array_rand($userIds)],
                'status' => $callingStatuses[array_rand($callingStatuses)],
                'message' => 'Dummy Follow-up',
                'next_follow_up_date' => Carbon::now()->addDays(rand(1, 5)),
                'created_at' => Carbon::now()->subDays(rand(0, 3)),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($followUps, 1000) as $chunk) {
            LeadFollowUp::insert($chunk);
        }

        $this->command->info('Dummy data seeding completed!');
    }
}
