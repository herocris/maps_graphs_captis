<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\autentificacion;
use Illuminate\Support\Facades\Mail;

class enviar_authController extends Controller
{
    public function store(){
        $correo=new autentificacion;
        Mail::to('cristhianivan.torresgarcia@gmail.com')->send($correo);
        return "Mesage enviado";
    }
}
