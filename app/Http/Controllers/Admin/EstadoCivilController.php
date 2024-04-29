<?php

namespace App\Http\Controllers\Admin;

use App\Models\EstadoCivil;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstadoCivilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver estados civiles'])->only('index'); 
        $this->middleware(['permission:crear estados civiles'])->only(['create','store']);
        $this->middleware(['permission:editar estados civiles'])->only(['edit','update']);
        $this->middleware(['permission:borrar estados civiles'])->only('destroy');
    }
    public function index()
    {
        debug("habilitados");
        $dehabilitado=false;
        $estados= EstadoCivil::orderBy('created_at', 'DESC')->get();
        return view('admin.estadoCivil.index',compact('estados','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estado = new EstadoCivil;
        return view('admin.estadoCivil.create', compact('estado'));
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
            'descripcion'=> 'required|unique:estado_civils',
        ];

        $this->validate($request, $rules);

        $estado=new EstadoCivil;
        $estado->descripcion=$request->descripcion;
        $estado->save();

        return redirect()->route('estado.index')->with('flash', 'El estado civil '.$estado->descripcion.' ha sido creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        debug("deshabilitados");
        $dehabilitado=true;
        $estados= EstadoCivil::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.estadoCivil.index',compact('estados','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EstadoCivil $estado)
    {
        return view('admin.estadoCivil.edit', compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstadoCivil $estado)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $estado->descripcion=$request->descripcion;
        
        $estado->save();

        return redirect()->route('estado.index')->with('flash', 'El estado civil '.$estado->descripcion.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstadoCivil $estado)
    {
        $estado->delete();
        return redirect()->route('estado.index')->with('flash', 'El estado civil '.$estado->descripcion.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        EstadoCivil::withTrashed()->find($id)->restore();
        return redirect()->route('estado.index')->with('flash', 'El estado civil ha sido restaurado.');;
    }
}
