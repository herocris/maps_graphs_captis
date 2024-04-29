<?php

namespace App\Http\Controllers\Admin;

use App\Models\Precursor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrecursorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver precursor'])->only('index'); 
        $this->middleware(['permission:crear precursor'])->only(['create','store']);
        $this->middleware(['permission:editar precursor'])->only(['edit','update']);
        $this->middleware(['permission:borrar precursor'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=false;
        $precursores= Precursor::orderBy('created_at', 'DESC')->get();
        return view('admin.precursor.index',compact('precursores','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $precursor = new Precursor;
        return view('admin.precursor.create', compact('precursor'));
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
            'descripcion'=> 'required|unique:precursors',
        ];

        $this->validate($request, $rules);

        $precursor=new Precursor;
        $precursor->descripcion=$request->descripcion;
        $precursor->save();

        return redirect()->route('precursor.index')->with('flash', 'El precursor '.$precursor->descripcion.' ha sido creado.');
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
        $precursores= Precursor::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.precursor.index',compact('precursores','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Precursor $precursor)
    {
        return view('admin.precursor.edit', compact('precursor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Precursor $precursor)
    {
        $rules = [
            'descripcion'=> 'required',
        ];

        $this->validate($request, $rules);

        $precursor->descripcion=$request->descripcion;
        $precursor->save();

        return redirect()->route('precursor.index')->with('flash', 'El precursor '.$precursor->descripcion.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Precursor $precursor)
    {
        $precursor->delete();
        return redirect()->route('precursor.index')->with('flash', 'El precursor '.$precursor->descripcion.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        Precursor::withTrashed()->find($id)->restore();
        return redirect()->route('precursor.index')->with('flash', 'El precursor ha sido restaurado.');
    }
}
