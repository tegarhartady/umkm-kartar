<?php

namespace App\Mail;

use App\Models\Umkm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UmkmPasswordReset extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $umkm;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(Umkm $umkm, $password)
    {
        $this->umkm = $umkm;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Reset Password Akun UMKM',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.umkm.password_reset',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
