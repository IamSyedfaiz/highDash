<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeadFollowUp;
use App\Models\User;
use App\Notifications\FollowUpReminder;
use Carbon\Carbon;

class SendFollowUpReminders extends Command
{
    protected $signature = 'followups:send-reminders';
    protected $description = 'Send email and database notifications for today\'s follow-ups';

    public function handle()
    {
        $today = now('Asia/Kolkata')->toDateString();

        $followUps = LeadFollowUp::whereDate('next_follow_up_date', $today)
            ->with(['lead', 'user'])
            ->get();

        $count = 0;
        foreach ($followUps as $followUp) {
            // Check if already notified for this today (to avoid spam if run multiple times)
            // For simplicity, we just notify.
            if ($followUp->user) {
                $followUp->user->notify(new FollowUpReminder($followUp));
                $count++;
            }
        }

        $this->info("Sent {$count} follow-up reminders.");
    }
}
