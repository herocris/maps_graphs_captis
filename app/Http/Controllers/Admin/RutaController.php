<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RutaController extends Controller
{
    public function direccionar()
    {
     //dd(Auth::user()->hasRole('SuperAdministrador|Administrador'));   
        if (Auth::user()->hasRole('SuperAdministrador|Administrador')) {
            //dd("admi");
            return redirect()->route('user.index');
        }else{
            //dd("no tiene roles o no es admin");
            return redirect()->route('index');
        }
        
    }


}
