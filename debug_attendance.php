<?php
ob_start();
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Attendance;
use App\Models\LoginSession;
use Carbon\Carbon;

$user = User::latest()->first(); // Use someone who might be active
$userId = $user->id;
$now = now('Asia/Kolkata');
$date = $now->toDateString();

echo "Current Time (IST): " . $now->toDateTimeString() . "\n";
echo "Checking User: " . $user->name . " (ID " . $userId . ")\n";

$attendance = Attendance::where('user_id', $userId)->where('date', $date)->first();
if ($attendance) {
    echo "Attendance ID: " . $attendance->id . "\n";
    echo "Work Duration Min: " . $attendance->work_duration_minutes . "\n";
    echo "Status: " . $attendance->status . "\n";
} else {
    echo "No attendance record for today for user " . $userId . ".\n";
}

$dayStart = $now->copy()->startOfDay();
$dayEnd = $now->copy()->endOfDay();

$sessions = LoginSession::where('user_id', $userId)
    ->where('login_at', '<', $dayEnd)
    ->where(function ($query) use ($dayStart) {
        $query->whereNull('logout_at')
            ->orWhere('logout_at', '>', $dayStart);
    })->get();

echo "Total overlapping sessions found in DB: " . $sessions->count() . "\n";

$totalSeconds = 0;
foreach ($sessions as $session) {
    echo " - Session ID " . $session->id . "\n";
    echo "   DB Raw Login: " . $session->getRawOriginal('login_at') . "\n";
    echo "   DB Raw Logout: " . ($session->getRawOriginal('logout_at') ?? 'NULL') . "\n";

    $sessLogin = $session->login_at->copy()->setTimezone('Asia/Kolkata');
    $sessLogout = ($session->logout_at ?? $now)->copy()->setTimezone('Asia/Kolkata');

    echo "   SessLogin (IST): " . $sessLogin->toDateTimeString() . "\n";
    echo "   SessLogout (IST): " . $sessLogout->toDateTimeString() . "\n";
    echo "   DayStart (IST): " . $dayStart->toDateTimeString() . "\n";
    echo "   DayEnd (IST): " . $dayEnd->toDateTimeString() . "\n";

    $start = $sessLogin->gt($dayStart) ? $sessLogin : $dayStart;
    $end = $sessLogout->lt($dayEnd) ? $sessLogout : $dayEnd;

    echo "   Computed Start: " . $start->toDateTimeString() . "\n";
    echo "   Computed End: " . $end->toDateTimeString() . "\n";

    if ($end->gt($start)) {
        $sec = (int) $end->diffInSeconds($start);
        echo "   Seconds detected: " . $sec . "\n";
        $totalSeconds += $sec;
    } else {
        echo "   No overlap or invalid duration.\n";
    }
}
echo "Total Calculated Minutes: " . floor($totalSeconds / 60) . "\n";
$content = ob_get_clean();
file_put_contents('debug_output.txt', $content);
echo "Logged to debug_output.txt\n";
