<?php

namespace wbALFINop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $oferta, $credito;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($oferta, $credito)
    {
        //
        $this->oferta = $oferta;
        $this->credito = $credito;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.offer-send')->subject("Refinanciamiento ID Cliente:".$this->credito->idCliente." ".date('d-m-Y', strtotime($this->oferta->fechai))." - ".date('d-m-Y', strtotime($this->oferta->fechaf)));
        // ->attach('111683-analisis.pdf', [
        //     'as' => 'prueba.pdf',
        //     'mime' => 'application/pdf',
        // ]);
    }
}
