<?php

namespace App\Http\Controllers\Admin;

use App\Models\Identificacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IdentificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver identificaciones'])->only('index'); 
        $this->middleware(['permission:crear identificacion'])->only(['create','store']);
        $this->middleware(['permission:editar identificacion'])->only(['edit','update']);
        $this->middleware(['permission:borrar identificacion'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $identificaciones= Identificacion::orderBy('created_at', 'DESC')->get();
        return view('admin.identificacion.index',compact('identificaciones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("hola");
        $identificacion = new Identificacion;
        return view('admin.identificacion.create', compact('identificacion'));
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
            'descripcion'=> 'required|unique:identificacions',
        ];

        $this->validate($request, $rules);

        $identificacion=new Identificacion;
        $identificacion->descripcion=$request->descripcion;
        $identificacion->save();

        return redirect()->route('identificacion.index')->with('flash', 'La identificación '.$identificacion->descripcion.' ha sido creada.');
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
        $identificaciones= Identificacion::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.identificacion.index',compact('identificaciones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Identificacion $identificacion)
    {
        //dd($presentaciondroga);
        return view('admin.identificacion.edit', compact('identificacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Identificacion $identificacion)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $identificacion->descripcion=$request->descripcion;
        $identificacion->save();

        return redirect()->route('identificacion.index')->with('flash', 'La identificación '.$identificacion->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Identificacion $identificacion)
    {
        $identificacion->delete();
        return redirect()->route('identificacion.index')->with('flash', 'La identificación '.$identificacion->descripcion.' ha sido eliminada.');;
    }

    public function restaurar($id)
    {
        Identificacion::withTrashed()->find($id)->restore();
        return redirect()->route('identificacion.index')->with('flash', 'La identificación ha sido restaurada.');;
    }
}
