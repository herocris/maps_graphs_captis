<?php

namespace App\Http\Controllers\Admin;

use App\Models\Presentacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presentaciones= Presentacion::all();
        return view('admin.presentacion.index',compact('presentaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $presentacion = new Presentacion;
        return view('admin.presentacion.create', compact('presentacion'));
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
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $presentacion=new Presentacion;
        $presentacion->descripcion=$request->descripcion;
        $presentacion->save();

        return redirect()->route('presentacion.index')->with('flash', 'Tu publicación ha sido eliminada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Presentacion $presentacion)
    {
        return view('admin.presentacion.edit', compact('presentacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Presentacion $presentacion)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $presentacion->descripcion=$request->descripcion;
        $presentacion->save();

        return redirect()->route('presentacion.index')->with('flash', 'Tu publicación ha sido eliminada.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Presentacion $presentacion)
    {
        $presentacion->delete();
        return redirect()->route('presentacion.index');
    }
}
