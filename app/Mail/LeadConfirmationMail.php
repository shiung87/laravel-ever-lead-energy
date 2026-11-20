<?php

namespace App\Mail;

use App\Models\CustomerLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public CustomerLead $lead)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Enquiry Received [' . $this->lead->reference_number . '] - Everlead Energy',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-confirmation',
        );
    }
}
