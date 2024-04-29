<?php

namespace App\Http\Controllers\Admin;

use App\Models\Institucion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver instituciones'])->only('index'); 
        $this->middleware(['permission:crear institucion'])->only(['create','store']);
        $this->middleware(['permission:editar institucion'])->only(['edit','update']);
        $this->middleware(['permission:borrar institucion'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $instituciones= Institucion::orderBy('created_at', 'DESC')->get();
        return view('admin.institucion.index',compact('instituciones','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institucion = new Institucion;
        return view('admin.institucion.create', compact('institucion'));
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
            'nombre'=> 'required|unique:institucions',
            'contacto'=> 'required',
            'telefono'=> 'required|regex:/^\d{4}-\d{4}\b$/',
        ];

        $this->validate($request, $rules);

        $institucion=new Institucion;
        $institucion->nombre=$request->nombre;
        $institucion->contacto=$request->contacto;
        $institucion->telefono=$request->telefono;
        $institucion->save();

        return redirect()->route('institucion.index')->with('flash', 'La institución '.$institucion->nombre.' ha sido creada.');
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
        $instituciones= Institucion::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.institucion.index',compact('instituciones','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Institucion $institucion)
    {
        return view('admin.institucion.edit', compact('institucion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institucion $institucion)
    {
        $rules = [
            'nombre'=> 'required',
            'contacto'=> 'required',
            'telefono'=> 'required|regex:/^\d{4}-\d{4}\b$/',
        ];
        $messages = [
            'telefono.regex' => 'El formato de telefono debe ser 0000-0000'
        ];

        $this->validate($request, $rules, $messages);

        $institucion->nombre=$request->nombre;
        $institucion->contacto=$request->contacto;
        $institucion->telefono=$request->telefono;
        $institucion->save();

        return redirect()->route('institucion.index')->with('flash', 'La institución '.$institucion->nombre.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institucion $institucion)
    {
        $institucion->delete();
        return redirect()->route('institucion.index')->with('flash', 'La institución '.$institucion->nombre.' ha sido eliminada.');;
    }

    public function restaurar($id)
    {
        Institucion::withTrashed()->find($id)->restore();
        return redirect()->route('institucion.index')->with('flash', 'La institución ha sido restaurada.');;
    }
}
