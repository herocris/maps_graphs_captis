<?php

namespace App\Http\Controllers\Admin;

use App\Models\Parametro;
use App\Models\Tipo_parametro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParametroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$peroms=Auth::user()->getPermissionsViaRoles();
        $kkd=collect();
        // foreach ($peroms as $perom) {
        //     $kkd->push($perom->name);
        // }
        // $uis="usuarios";

        
       //dd(Auth::user()->can('ver Tipo de droga')); 


        $parametros= Parametro::all();
        $tipoParametros= Tipo_parametro::all();
        //dd($parametros);
        foreach ($tipoParametros as $tipoParametro) {
            if (Auth::user()->can('ver '.$tipoParametro->descripcion)) {
                $iusd=new Tipo_parametro;
                $iusd->descripcion=$tipoParametro->descripcion;
                $iusd->id =$tipoParametro->id;
                $kkd->push($iusd);
            }
        }

        //dd($kkd);

        return view('admin.parametro.index',compact('parametros','kkd'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parametro = new Parametro;
        $tipoParametros= Tipo_parametro::all();
        //dd($tipoParametros);
        return view('admin.parametro.create', compact('parametro','tipoParametros'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $rules = [
            'descripcion'=> 'required',
            'tipo_parametro_id'=> 'required',
        ];

        $this->validate($request, $rules);

        $parametro=new Parametro;
        $parametro->descripcion=$request->descripcion;
        $parametro->tipo_parametro_id=$request->tipo_parametro_id;
        $parametro->save();

        return redirect()->route('parametro.index')->with('flash', 'Tu publicación ha sido eliminada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return view('admin.tipodroga.edit', compact('tipo_droga'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Parametro $parametro)
    {
        //dd($parametro);
        $tipoParametros= Tipo_parametro::all();
        return view('admin.parametro.edit', compact('parametro','tipoParametros'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parametro $parametro)
    {
        //dd($tipodroga);
            $rules = [
                'descripcion'=> 'required',
            ];

            $this->validate($request, $rules);

            $parametro->descripcion=$request->descripcion;
            
            $parametro->save();

            return redirect()->route('parametro.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parametro $parametro)
    {
        $parametro->delete();
        return redirect()->route('parametro.index');
    }
}
