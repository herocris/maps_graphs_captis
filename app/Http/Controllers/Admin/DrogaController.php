<?php

namespace App\Http\Controllers\Admin;

use App\Models\Droga;
use App\Models\TipoDroga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrogaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver drogas'])->only('index'); 
        $this->middleware(['permission:crear droga'])->only(['create','store']);
        $this->middleware(['permission:editar droga'])->only(['edit','update']);
        $this->middleware(['permission:borrar droga'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $drogas= Droga::orderBy('created_at', 'DESC')->get();
        //$tipoDrogas= TipoDroga::withTrashed()->get();
        $tipoDrogas= TipoDroga::all();

        //dd($tipoDrogas);

        return view('admin.droga.index',compact('drogas','tipoDrogas','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $droga = new Droga;
        $tipoDrogas= TipoDroga::all();
        return view('admin.droga.create', compact('droga','tipoDrogas'));
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
            'descripcion'=> 'required|unique:drogas',
            'tipo_droga_id'=> 'required',
        ];

        $messages = [
            'tipo_droga_id.required' => 'El tipo de droga es requerido'
        ];

        $this->validate($request, $rules, $messages);

        $droga=new Droga;
        $droga->descripcion=$request->descripcion;
        $droga->tipo_droga_id=$request->tipo_droga_id;
        $droga->save();

        return redirect()->route('droga.index')->with('flash', 'La droga '.$droga->descripcion.' ha sido creada.');
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
        $drogas= Droga::onlyTrashed()->orderBy('created_at', 'DESC')->get();
        //$tipoDrogas= TipoDroga::withTrashed()->get();
        $tipoDrogas= TipoDroga::all();

        //dd($tipoDrogas);

        return view('admin.droga.index',compact('drogas','tipoDrogas','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Droga $droga)
    {
        //dd($parametro);
        $tipoDrogas= TipoDroga::all();
        //dd($tipoDrogas);
        return view('admin.droga.edit', compact('droga','tipoDrogas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Droga $droga)
    {
        $rules = [
            'descripcion'=> 'required',
            'tipo_droga_id'=> 'required',
        ];

            $this->validate($request, $rules);
            //dd($droga->isDirty());
            $droga->descripcion=$request->descripcion;
            $droga->tipo_droga_id=$request->tipo_droga_id;
            //dd($droga->isDirty());
            $droga->save();

            return redirect()->route('droga.index')->with('flash', 'La droga '.$droga->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Droga $droga)
    {
        $droga->delete();
        return redirect()->route('droga.index')->with('flash', 'La droga '.$droga->descripcion.' ha sido eliminada.');
    }

    public function restaurar($id)
    {
        Droga::withTrashed()->find($id)->restore();
        return redirect()->route('droga.index')->with('flash', 'La droga ha sido restaurada.');
    }
}
