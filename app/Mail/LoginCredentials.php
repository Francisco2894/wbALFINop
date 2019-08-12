<?php

namespace wbALFINop\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginCredentials extends Mailable
{
    use Queueable, SerializesModels;
    public $usuario,$password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $password)
    {
        //
        $this->usuario = $usuario;
        $this->password =$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.login-credentials')->subject('Tu Nueva Contraseña');
    }
}
