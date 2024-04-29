<?php

namespace App\Http\Controllers\Admin;

use App\Models\PresentacionPrecursor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresentacionPrecursorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        
        $this->middleware(['permission:ver presentaciones de precursor'])->only('index'); 
        $this->middleware(['permission:crear presentaciones de precursor'])->only(['create','store']);
        $this->middleware(['permission:editar presentaciones de precursor'])->only(['edit','update']);
        $this->middleware(['permission:borrar presentaciones de precursor'])->only('destroy');
    }
    public function index()
    {
        debug("habilitados");
        $dehabilitado=false;
        $presentaciones= PresentacionPrecursor::orderBy('created_at', 'DESC')->get();
        return view('admin.presentacionprecursor.index',compact('presentaciones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("hola");
        $presentacionprecursor = new PresentacionPrecursor;
        return view('admin.presentacionprecursor.create', compact('presentacionprecursor'));
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
            'descripcion'=> 'required|unique:presentacion_precursors',
        ];

        $this->validate($request, $rules);

        $presentacionprecursor=new PresentacionPrecursor;
        $presentacionprecursor->descripcion=$request->descripcion;
        $presentacionprecursor->save();

        return redirect()->route('presentacionprecursor.index')->with('flash', 'La presentación de precursor '.$presentacionprecursor->descripcion.' ha sido creada.');
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
        $presentaciones= PresentacionPrecursor::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.presentacionprecursor.index',compact('presentaciones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PresentacionPrecursor $presentacionprecursor)
    {
        //dd($presentaciondroga);
        return view('admin.presentacionprecursor.edit', compact('presentacionprecursor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PresentacionPrecursor $presentacionprecursor)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $presentacionprecursor->descripcion=$request->descripcion;
        $presentacionprecursor->save();

        return redirect()->route('presentacionprecursor.index')->with('flash', 'La presentación de precursor '.$presentacionprecursor->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresentacionPrecursor $presentacionprecursor)
    {
        $presentacionprecursor->delete();
        return redirect()->route('presentacionprecursor.index')->with('flash', 'La presentación de precursor '.$presentacionprecursor->descripcion.' ha sido eliminada.');;
    }

    public function restaurar($id)
    {
        PresentacionPrecursor::withTrashed()->find($id)->restore();
        return redirect()->route('presentacionprecursor.index')->with('flash', 'La presentación de precursor ha sido restaurada.');;
    }
}
