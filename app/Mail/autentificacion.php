<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class autentificacion extends Mailable
{
    use Queueable, SerializesModels;

    public $subject="Informacion de contacto";
    public $contancto;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($autentificar)
    {
        //
        $this->contancto=$autentificar;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.autentificacion');
    }
}
