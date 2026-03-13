<?php

namespace App\Notifications;

use App\Models\LeadFollowUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowUpReminder extends Notification
{
    use Queueable;

    protected $followUp;

    public function __construct(LeadFollowUp $followUp)
    {
        $this->followUp = $followUp;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Scheduled Follow-up Reminder: ' . $this->followUp->lead->company_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder for your scheduled follow-up with ' . $this->followUp->lead->company_name . ' today.')
            ->line('Contact Person: ' . $this->followUp->lead->contact_name)
            ->line('Lead Status: ' . $this->followUp->status)
            ->action('View Lead Details', route('leads.show', $this->followUp->lead_id))
            ->line('Please ensure you record the outcome of this call in the system.');
    }

    public function toArray($notifiable)
    {
        return [
            'follow_up_id' => $this->followUp->id,
            'lead_id' => $this->followUp->lead_id,
            'company_name' => $this->followUp->lead->company_name,
            'message' => 'Scheduled follow-up due today.',
            'contact_name' => $this->followUp->lead->contact_name,
        ];
    }
}
