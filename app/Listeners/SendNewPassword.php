<?php

namespace wbALFINop\Listeners;

use wbALFINop\Events\UserNewPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use wbALFINop\Mail\LoginCredentials;

class SendNewPassword
{
    /**
     * Handle the event.
     *
     * @param  UserNewPassword  $event
     * @return void
     */
    public function handle(UserNewPassword $event)
    {
        //Envio de correo electronico
        Mail::to('coordinador.soporteinf@alfin.mx')->queue(
            new LoginCredentials($event->usuario, $event->password)
        );
    }
}
