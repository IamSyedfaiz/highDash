<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Lead;
use App\Models\Attendance;
use App\Models\LoginSession;
use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

$keepUsers = [1, 13, 14, 15, 16, 17, 18, 19, 20];

DB::beginTransaction();
try {
    // 1. Unassign all leads
    Lead::query()->update(['assigned_to' => null]);
    echo "All leads unassigned.\n";

    // 2. Identify users to delete
    $usersToDelete = User::whereNotIn('id', $keepUsers)->pluck('id')->toArray();
    echo "Users to delete: " . implode(', ', $usersToDelete) . "\n";

    if (!empty($usersToDelete)) {
        // 3. Remove related data for deleted users
        Attendance::whereIn('user_id', $usersToDelete)->delete();
        echo "Deleted attendances for removed users.\n";

        LoginSession::whereIn('user_id', $usersToDelete)->delete();
        echo "Deleted login sessions for removed users.\n";

        LeaveRequest::whereIn('user_id', $usersToDelete)->delete();
        echo "Deleted leave requests for removed users.\n";

        Task::whereIn('user_id', $usersToDelete)->orWhereIn('created_by', $usersToDelete)->delete();
        echo "Deleted tasks for removed users.\n";

        ActivityLog::whereIn('user_id', $usersToDelete)->delete();
        echo "Deleted activity logs for removed users.\n";

        DB::table('role_user')->whereIn('user_id', $usersToDelete)->delete();
        echo "Deleted role_user pivot data for removed users.\n";

        DB::table('notifications')->where('notifiable_type', User::class)->whereIn('notifiable_id', $usersToDelete)->delete();
        echo "Deleted notifications for removed users.\n";

        // Optional: lead_follow_ups if user_id exists
        if (Schema::hasColumn('lead_follow_ups', 'user_id')) {
            DB::table('lead_follow_ups')->whereIn('user_id', $usersToDelete)->delete();
        } elseif (Schema::hasColumn('lead_follow_ups', 'added_by')) {
            DB::table('lead_follow_ups')->whereIn('added_by', $usersToDelete)->delete();
        }

        // 4. Finally delete the users
        User::whereIn('id', $usersToDelete)->delete();
        echo "Deleted users.\n";
    }

    DB::commit();
    echo "Cleanup completed successfully!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Failed: " . $e->getMessage() . "\n";
}
