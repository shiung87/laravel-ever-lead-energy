<?php

namespace App\Mail;

use App\Models\DealerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerApplicationAckMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public DealerApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dealer Application Received [' . $this->application->reference_number . '] - Everlead Energy',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer-ack',
        );
    }
}
