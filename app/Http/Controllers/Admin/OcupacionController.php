<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ocupacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OcupacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver ocupaciones'])->only('index'); 
        $this->middleware(['permission:crear ocupacion'])->only(['create','store']);
        $this->middleware(['permission:editar ocupacion'])->only(['edit','update']);
        $this->middleware(['permission:borrar ocupacion'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $ocupaciones= Ocupacion::orderBy('created_at', 'DESC')->get();
        return view('admin.ocupacion.index',compact('ocupaciones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ocupacion = new Ocupacion;
        return view('admin.ocupacion.create', compact('ocupacion'));
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
            'descripcion'=> 'required|unique:ocupacions',
        ];

        $this->validate($request, $rules);

        $ocupacion=new Ocupacion;
        $ocupacion->descripcion=$request->descripcion;
        $ocupacion->save();

        return redirect()->route('ocupacion.index')->with('flash', 'La ocupación '.$ocupacion->descripcion.' ha sido creada.');
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
        $ocupaciones= Ocupacion::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.ocupacion.index',compact('ocupaciones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ocupacion $ocupacion)
    {
        return view('admin.ocupacion.edit', compact('ocupacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ocupacion $ocupacion)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $ocupacion->descripcion=$request->descripcion;
        $ocupacion->save();

        return redirect()->route('ocupacion.index')->with('flash', 'La ocupación '.$ocupacion->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ocupacion $ocupacion)
    {
        $ocupacion->delete();
        return redirect()->route('ocupacion.index')->with('flash', 'La ocupación '.$ocupacion->descripcion.' ha sido eliminada.');
    }

    public function restaurar($id)
    {
        Ocupacion::withTrashed()->find($id)->restore();
        return redirect()->route('ocupacion.index')->with('flash', 'La ocupación ha sido restaurada.');;
    }
}
