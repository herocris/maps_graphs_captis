<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tipo_parametro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoParametroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_parametros= Tipo_parametro::all();
        return view('admin.tipoParametro.index',compact('tipo_parametros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipoParametro = new Tipo_parametro;
        return view('admin.tipoParametro.create', compact('tipoParametro'));
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
        ];

        $this->validate($request, $rules);

        $tipoParametro=new Tipo_parametro;
        $tipoParametro->descripcion=$request->descripcion;
        $tipoParametro->save();

        return redirect()->route('tipoParametro.index')->with('flash', 'Tu publicación ha sido eliminada.');
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
    public function edit(Tipo_parametro $tipoParametro)
    {
        //dd($tipodroga);
        return view('admin.tipoParametro.edit', compact('tipoParametro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipo_parametro $tipoParametro)
    {
        //dd($tipodroga);
            $rules = [
                'descripcion'=> 'required',
            ];

            $this->validate($request, $rules);

            $tipoParametro->descripcion=$request->descripcion;
            
            $tipoParametro->save();

            return redirect()->route('tipoParametro.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipo_parametro $tipoParametro)
    {
        $tipoParametro->delete();
        return redirect()->route('tipoParametro.index');
    }
}
