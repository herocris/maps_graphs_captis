<?php

namespace App\Http\Controllers\Admin;

use App\Models\TipoMunicion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoMunicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver municiones'])->only('index'); 
        $this->middleware(['permission:crear municion'])->only(['create','store']);
        $this->middleware(['permission:editar municiones'])->only(['edit','update']);
        $this->middleware(['permission:borrar municiones'])->only('destroy');
    }
    public function index()
    {//dd("llego");
        $dehabilitado=false;
        $municiones= TipoMunicion::orderBy('created_at', 'DESC')->get();
        return view('admin.tipoMunicion.index',compact('municiones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("hola");
        $tipomunicion = new TipoMunicion;
        return view('admin.tipoMunicion.create', compact('tipomunicion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'descripcion'=> 'required|unique:tipo_municions',
        ];

        $this->validate($request, $rules);

        $municion = new TipoMunicion;
        $municion->descripcion=$request->descripcion;
        $municion->save();

        return redirect()->route('tipomunicion.index')->with('flash', 'La munición '.$municion->descripcion.' ha sido creada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dehabilitado=true;
        $municiones= TipoMunicion::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.tipoMunicion.index',compact('municiones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMunicion $tipomunicion)
    {
        //dd($tipomunicion);
        return view('admin.tipoMunicion.edit', compact('tipomunicion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoMunicion $tipomunicion)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $tipomunicion->descripcion=$request->descripcion;
        $tipomunicion->save();

        return redirect()->route('tipomunicion.index')->with('flash', 'La munición '.$tipomunicion->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoMunicion $tipomunicion)
    {
        $tipomunicion->delete();
        return redirect()->route('tipomunicion.index')->with('flash', 'La munición '.$tipomunicion->descripcion.' ha sido eliminda.');;
    }

    public function restaurar($id)
    {
        TipoMunicion::withTrashed()->find($id)->restore();
        return redirect()->route('tipomunicion.index')->with('flash', 'La munición ha sido restaurada.');;
    }
}
