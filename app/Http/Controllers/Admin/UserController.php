<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Institucion;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver usuarios'])->only('index'); //auth:sanctum','verifieds
        $this->middleware(['permission:crear usuarios'])->only(['create','store']);
        $this->middleware(['permission:editar usuarios'])->only(['edit','update']);
        $this->middleware(['permission:borrar usuarios'])->only('destroy');
    }

    public function index()
    {//dd("sfsdf");
        $dehabilitado=false;
        
        $users= User::where('id','!=',Auth::user()->id)->where('id','!=',1)->orderBy('created_at', 'DESC')->get();
        //debug($users);
        return view('admin.user.index',compact('users','dehabilitado'));
        //return "prueba";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        $roles = Role::with('permissions')->where('id','!=','17')->get();
        $instituciones= Institucion::all();
        //$permisos = Permission::pluck('name','id');
        $permisos = Permission::all();
        return view('admin.user.create', compact('roles','permisos','user','instituciones'));
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
            'name'=> 'required',
            'email'=> 'required|unique:users,email|email:dns',
            'institucion_id'=> 'required',
            'password'=> ['required',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'password_confirmation' => 'required|same:password',
        ];

        $messages = [
            'password_confirmation.same' => 'Ambos campos deben coincidir'
        ];

        $this->validate($request, $rules, $messages);
        

        $usuario=new User;
        $usuario->name=$request->name;
        $usuario->institucion_id=$request->institucion_id;
        $usuario->oscuro=0;
        $usuario->email=$request->email;
        $usuario->password=Hash::make($request->password);
        $usuario->save();

        
        

        if ($request->filled('roles')) {
            $usuario->assignRole($request->roles);

            $lista=collect();
            foreach ($request->roles as $rol_) {
                $nrol=Role::find($rol_);
                $lista->push($nrol->name);
            }
                
            activity()
            ->causedBy(Auth::user())
            ->performedOn($usuario)
            ->withProperties(['attributes' => ['role_id' => implode(", ",$request->roles)]])
            ->event('creado')
            ->useLog(Auth::user()->name)
            ->log('Se le ha asignado el rol de: '.implode(", ",$lista->toArray())." al usuario con id: ".$usuario->id );
        }
        //dd($request);
        if ($request->permisos!=null) {
            //$usuario->givePermissionTo($request->permisos);
            $usuario->syncPermissions(explode(",", $request->permisos));
            $listap=collect();
            
            foreach (explode(",", $request->permisos) as $per_) {
                $nper=Permission::find($per_);
                $listap->push($nper->name);
            }
            //dd($listap);
            activity()
            ->causedBy(Auth::user())
            ->performedOn($usuario)
            ->withProperties(['attributes' => ['permission_id' => $request->permisos]])
            ->event('creado')
            ->useLog(Auth::user()->name)
            ->log('Se le ha asignado el permiso de: '.implode(", ",$listap->toArray())." al usuario con id: ".$usuario->id );
        }


        return redirect()->route('user.index')->with('flash', 'El usuario '.$usuario->name.' ha sido creado.');
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
        $users= User::onlyTrashed()->orderBy('deleted_at', 'DESC')->get();
        return view('admin.user.index',compact('users','dehabilitado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {//dd($user->roles);
        //return view('admin.user.edit', compact('user'));
        $instituciones= Institucion::all();
        $roles = Role::with('permissions')->where('id','!=','17')->get();
        //$permisos = Permission::pluck('name','id');
        $permisos = Permission::all();
        //$permisos = $user->;
        return view('admin.user.edit', compact('user','roles','permisos','instituciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //dd($request);
        
        if ($request->password!="") {
            $rules = [
                'name'=> 'required',
                'email'=> 'required|email',
                'institucion_id'=> 'required',
                'password'=> ['required',
                    Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
                'password_confirmation' => 'required|same:password',
            ];
            $user->password=Hash::make($request->password);
        }else{
            $rules = [
                'name'=> 'required',
                'email'=> 'required|email:dns',
            ];
        }
        

        $messages = [
            'password_confirmation.same' => 'Ambos campos deben coincidir'
        ];

        $this->validate($request, $rules, $messages);

        $user->name=$request->name;
        $user->email=$request->email;
        $user->institucion_id=$request->institucion_id;
        $user->save();

        return redirect()->route('user.index')->with('flash', 'El usuario '.$user->name.' ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //dd("llega a borrar");
        $user->delete();
        return redirect()->route('user.index')->with('flash', 'El usuario '.$user->name.' ha sido eliminado.');
    }

    public function restaurar($id)
    {
        User::withTrashed()->find($id)->restore();
        return redirect()->route('user.index')->with('flash', 'El usuario ha sido restaurado.');
    }

    
    
}
