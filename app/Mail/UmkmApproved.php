<?php

namespace App\Mail;

use App\Models\Umkm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UmkmApproved extends Mailable implements ShouldQueue
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
            subject: 'Selamat! Pendaftaran UMKM Anda Telah Disetujui',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.umkm.approved',
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
