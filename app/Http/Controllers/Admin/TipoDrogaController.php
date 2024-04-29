<?php

namespace App\Http\Controllers\Admin;

use App\Models\TipoDroga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoDrogaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver tipo de droga'])->only('index'); //auth:sanctum','verifieds
        $this->middleware(['permission:crear tipo de droga'])->only(['create','store']);
        $this->middleware(['permission:editar tipo de droga'])->only(['edit','update']);
        $this->middleware(['permission:borrar tipo de droga'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $tipo_drogas= TipoDroga::orderBy('created_at', 'DESC')->get();
        return view('admin.tipodroga.index',compact('tipo_drogas','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipodroga = new TipoDroga;
        return view('admin.tipodroga.create', compact('tipodroga'));
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
            'descripcion'=> 'required|unique:tipo_drogas',
        ];

        $this->validate($request, $rules);

        $tipo_droga=new TipoDroga;
        $tipo_droga->descripcion=$request->descripcion;
        $tipo_droga->save();

        return redirect()->route('tipodroga.index')->with('flash', 'El tipo de droga '.$tipo_droga->descripcion.' ha sido creado.');
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
        $tipo_drogas= TipoDroga::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.tipodroga.index',compact('tipo_drogas','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDroga $tipodroga)
    {
        //dd($tipodroga);
        return view('admin.tipodroga.edit', compact('tipodroga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoDroga $tipodroga)
    {
        //dd($request);
            $rules = [
                'descripcion'=> 'required',
            ];

            $this->validate($request, $rules);

            $tipodroga->descripcion=$request->descripcion;
            
            $tipodroga->save();

            return redirect()->route('tipodroga.index')->with('flash', 'El tipo de droga ha '.$tipodroga->descripcion.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoDroga $tipodroga)
    {
        $tipodroga->delete();
        return redirect()->route('tipodroga.index')->with('flash', 'El tipo de droga ha '.$tipodroga->descripcion.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        TipoDroga::withTrashed()->find($id)->restore();
        return redirect()->route('tipodroga.index')->with('flash', 'El tipo de droga ha sido restaurado.');
    }
}
