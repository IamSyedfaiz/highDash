<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveRequest;

class LeaveStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Leave Request has been ' . ucfirst($this->leaveRequest->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.leave.updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
