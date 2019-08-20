<?php

namespace wbALFINop\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendOffer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $oferta, $credito;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($oferta, $credito)
    {
        //
        $this->oferta = $oferta;
        $this->credito = $credito;
    }
}
