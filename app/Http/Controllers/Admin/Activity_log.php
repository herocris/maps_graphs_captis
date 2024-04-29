<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Institucion;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;

class Activity_log extends Controller
{
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver bitacora'])->only('index'); //auth:sanctum','verifieds
    }
    public function index(Request $request)
    {
        ///////////////////////////////logica nueva/////////////////////////////////////////////////
        if(count($request->all()) > 0) {////////////////////si el request viene vacío simplemente llena la tabla con la pagina inicial; y si no ejecuta los filtros
            ///debug($request);
            $draw = $request->get('draw'); // Internal use
            $start = $request->get("start"); // where to start next records for pagination
            $rowPerPage	= $request->get("length"); // How many recods needed per page for pagination
            $orderArray = $request->get('order');
            $columnNameArray = $request->get('columns'); // It will give us columns array            
            $searchArray = $request->get('search');
            $columnIndex = $orderArray[0]['column'];  // This will let us know, which column index should be sorted 0 = id, 1 = name, 2 = email , 3 = created_at
            $columnName = $columnNameArray[$columnIndex]['data']; // Here we will get column name, Base on the index we get
            $columnSortOrder = $orderArray[0]['dir']; // This will get us order direction(ASC/DESC)
            $searchValue = $searchArray['value']; // This is search value 

            $actividades=DB::table('activity_log')//////////////////////consulta principal//////////////////
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->join('institucions', 'users.institucion_id', '=', 'institucions.id')
            ->select('activity_log.log_name',
                    'institucions.nombre AS institucion',
                    'activity_log.description',
                    'activity_log.properties',
                    'activity_log.created_at AS fecha');
                    
            if($searchValue!=null){
                $actividades=$actividades->where(function ($query) use ($searchValue) {
                    $query->where('log_name','like','%'. $searchValue.'%')
                    ->orWhere('description','like','%'. $searchValue.'%')
                    ->orWhere('properties','like','%'. $searchValue.'%')
                    ->orWhere('activity_log.created_at','like','%'. $searchValue.'%')
                    ->orWhere('institucions.nombre','like','%'. $searchValue.'%');
                });
            }
            
                
            $total=$actividades->count();

            $totalFilter=$actividades;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'log_name':
                            $totalFilter= $totalFilter->where('log_name','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'institucion':
                            $totalFilter= $totalFilter->where('institucions.nombre','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'description':
                            $totalFilter= $totalFilter->where('description','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'properties':
                            //debug("llega");
                            $totalFilter= $totalFilter->where('properties','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'fecha':
                            debug("llega");
                            $totalFilter= $totalFilter->where('activity_log.created_at','like','%'. $columna['search']['value'].'%');
                            break;                  
                        default:
                            //$totalFilter= $totalFilter->where($columna['data'],'like','%'. $columna['search']['value'].'%');
                            break;
                    }
                }
            }
            ///////////para filtra por fechas
            if ($request->get("fec_ini")!=null && $request->get("fec_fin")!=null) {
                $fechafinal=$request->get("fec_fin"). " 23:59:59";
                $totalFilter= $totalFilter->whereBetween('activity_log.created_at', [$request->get("fec_ini"), $fechafinal]);
            }

            $imprimir="nada que imprimir";
            if ($request->get("imprimir")=="true") {
                debug("imprime");
                $paraimp= clone $totalFilter;
                $imprimir=$paraimp->orderByDesc('fecha')->get();
            }
            

            $filtradototal=$totalFilter->count();///------------
            debug($start);
            
            $totalFilter=$totalFilter->skip($start)->take($rowPerPage);

            $totalFilter=$totalFilter->orderBy($columnName, $columnSortOrder);

            $totalFilter=$totalFilter->get();
            
            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $total,
                "recordsFiltered" => $filtradototal,
                "data" => $totalFilter,
                "imprimir" => $imprimir,
            );

            return response()->json($response);
        }else{
            return view('admin.bitacora.index');
        }
        //////////////////////////////////////////////fin logica nueva//////////////////////////////////////////////////////

        ///////////////////logica anterior////////////////////////////////////////////////////
        // $actividades=DB::table('activity_log')
        // ->join('users', 'activity_log.causer_id', '=', 'users.id')
        // ->join('institucions', 'users.institucion_id', '=', 'institucions.id')
        // ->select('activity_log.log_name',
        //         //'users.institucion_id AS institucion',
        //         'institucions.nombre AS institucion',
        //         'activity_log.description',
        //         'activity_log.properties',
        //         'activity_log.created_at')
        // ->orderBy('created_at', 'desc')->get();
        
        // $actividades= Activity::orderBy('created_at','DESC')->get();
        //     foreach ($actividades as $actividad) {
        //         $callback = function($key, $value) {
        //             return "$key : $value";
        //         };
        //             if (array_key_exists ('old',$actividad->properties->toArray()) && array_key_exists ('attributes',$actividad->properties->toArray())) {
        //                 $res1 = array_map($callback, array_keys($actividad->properties['old']), $actividad->properties['old']);
        //                 $res2 = array_map($callback, array_keys($actividad->properties['attributes']), $actividad->properties['attributes']);
        //                 $actividad->properties="ANTERIORES= ". implode(", ",$res1)." -- NUEVOS= ". implode(", ",$res2);
        //             } else if(array_key_exists ('old',$actividad->properties->toArray())){
        //                 $res1 = array_map($callback, array_keys($actividad->properties['old']), $actividad->properties['old']);
        //                 $actividad->properties=implode(", ",$res1);
        //             } else if(array_key_exists ('attributes',$actividad->properties->toArray())) {
        //                 $res1 = array_map($callback, array_keys($actividad->properties['attributes']), $actividad->properties['attributes']);
        //                 $actividad->properties=implode(", ",$res1);
        //             } else{
        //                 $actividad->properties=[''];
        //             }
        //     }
        ///////////////////fin logica anterior////////////////////////////////////////////////////


        
        
        
        //return view('admin.bitacora.index',compact('actividades'));
    }

    public function generargrafica(Request $request){
        $actividades=DB::table('activity_log')//////////////////////consulta principal//////////////////
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->join('institucions', 'users.institucion_id', '=', 'institucions.id')
            ->select('activity_log.log_name',
                    'institucions.nombre AS institucion',
                    'institucions.id AS institucion_id',
                    'activity_log.description',
                    'activity_log.properties',
                    'activity_log.event',
                    'activity_log.created_at AS fecha',
                    'activity_log.causer_id')
            ->whereIn('activity_log.event', $request->acciones)
            ->whereNot('activity_log.description','like','%Se ha generado una grafica%')
            ->whereNot('activity_log.description','like','%Se ha generado un mapa%')
            ->whereBetween('activity_log.created_at', [$request->fecha_ini, $request->fecha_fin." 23:59:59"]);
            
        if ($request->parametro=="instituciones") {///////////////////comprobando si la grafica es en base a instituciones
            $actividades->whereIn('institucions.id', $request->instituciones);
        } 
        if ($request->parametro=="usuarios") {///////////////////comprobando si la grafica es en base a usuarios
            $actividades->where('institucions.id', $request->instituciones);
            $actividades->whereIn('activity_log.causer_id', $request->usuarios);
        }
        $actividades=$actividades->get();///////////////////////ejecutando la consulta

        $datos=['datasets'=> [],'labels'=>[$request->fecha_ini.' al '.$request->fecha_fin]];////////////////creando datos para grafica

        $insti=DB::table('institucions')->select('institucions.id','institucions.nombre')->whereNull('deleted_at')->get();///////////////obteniendo id y nombre de instituciones
        $users_=DB::table('users')->select('users.id','users.name')->whereNull('deleted_at')->get();///////////////obteniendo id y nombre de usuarios

        if ($request->parametro=="instituciones") {/////////////////////llenando datos de grafica en base a instituciones
            foreach ($request->instituciones as $institucion) {
                $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
                $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
                $intso = $insti->first(function ($value) use ($institucion){
                    return $value->id == $institucion;
                });
                $cantidad=$actividades->where('institucion', $intso->nombre)->count();
                $datos['datasets'][]=['label'=> $intso->nombre, 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[$cantidad], 'borderWidth'=> 1];
            }
        }

        if ($request->parametro=="usuarios") {/////////////////////llenando datos de grafica en base a usuarios
            foreach ($request->usuarios as $usuario) {
                $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
                $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
                $user_ = $users_->first(function ($value) use ($usuario){
                    return $value->id == $usuario;
                });
                $cantidad=$actividades->where('causer_id', $user_->id)->count();
                $datos['datasets'][]=['label'=> $user_->name, 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[$cantidad], 'borderWidth'=> 1];
            }
        }

        return response()->json(['datos'=>$datos]);
    }

    public function mostrar(){
        $usuarios= User::all();
        $instituciones= Institucion::all();

        return view('admin.bitacora.grafica_estadistica', compact('usuarios','instituciones'));  
    }
}
