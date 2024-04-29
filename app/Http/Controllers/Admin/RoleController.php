<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver roles'])->only('index'); 
        $this->middleware(['permission:crear roles'])->only(['create','store']);
        $this->middleware(['permission:editar roles'])->only(['edit','update']);
        $this->middleware(['permission:borrar roles'])->only('destroy');
    }

    public function index()
    {
        Auth::user()->can('un permiso');
        //dd(Auth::user()->can('primer permiso'));
        $dehabilitado=true;
        $roles=Role::orderBy('created_at', 'DESC')->get();
        return view('admin.rol.index', compact('roles','dehabilitado'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role;
        //$permisos = Permission::pluck('name','id');
        $permisos = Permission::all();
        return view('admin.rol.create', compact('permisos','role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rol=new Role;
        // $rol->name=$request->name;
        // $rol->save();

        $rules = [
            'name'=> 'required|unique:roles,name',
        ];
        $messages = [
            'name.unique' => 'El rol '.$request->name.' ya ha sido registrado'
        ];

        $this->validate($request, $rules, $messages);
        
        $role=Role::create(['name'=>$request->name,'guard_name'=>'web']);
        //dd($request->permisos);
        $role->syncPermissions(explode(",", $request->permisos));

        return redirect()->route('role.index')->with('flash', 'El rol '.$role->name.' ha sido creado.');
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
        $roles=Role::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.rol.index', compact('roles','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //dd($role->permissions->pluck('id')->toArray());
        //$permisos = Permission::pluck('name','id');
        $permisos = Permission::all();
        //$permisoss = $role->permissions;
        //dd($permisoss);
        return view('admin.rol.edit', compact('role','permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //dd($request);
        $rules = [
            'name'=> 'required',
        ];
        
        $this->validate($request, $rules);
        $role->name=$request->name;
        $role->guard_name='web';
        $role->save();
        //dd($request->permisos);
        if ($request->has('permisos')) {
            
            $role->syncPermissions(explode(",", $request->permisos));

            $user=Auth::user();
        //$per_act=Role::find($this->attributes['id']);
        $permissions=explode(",", $request->permisos);
            activity()
                ->causedBy($user)
                //->performedOn("dlmflk")
                ->withProperties(['attributes' => ['role_id' => $request->permisos]])
                ->event('actualizado')
                ->useLog($user->name)
                ->log('Se ha actualizado el rol de: '.$role->name . " con los permisos: " .$request->permisos);
        }else{
            $role->revokePermissionTo($request->permisos);
        }
        
        
        return redirect()->route('role.index')->with('flash', 'El rol '.$role->name.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index')->with('flash', 'El rol '.$role->name.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        Role::withTrashed()->find($id)->restore();
        return redirect()->route('role.index')->with('flash', 'El rol ha sido restaurado.');
    }
}
