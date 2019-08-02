<?php

namespace wbALFINop\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;
use wbALFINop\Sesion;

class SuccessfullLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //aqui va el codigo que se ejecuta despues del Evento
        $sesion=new Sesion;
        $sesion->id=$event->user->id;
        $sesion->f_login=new \DateTime();
        $sesion->save();
    }
}
