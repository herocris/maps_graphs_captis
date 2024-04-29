<?php

namespace App\Http\Controllers\Admin;

use App\Models\Arma;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver armas'])->only('index'); 
        $this->middleware(['permission:crear arma'])->only(['create','store']);
        $this->middleware(['permission:editar arma'])->only(['edit','update']);
        $this->middleware(['permission:borrar arma'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $armas= Arma::orderBy('created_at', 'DESC')->get();
        return view('admin.arma.index',compact('armas','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arma = new Arma;
        return view('admin.arma.create', compact('arma'));
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
            'descripcion'=> 'required|unique:armas',
        ];

        $this->validate($request, $rules);

        $arma=new Arma;
        $arma->descripcion=$request->descripcion;
        $arma->save();

        return redirect()->route('arma.index')->with('flash', 'El arma'.$arma->descripcion.' ha sido creada.');
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
        $armas= Arma::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.arma.index',compact('armas','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Arma $arma)
    {
        return view('admin.arma.edit', compact('arma'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arma $arma)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $arma->descripcion=$request->descripcion;
        
        $arma->save();

        return redirect()->route('arma.index')->with('flash', 'El arma'.$arma->descripcion.' ha sido editada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arma $arma)
    {
        $arma->delete();
        return redirect()->route('arma.index')->with('flash', 'El arma'.$arma->descripcion.' ha sido eliminada.');
    }

    public function restaurar($id)
    {
        Arma::withTrashed()->find($id)->restore();
        return redirect()->route('arma.index')->with('flash', 'El arma ha sido restaurada.');;
    }
}
