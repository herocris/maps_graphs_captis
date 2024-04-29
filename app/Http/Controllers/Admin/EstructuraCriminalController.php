<?php

namespace App\Http\Controllers\Admin;

use App\Models\EstructuraCriminal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstructuraCriminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver estructuras'])->only('index'); 
        $this->middleware(['permission:crear estructura'])->only(['create','store']);
        $this->middleware(['permission:editar estructura'])->only(['edit','update']);
        $this->middleware(['permission:borrar estructura'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $estructuras= EstructuraCriminal::orderBy('created_at', 'DESC')->get();
        return view('admin.estructura.index',compact('estructuras','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("hola");
        $estructura = new EstructuraCriminal;
        return view('admin.estructura.create', compact('estructura'));
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
            'descripcion'=> 'required|unique:estructura_criminals',
        ];

        $this->validate($request, $rules);

        $estructura=new EstructuraCriminal;
        $estructura->descripcion=$request->descripcion;
        $estructura->save();

        return redirect()->route('estructura.index')->with('flash', 'La estructura criminal '.$estructura->descripcion.' ha sido creada.');
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
        $estructuras= EstructuraCriminal::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.estructura.index',compact('estructuras','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EstructuraCriminal $estructura)
    {
        //dd($presentacionestructura);
        return view('admin.estructura.edit', compact('estructura'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstructuraCriminal $estructura)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $estructura->descripcion=$request->descripcion;
        $estructura->save();

        return redirect()->route('estructura.index')->with('flash', 'La estructura criminal '.$estructura->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstructuraCriminal $estructura)
    {
        $estructura->delete();
        return redirect()->route('estructura.index')->with('flash', 'La estructura criminal '.$estructura->descripcion.' ha sido eliminada.');
    }

    public function restaurar($id)
    {
        EstructuraCriminal::withTrashed()->find($id)->restore();
        return redirect()->route('estructura.index')->with('flash', 'La estructura criminal ha sido restaurada.');
    }
}
