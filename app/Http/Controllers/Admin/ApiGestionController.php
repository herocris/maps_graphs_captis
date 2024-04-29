<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiGestionController extends Controller
{
    public function indexTokens()
    {
        return view('admin.api.TokensPersonales');
    }
    public function indexApiClients()
    {
        debug("ldfha");
        return view('admin.api.ClientesApi');
    }
    public function indexAuthorizedApiClients()
    {
        return view('admin.api.ClientesApiAutorizados');
    }

    public function pruebapost()
    {
        debug("prueba");
        return response()->json(['msg'=> "ksjald"], 200);
    }
}
