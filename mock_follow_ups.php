<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\Role;
use Carbon\Carbon;

// Identify Calling Team Users
$callingTeamRoles = Role::whereIn('slug', ['sales', 'inside_sales', 'field_sales'])->pluck('id');
$callingUsers = User::whereHas('roles', function ($q) use ($callingTeamRoles) {
    $q->whereIn('roles.id', $callingTeamRoles);
})->get();

if ($callingUsers->isEmpty()) {
    echo "No calling team users found. Getting some arbitrary users to act as callers.\n";
    $callingUsers = User::whereIn('id', [13, 14, 15, 16])->get();
}

$statuses = Lead::STATUSES;

$leads = Lead::take($callingUsers->count() * 5)->get();
if ($leads->isEmpty()) {
    echo "No leads found in DB!\n";
    exit;
}

$leadIndex = 0;

foreach ($callingUsers as $user) {
    echo "Assigning leads to user: " . $user->name . "\n";

    // Assign 5 leads per user
    for ($i = 0; $i < 5; $i++) {
        if (!isset($leads[$leadIndex])) {
            break;
        }

        $lead = $leads[$leadIndex];

        $randomStatus = $statuses[array_rand($statuses)];

        // Update Lead
        $lead->assigned_to = $user->id;
        $lead->status = $randomStatus;
        $lead->save();

        // Remove existing followups
        LeadFollowUp::where('lead_id', $lead->id)->delete();

        // Create an upcoming followup
        LeadFollowUp::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'status' => $randomStatus,
            'message' => 'Mock Follow Up added for testing.',
            'next_follow_up_date' => Carbon::now()->addDays(rand(1, 5))->setHour(rand(9, 17))->setMinute(0)
        ]);

        $leadIndex++;
    }
}

echo "Successfully populated mock follow ups!\n";
