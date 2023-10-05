<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestOTR extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Vehicle $vehicle;
    public function __construct(User $user, Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTR Requested for ' . $this->vehicle->niceName(),
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.request-o-t-r');
    }

    public function attachments(): array
    {
        return [];
    }
}
