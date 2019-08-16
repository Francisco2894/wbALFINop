<?php

namespace wbALFINop\Listeners;

use wbALFINop\Events\SendOffer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use wbALFINop\Mail\OfferMessage;

class SendMessage
{

    /**
     * Handle the event.
     *
     * @param  SendOffer  $event
     * @return void
     */
    public function handle(SendOffer $event)
    {
        //
        //Mail::to($event->usuario)->queue(
        Mail::to(['coordinador.soporteinf@alfin.mx','soporte.alfin2.0@gmail.com'])->queue(
            new OfferMessage($event->oferta)
        );
    }
}
