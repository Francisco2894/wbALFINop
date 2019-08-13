<?php

namespace wbALFINop\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserNew
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $usuario,$password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($usuario,$password)
    {
        //
        $this->usuario = $usuario;
        $this->password = $password;
    }
}
