<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailFichaInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    public $detalle;

    /**
     * Create a new message instance.
     */
    public function __construct($detalle)
    {
        $this->detalle = $detalle;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ficha de Inscripción',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'modulo-inscripcion.email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->detalle['pdf_email'])
                ->as($this->detalle['nombre_pdf'])
                ->withMime('application/pdf'),
        ];
    }
}
