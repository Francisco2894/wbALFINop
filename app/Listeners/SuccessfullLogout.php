<?php

namespace wbALFINop\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use DB;
use wbALFINop\Sesion;

class SuccessfullLogout
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
      //aqui va el codigo que se ejecuta despues del Evento
      $idsesion=Sesion::where('id', '=', $event->user->id)->max('idSesion');
      $sesion=Sesion::where('idSesion', '=', $idsesion)->first();
      $sesion->f_logout=new \DateTime();
      $sesion->update();
    }
}
