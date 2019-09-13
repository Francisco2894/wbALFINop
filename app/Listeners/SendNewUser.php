<?php

namespace wbALFINop\Listeners;

use wbALFINop\Events\UserNew;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use wbALFINop\Mail\LoginAccess;

class SendNewUser
{
    /**
     * Handle the event.
     *
     * @param  UserNew  $event
     * @return void
     */
    public function handle(UserNew $event)
    {
        //
        Mail::to($event->usuario)->queue(
        //Mail::to('coordinador.soporteinf@alfin.mx')->queue(
            new LoginAccess($event->usuario, $event->password)
        );
    }
}
