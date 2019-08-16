<?php

namespace wbALFINop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $oferta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($oferta)
    {
        //
        $this->oferta = $oferta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.offer-send')->subject('Nuevo Oferta');
        // ->attach('111683-analisis.pdf', [
        //     'as' => 'prueba.pdf',
        //     'mime' => 'application/pdf',
        // ]);
    }
}
