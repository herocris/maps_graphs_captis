<?php

namespace App\Http\Controllers\Admin;

use App\Models\PresentacionDroga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresentacionDrogaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver presentaciones de droga'])->only('index'); //auth:sanctum','verifieds
        $this->middleware(['permission:crear presentaciones de droga'])->only(['create','store']);
        $this->middleware(['permission:editar presentaciones de droga'])->only(['edit','update']);
        $this->middleware(['permission:borrar presentaciones de droga'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $presentaciones= PresentacionDroga::orderBy('created_at', 'DESC')->get();
        return view('admin.presentaciondroga.index',compact('presentaciones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd("hola");
        $presentaciondroga = new PresentacionDroga;
        return view('admin.presentaciondroga.create', compact('presentaciondroga'));
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
            'descripcion'=> 'required|unique:presentacion_drogas',
        ];

        $this->validate($request, $rules);

        $presentacion=new PresentacionDroga;
        $presentacion->descripcion=$request->descripcion;
        $presentacion->save();

        return redirect()->route('presentaciondroga.index')->with('flash', 'La presentación de droga '.$presentacion->descripcion.' ha sido creada.');
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
        $presentaciones= PresentacionDroga::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.presentaciondroga.index',compact('presentaciones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PresentacionDroga $presentaciondroga)
    {
        //dd($presentaciondroga);
        return view('admin.presentaciondroga.edit', compact('presentaciondroga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PresentacionDroga $presentaciondroga)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $presentaciondroga->descripcion=$request->descripcion;
        $presentaciondroga->save();

        return redirect()->route('presentaciondroga.index')->with('flash', 'La presentación de droga '.$presentaciondroga->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresentacionDroga $presentaciondroga)
    {
        $presentaciondroga->delete();
        return redirect()->route('presentaciondroga.index')->with('flash', 'La presentación de droga '.$presentaciondroga->descripcion.' ha sido eliminada.');
    }

    public function restaurar($id)
    {
        PresentacionDroga::withTrashed()->find($id)->restore();
        return redirect()->route('presentaciondroga.index')->with('flash', 'La presentación de droga ha sido restaurada.');;
    }
}
