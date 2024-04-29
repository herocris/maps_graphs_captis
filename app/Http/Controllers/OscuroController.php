<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class OscuroController extends Controller
{
    public function oscuro(Request $request, User $user)
    {
        //dd($request->oscuro);
        // $usuario=Auth::user();
        // $color=$usuario->oscuro==1?"oscuro":"claro";
        // activity()
        //     ->causedBy($usuario)
        //     ->event('Cambio de color')
        //     ->useLog($usuario->name)
        //     ->log('El usuario ha cambiado a color '.$color.' la interfaz del sistema' );

        $user->oscuro=$request->oscuro;
        $user->save();
        

        return redirect()->back();
    }
}
