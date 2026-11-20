<?php

namespace App\Mail;

use App\Models\DealerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public DealerApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[New Dealer Application] ' . $this->application->business_name . ' (' . $this->application->reference_number . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer-admin-notification',
        );
    }
}
