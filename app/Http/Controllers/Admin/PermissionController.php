<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver permisos'])->only('index'); 
        $this->middleware(['permission:crear permisos'])->only(['create','store']);
        $this->middleware(['permission:editar permisos'])->only(['edit','update']);
        $this->middleware(['permission:borrar permisos'])->only('destroy');
    }
    public function index()
    {
        $dehabilitado=true;
        $permissions=Permission::orderBy('created_at', 'DESC')->get();
        return view('admin.permission.index',compact('permissions','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission=new Permission;
        return view('admin.permission.create',compact('permission'));
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
            'name'=> 'required|unique:permissions,name',
        ];
        $messages = [
            'name.unique' => 'El permiso '.$request->name.' ya ha sido registrado'
        ];
        
        $this->validate($request, $rules, $messages);
        // $data=['name' => $request->name, 'guard_name' => 'web'];
        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        
        return redirect()->route('permission.index')->with('flash', 'El permiso '.$request->name.' ha sido creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dehabilitado=false;
        $permissions=Permission::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.permission.index',compact('permissions','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $rules = [
            'name'=> 'required',
        ];
        
        $this->validate($request, $rules);
        $data=['name' => $request->name, 'guard_name' => 'web'];
        $permission->update($data);
        
        return redirect()->route('permission.index')->with('flash', 'El permiso '.$permission->name.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission )
    {
        //dd("llego aqui");
        $permission->delete();
        return redirect()->route('permission.index')->with('flash', 'El permiso '.$permission->name.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        Permission::withTrashed()->find($id)->restore();
        return redirect()->route('permission.index')->with('flash', 'El permiso ha sido restaurado.');;
    }
}
