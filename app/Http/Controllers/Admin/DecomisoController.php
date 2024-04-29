<?php

namespace App\Http\Controllers\Admin;

use App\Events\DecomisoBitacorae;
use App\Models\Decomiso;
use App\Models\TipoDroga;
use App\Models\Departamento;
use App\Models\Pais;
use App\Models\Municipio;
//use App\Models\Bitacora_decomiso;
use App\Models\PresentacionDroga;
use App\Models\Droga;
use App\Models\Precursor;
use App\Models\PresentacionPrecursor;
use App\Models\Ocupacion;
use App\Models\Arma;
use App\Models\TipoMunicion;
use App\Models\EstructuraCriminal;
use App\Models\Identificacion;
use App\Models\Institucion;
use App\Models\EstadoCivil;
use App\Models\decomiso_droga;
use App\Models\decomiso_detenido;
use App\Models\decomiso_transporte;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\UrlGenerator;

class DecomisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function __construct()
    {
        //asingación de middlewares a acciones especificas mediante permisos
        $this->middleware(['permission:ver decomisos'])->only('index');
        $this->middleware(['permission:importar data'])->only('ver_importar');
        $this->middleware(['permission:crear decomiso'])->only(['create','store']);
        $this->middleware(['permission:editar decomisos'])->only(['edit','update']);
        //$this->middleware(['permission:visualizar subdecomisos'])->only('edit');
        $this->middleware(['permission:borrar decomisos'])->only('destroy');
    }
    //acción para desplegar la vista de todos los decomisos
    public function index(Request $request)
    {
        if (count($request->all())) {
            //debug($request);
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

            //debug($columnNameArray);
            $desabilitados=$request->get('desabilitados')=="true"?false:true;
            //$desabilitados=false;
            //debug($desabilitados);


            //$tuusi="marihu";

            $decomisos=DB::table('decomisos')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
            ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
            ->join('users AS usu_cre', 'decomisos.user_create', '=', 'usu_cre.id')
            ->join('users AS usu_upd', 'decomisos.user_update', '=', 'usu_upd.id')
            //->join('decomiso_institucion', 'decomisos.id', '=', 'decomiso_institucion.decomiso_id', 'left outer')
            // ->join('institucions AS intit', 'decomiso_institucion.institucion_id', '=', 'intit.id')

            // ->join('decomiso_institucion AS tabla1', 'decomisos.id', '=', 'decomiso_institucion.decomiso_id')
            // ->join('decomiso_institucion AS tabla2', 'institucions.id', '=', 'decomiso_institucion.institucion_id')
            //->join('users AS users2', 'bitacora_decomiso.user_id_update', '=', 'users.id')
            //->join('activity_log', 'bitacora_decomiso.user_id_create', '=', 'users.id')
            // ->join('decomiso_droga', 'decomisos.id', '=', 'decomiso_droga.decomiso_id')
            // ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
            //->where('drogas.descripcion','like','%'. $tuusi.'%')

            ->select('decomisos.id',
                    'decomisos.fecha',
                    'decomisos.observacion',
                    'decomisos.direccion',
                    'municipios.nombre AS municipio',
                    'departamentos.nombre AS departamento',
                    'institucions.nombre AS institucion',
                    'decomisos.latitud',
                    'decomisos.longitud',
                    'decomisos.created_at',
                    'decomisos.deleted_at',
                    'usu_cre.name AS creador',
                    'usu_upd.name AS actualizador',
                    //'intit.nombre AS instituciones'
                    //'tabla1.decomiso_institucion'
                    //'decomisos.user_update',
                    //'uupdate.name AS actu'
                    //'bitacora_decomiso.user_id_create',
                    //'users.name AS uscr',
                    //'users2.name AS usup'

        );
        // $valor="";
        // debug($columnNameArray[6]['search']['value']);
        //     if($searchValue!=null || $columnNameArray[6]['search']['value']!=null){
        //         $usuario_=DB::table('users')->where('name','like','%'. $searchValue.'%')->first();
        //         if($usuario_!=null ){
        //             //debug($usuario_);
        //             $valor=$usuario_->id;
        //         }
        //     }
            $separado = explode("=", $searchValue);

            $esta = strpos($searchValue, '=');
            if($searchValue!=null && $esta==true){

                switch ($separado[0]) {
                    case 'droga':
                        $decomisos= $decomisos
                                    ->join('decomiso_droga', 'decomisos.id', '=', 'decomiso_droga.decomiso_id')
                                    ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
                                    ->where('drogas.descripcion','like','%'. $separado[1].'%')->distinct();
                        break;
                    case 'precursor':
                        $decomisos= $decomisos
                                    ->join('decomiso_precursor', 'decomisos.id', '=', 'decomiso_precursor.decomiso_id')
                                    ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
                                    ->where('precursors.descripcion','like','%'. $separado[1].'%')->distinct();
                        break;
                    case 'arma':
                        $decomisos= $decomisos
                                    ->join('arma_decomiso', 'decomisos.id', '=', 'arma_decomiso.decomiso_id')
                                    ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
                                    ->where('armas.descripcion','like','%'. $separado[1].'%')->distinct();
                        break;
                    case 'municion':
                        $decomisos= $decomisos
                                    ->join('decomiso_tipo_municion', 'decomisos.id', '=', 'decomiso_tipo_municion.decomiso_id')
                                    ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
                                    ->where('tipo_municions.descripcion','like','%'. $separado[1].'%')->distinct();
                        break;
                    case 'detenido':
                        $decomisos= $decomisos
                                    ->join('decomiso_detenidos', 'decomisos.id', '=', 'decomiso_detenidos.decomiso_id')
                                    ->where('decomiso_detenidos.nombre','like','%'. $separado[1].'%')
                                    ->orWhere('decomiso_detenidos.alias', 'like','%'.$separado[1]. '%')
                                    ->orWhere('decomiso_detenidos.identidad', 'like','%'.$separado[1]. '%')->distinct();
                        break;
                    case 'transporte':
                        debug($separado[0]);
                        $decomisos= $decomisos
                                    ->join('decomiso_transportes', 'decomisos.id', '=', 'decomiso_transportes.decomiso_id')
                                    ->where('decomiso_transportes.placa','like','%'. $separado[1].'%')
                                    ->orWhere('decomiso_transportes.marca','like','%'. $separado[1].'%')
                                    ->orWhere('decomiso_transportes.modelo','like','%'. $separado[1].'%')
                                    ->orWhere('decomiso_transportes.tipo','like','%'. $separado[1].'%')->distinct();
                        break;
                    case 'colaboradores':
                        debug($separado[0]);
                        $decomisos= $decomisos
                                    ->join('decomiso_institucion', 'decomisos.id', '=', 'decomiso_institucion.decomiso_id')
                                    ->join('institucions AS intit', 'decomiso_institucion.institucion_id', '=', 'intit.id')
                                    ->where('intit.nombre','like','%'. $separado[1].'%')->distinct();
                        break;
                    default:
                        //$decomisos= $decomisos->where($columna['data'],'like','%'. $columna['search']['value'].'%');
                        break;
                }
            }

            //////////////////////////filtrador del valor de la caja de busqueda////////////////////////
            if($searchValue!=null && $esta==false){
                $decomisos=$decomisos->where(function ($query) use ($searchValue) {
                    $query->where('fecha','like','%'. $searchValue.'%')
                    ->orWhere('observacion','like','%'. $searchValue.'%')
                    ->orWhere('direccion','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('institucions.nombre','like','%'. $searchValue.'%')
                    ->orWhere('usu_cre.name','like','%'. $searchValue.'%')
                    ->orWhere('usu_upd.name','like','%'. $searchValue.'%');
                });
            }
            //////////////////////////filtrador del valor decomisos habilitados o deshabilitados////////////////////////
            $decomisos->when($desabilitados, function ($query, $desabilitados) { ///condiciones para ver decomisos desabilitados
                $query->whereNotNull('decomisos.deleted_at');
            }, function ($query) {
                $query->whereNull('decomisos.deleted_at');
            });


            $total=$decomisos->count('decomisos.id');
            //debug($total);
            $totalFilter=$decomisos;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'departamento':
                            $totalFilter= $totalFilter->where('departamentos.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'municipio':
                            $totalFilter= $totalFilter->where('municipios.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'institucion':
                            $totalFilter= $totalFilter->where('institucions.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'creador':
                            $totalFilter= $totalFilter->where('usu_cre.name','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'actualizador':
                            $totalFilter= $totalFilter->where('usu_upd.name','like','%'. $columna['search']['value'].'%');
                            break;
                        // case 'colaboradores':
                        //     debug("sdfsdf");
                        //     $totalFilter= $totalFilter
                        //                 ->join('decomiso_institucion', 'decomisos.id', '=', 'decomiso_institucion.decomiso_id')
                        //                 ->join('institucions AS intit', 'decomiso_institucion.institucion_id', '=', 'intit.id')
                        //                 ->where('intit.nombre','like','%'. $columna['search']['value'].'%');
                        //                 //->addSelect('intit.nombre AS instituciones');
                        //                 //->select('intit.nombre AS instituciones');
                        //     break;
                        case 'botones':
                            break;
                        default:
                            $totalFilter= $totalFilter->where($columna['data'],'like','%'. $columna['search']['value'].'%');
                            break;
                    }
                }
            }
            /////////////para filtra por fechas
            if ($request->get("fec_ini")!=null && $request->get("fec_fin")!=null) {
                $totalFilter= $totalFilter->whereBetween('fecha', [$request->get("fec_ini"), $request->get("fec_fin")]);
            }

            $imprimir="nada que imprimir";
            if ($request->get("imprimir")=="true") {
                debug("imprime");
                $paraimp= clone $totalFilter;
                $imprimir=$paraimp->orderByDesc('fecha')->get();
            }

            debug($totalFilter);
            $filtradototal=$totalFilter->count('decomisos.id');///------------
            //debug($start);
            //$arrData=$decomisos;

            $totalFilter=$totalFilter->skip($start)->take($rowPerPage);
            if($columnName!="botones"){
                $totalFilter=$totalFilter->orderBy($columnName, $columnSortOrder);
            }

            $totalFilter=$totalFilter->get();
            debug($totalFilter);

            $decomisos_inst=DB::table('decomiso_institucion')->select('decomiso_id','institucion_id')->get();
            $instituciones_deco=DB::table('institucions')->select('id','nombre')->get();

            ////////////////para incluir nombre de colaboradores//////////////////////////
            // foreach ($totalFilter as $deco) {
            //     $inst_en_dec=$decomisos_inst->where('decomiso_id','=',$deco->id)->pluck('institucion_id');
            //     $nom_inst=[];
            //     foreach ($inst_en_dec as $instit) {
            //         $nomb=$instituciones_deco->where('id','=',$instit)->pluck('nombre')->first();
            //         array_push($nom_inst, $nomb);
            //     }
            //     $deco->instituciones=implode(", ", $nom_inst);
            // }
            debug($totalFilter);


            $user=Auth::user();

            //permisos designados a variables para no invocarlos por cada fila de la grilla de decomisos y hacer mas eficiente la carga.
            $editar_permisos=$user->can('editar decomisos');
            $borrar_permisos=$user->can('borrar decomisos');

            ////condicion para visualizar boton de restaurado en grilla de decomisos desabilitados
            if (!$desabilitados) {
                if($editar_permisos){
                    foreach ($totalFilter as $value) {
                        $value->botones="<a href='decomiso/".$value->id."/edit' class='btn btn-sm btn-info' data-toggle='tooltip' data-placement='bottom' title='Editar institución'><i class='fas fa-pencil-alt'></i></a>";
                    }
                }

                if($borrar_permisos){
                    foreach ($totalFilter as $value) {
                        $borrara='"¿Borrar?"';
                        $borrar= "<form method='POST' action=".url()->previous()."/".$value->id." style='display: inline;'>"
                                ."<input type='hidden' name='_token' value=".csrf_token()." />"
                                ."<input type='hidden' name='_method' value='DELETE'>"
                                ."<button class='btn btn-sm btn-danger' data-toggle='tooltip' data-placement='bottom' title='Borrar institución' onclick='return confirm(".$borrara.");'><i class='fas fa-times-circle'></i></button>"
                                ."</form>";
                        $value->botones=$value->botones." ".$borrar;
                    }
                }
            }else {
                foreach ($totalFilter as $value) {
                    $borrara='"¿Borrar deshabilitados?"';
                    $borrar= "<form method='GET' action=".url()->previous()."oo/".$value->id." style='display: inline;'>"
                            ."<input type='hidden' name='_token' value=".csrf_token()." />"
                            ."<button class='btn btn-sm btn-success' data-toggle='tooltip' data-placement='bottom' title='Restaurar decomiso'><i class='fas fa-trash-restore-alt'></button>"
                            ."</form>";
                    $value->botones=$borrar;
                }
            }


            $response = array(
                "draw" => intval($draw),
                "recordsTotal" => $total,
                "recordsFiltered" => $filtradototal,
                "data" => $totalFilter,
                "imprimir" => $imprimir,
            );
            //debug($response);
            return response()->json($response);
            //return view('admin.decomiso.index', compact('decomisos','dehabilitado','editar_permisos','borrar_permisos'));
        } else {
            return view('admin.decomiso.index');
        }


    }



    public function indexpaginado(Request $request)
    {
        //dd("skadl");
        if ($request->ajax()) {
            $dehabilitado=false;
            $decomisos=DB::table('decomisos')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
            ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
            ->select('decomisos.id',
                    'decomisos.fecha',
                    'decomisos.observacion',
                    'decomisos.direccion',
                    'municipios.nombre AS municipio',
                    'departamentos.nombre AS departamento',
                    'institucions.nombre AS institucion',
                    'decomisos.latitud',
                    'decomisos.longitud',
                    'decomisos.deleted_at',
                    )
            ->orderByDesc('fecha')
            ->whereNull('decomisos.deleted_at')
            ->paginate(10);
            //->simplePaginate(10);
            //->get();
            //$decomisos= Decomiso::orderByDesc('fecha')->get();

            //$tipoDrogas= TipoDroga::withTrashed()->get();
            //$tipoDrogas= TipoDroga::all();
            $user=Auth::user();
            //dd($user->can('editar decomisos'));

            //permisos designados a variables para no invocarlos por cada fila de la grilla de decomisos y hacer mas eficiente la carga.
            $editar_permisos=$user->can('editar decomisos');
            $borrar_permisos=$user->can('borrar decomisos');


            return view('admin.decomiso.paginacion', compact('decomisos','dehabilitado','editar_permisos','borrar_permisos'))->render();
            //return view('admin.decomiso.paginacion')->with('decomisos', $decomisos);
            //return response()->json(['decomisos'=>$decomisos]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //acción para desplegar la vista de creación de decomiso principal
    public function create()
    {
         $departamentos= Departamento::all();
         $municipios= Municipio::all();
         $instituciones= Institucion::all();


        //return view('admin.decomiso.create2', compact('decomiso','tipoDrogas','departamentos','paises','municipios','presentacion_drogas','presentacion_precursores','ocupaciones','estructuras','indentidades','estados','drogas','precursores','armas'));
        return view('admin.decomiso.create', compact('departamentos','municipios','instituciones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //acción para guardar en la base de datos el decomiso principal
    public function store(Request $request)
    {
        debug($request);
        $user_id=Auth::user()->id;
        $rules = [              //reglas de validación para los decomisos
            'fecha'=> 'required',
            'observacion'=> 'required',
            'direccion'=> 'required',
            'municipio_id'=> 'required',
            'inputDep'=> 'required',
            'institucion_id'=> 'required',
            'latitud'=> 'required',
            'longitud'=> 'required',
        ];

        $this->validate($request, $rules);

        $decomiso=new Decomiso;
        $decomiso->fecha=$request->fecha;
        $decomiso->observacion=$request->observacion;
        $decomiso->direccion=$request->direccion;
        $decomiso->municipio_id=$request->municipio_id;
        $decomiso->institucion_id=$request->institucion_id;
        $decomiso->latitud=$request->latitud;
        $decomiso->longitud=$request->longitud;
        $decomiso->user_create=$user_id;
        $decomiso->user_update=$user_id;
        $decomiso->save();
        $decomiso->instituciones()->attach($request->instituciones_id);

        //$decomiso_=['id'=> $decomiso->id,'user_create'=> Auth::user()->id,'user_update'=>NULL];
        //event(new DecomisoBitacorae($decomiso));

        // $bitacora_de=new Bitacora_decomiso;
        // $bitacora_de->user_id_create=Auth::user()->id;
        // //$bitacora_de->user_id_update=Auth::user()->id;

        // $decomiso->bitacora_decomiso()->save($bitacora_de);

        //event(new DecomisoBitacorae($decomiso));
        //return view('admin.decomiso.edit', compact('decomiso'));
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El decomiso ha sido creado.');
        //return redirect()->route('decomiso.edit')->with('flash', 'Tu publicación ha sido eliminada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Decomiso  $decomiso
     * @return \Illuminate\Http\Response
     */
    //acción utilizada para desplegar la vista de decomisos desabilitados
    public function show($Id)
    {
        // if ($Id==1) {
        //     $dehabilitado=true;
        //     $decomisos= Decomiso::onlyTrashed()->orderByDesc('fecha')->get();
        //     return view('admin.decomiso.index',compact('decomisos','dehabilitado'));
        // } else {
        //     # code...
        // }


        $dehabilitado=true;
        $decomisos=DB::table('decomisos')
        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
        ->select('decomisos.id',
                'decomisos.fecha',
                'decomisos.observacion',
                'decomisos.direccion',
                'municipios.nombre AS municipio',
                'departamentos.nombre AS departamento',
                'institucions.nombre AS institucion',
                'decomisos.latitud',
                'decomisos.longitud',
                'decomisos.deleted_at',
                )
        ->orderByDesc('fecha')
        ->whereNotNull('decomisos.deleted_at')
        ->get();
        $user=Auth::user();
        $editar_permisos=$user->can('editar decomisos');
        $borrar_permisos=$user->can('borrar decomisos');
        //dd($decomisos);
        return view('admin.decomiso.index',compact('decomisos','dehabilitado','editar_permisos','borrar_permisos'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Decomiso  $decomiso
     * @return \Illuminate\Http\Response
     */
    //acción para desplegar la vista de edición de decomiso principal
    public function edit(Decomiso $decomiso)
    {
        //debug($decomiso);
        $dehabilitado=false;
        //dd(Auth::user()->getAllPermissions()->pluck('name'));
        $permisos=Auth::user()->getAllPermissions()->pluck('name');
        //dd($permisos);
        	//$erer=array_search('manejar decomisos', $permisos, true);
            $permiso_tab = Arr::first($permisos, function ($value, $key ) {
                return str_contains($value, 'decomisos de');
            });

        switch ($permiso_tab) {
            case "ver decomisos de droga":
                $permiso_tab="drogas";
                break;
            case "ver decomisos de precursores":
                $permiso_tab="precursores";
                break;
            case "ver decomisos de armas":
                $permiso_tab="armas";
                break;
            case "ver decomisos de municiones":
                $permiso_tab="municiones";
                break;
            case "ver detenidos en decomisos":
                $permiso_tab="detenidos";
                break;
            case "ver decomisos de transportes":
                $permiso_tab="transportes";
                break;
        }
        //dd($permiso_tab);
            //$admin = Arr::get($info, "manejar decomisos");
        //dd($permiso_tab=="ver decomisos de droga");
        //$decomiso = new Decomiso;
        $tipoDrogas= TipoDroga::all();
        $departamentos= Departamento::all();
        $municipios= Municipio::all();
        $instituciones= Institucion::all();
        $ocupaciones= Ocupacion::all();
        $presentacion_drogas= PresentacionDroga::all();
        $presentacion_precursores= PresentacionPrecursor::withTrashed()->get();
        $estructuras= EstructuraCriminal::all();
        $indentidades= Identificacion::all();
        $estados= EstadoCivil::all();
        $drogas= Droga::all();
        $armas= Arma::all();
        $municiones= TipoMunicion::all();
        $precursores= Precursor::all();
        $paises= Pais::all();
        $detenidos = decomiso_detenido::where('decomiso_id', $decomiso->id)->get();
        $transportes = decomiso_transporte::where('decomiso_id', $decomiso->id)->get();
        //$detenidos = Decomiso::find($decomiso->id)->detenidos();
        //dd($decomiso->instituciones->pluck('nombre')->toArray());

        // $decomiso_drogas = decomiso_droga::all();
        // $decomiso_precursores = Decomiso::find($decomiso->id)->precursores()->get();
        // $decomiso_armas = Decomiso::find($decomiso->id)->armas()->get();//duda
        // $decomiso_municiones = Decomiso::find($decomiso->id)->municiones()->get();
        // $decomiso_transportes = Decomiso::find($decomiso->id)->transportes()->get();
        //dd($decomiso_drogas);

        $tab="nada";
        $decomisos = "sadfad";

       return view('admin.decomiso.edit', compact('dehabilitado','permiso_tab','decomisos','transportes','detenidos','municiones','tab','decomiso','instituciones','tipoDrogas','departamentos','paises','municipios','presentacion_drogas','presentacion_precursores','ocupaciones','estructuras','indentidades','estados','drogas','precursores','armas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Decomiso  $decomiso
     * @return \Illuminate\Http\Response
     */
    //acción para actualizar el decomiso principal
    public function update(Request $request, Decomiso $decomiso)
    {
        //dd($request);

        $rules = [      //reglas de validación
            'fecha_'=> 'required',
            'observacion_'=> 'required',
            'direccion_'=> 'required',
            'municipio_id_'=> 'required',
            //'institucion_id_'=> 'required',
            'latitud'=> 'required',
            'longitud'=> 'required',
        ];

        $this->validate($request, $rules);
        // $value = $request->session()->all();

        // $validator = Validator::make($request->all(), [
        //     'fecha_'=> 'required',
        //     'observacion_'=> 'required',
        //     'direccion_'=> 'required',
        //     'municipio_id_'=> 'required',
        //     'institucion_id_'=> 'required',
        // ]);
        // //dd($value);
        // if ($validator->fails()) {
        //     $request->session()->flash('status', 'Task was successful!');
        //     //$request["alog"]="hola";
        //     //dd($request);
        //     return redirect()->route('decomiso.edit', ['decomiso' => $decomiso, 'modelo' => 'arma'])
        //                 ->withErrors($validator)
        //                 ->withInput()
        //                 ->with("lkjadfñlkj");

        // }
        //dd($request->route()->getActionMethod());
        //dd($request->instituciones_id);
        $decomiso->fecha=$request->fecha_;
        $decomiso->observacion=$request->observacion_;
        $decomiso->direccion=$request->direccion_;
        $decomiso->municipio_id=$request->municipio_id_;
        $decomiso->institucion_id=$request->institucion_id_!=null?$request->institucion_id_:$decomiso->institucion_id;
        $decomiso->latitud=$request->latitud;
        $decomiso->longitud=$request->longitud;
        $decomiso->user_update=Auth::user()->id;
        $decomiso->save();

        $decomiso->instituciones()->sync($request->instituciones_id);



        //dd($decomiso->id);


        //$decomiso_=['id'=> $decomiso->id,'user_update'=>Auth::user()->id];
        //event(new DecomisoBitacorae($decomiso));

        // $decomiso_bita = Bitacora_decomiso::where('decomiso_id', $decomiso->id)->first();
        // $decomiso_bita->user_id_update=Auth::user()->id;

        // $decomiso->bitacora_decomiso()->save($decomiso_bita);
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El decomiso ha sido editado.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Decomiso  $decomiso
     * @return \Illuminate\Http\Response
     */
    //acción para borrar el decomiso principal
    public function destroy(Decomiso $decomiso)
    {
        $decomiso->delete();
        return redirect()->route('decomiso.index')->with('flash', 'El decomiso ha sido eliminado.');
    }

    public function desabilitados(Decomiso $decomiso, $Id)
    {
        //dd($Id);
        $dehabilitado=true;
        //dd(Auth::user()->getAllPermissions()->pluck('name'));
        $permisos=Auth::user()->getAllPermissions()->pluck('name');
        //dd($permisos);
        	//$erer=array_search('manejar decomisos', $permisos, true);
            $permiso_tab = Arr::first($permisos, function ($value, $key ) {
                return str_contains($value, 'decomisos de');
            });

        switch ($permiso_tab) {
            case "ver decomisos de droga":
                $permiso_tab="drogas";
                break;
            case "ver decomisos de precursores":
                $permiso_tab="precursores";
                break;
            case "ver decomisos de armas":
                $permiso_tab="armas";
                break;
            case "ver decomisos de municiones":
                $permiso_tab="municiones";
                break;
            case "ver detenidos en decomisos":
                $permiso_tab="detenidos";
                break;
            case "ver decomisos de transportes":
                $permiso_tab="transportes";
                break;
        }

        switch ($Id) {
            case "1":
                debug("llego a switch con 1");
                debug($decomiso);
                $permiso_tab="drogas";
                break;
            case "2":
                //dd("llego a switch con 2");
                $permiso_tab="precursores";
                break;
            case "3":
                $permiso_tab="armas";
                break;
            case "4":
                $permiso_tab="municiones";
                break;
            case "5":
                $permiso_tab="detenidos";
                break;
            case "6":
                $permiso_tab="transportes";
                break;
        }
        //dd($permiso_tab);
            //$admin = Arr::get($info, "manejar decomisos");
        //dd($permiso_tab=="ver decomisos de droga");
        //$decomiso = new Decomiso;
        $tipoDrogas= TipoDroga::all();
        $departamentos= Departamento::all();
        $municipios= Municipio::all();
        $instituciones= Institucion::all();
        $ocupaciones= Ocupacion::all();
        $presentacion_drogas= PresentacionDroga::all();
        $presentacion_precursores= PresentacionPrecursor::all();
        $estructuras= EstructuraCriminal::all();
        $indentidades= Identificacion::all();
        $estados= EstadoCivil::all();
        $drogas= Droga::all();
        $armas= Arma::all();
        $municiones= TipoMunicion::all();
        $precursores= Precursor::all();
        $paises= Pais::all();
        $detenidos = decomiso_detenido::where('decomiso_id', $decomiso->id)->get();
        $transportes = decomiso_transporte::where('decomiso_id', $decomiso->id)->get();
        //$detenidos = Decomiso::find($decomiso->id)->detenidos();
        //dd($municiones);

        // $decomiso_drogas = decomiso_droga::all();
        // $decomiso_precursores = Decomiso::find($decomiso->id)->precursores()->get();
        // $decomiso_armas = Decomiso::find($decomiso->id)->armas()->get();//duda
        // $decomiso_municiones = Decomiso::find($decomiso->id)->municiones()->get();
        // $decomiso_transportes = Decomiso::find($decomiso->id)->transportes()->get();
        //dd($decomiso_drogas);

        $tab="nada";
        $decomisos = "sadfad";

       return view('admin.decomiso.edit', compact('dehabilitado','permiso_tab','decomisos','transportes','detenidos','municiones','tab','decomiso','instituciones','tipoDrogas','departamentos','paises','municipios','presentacion_drogas','presentacion_precursores','ocupaciones','estructuras','indentidades','estados','drogas','precursores','armas'));
    }

    public function restaurar($id)
    {
        debug("llega a restaurar");
        Decomiso::withTrashed()->find($id)->restore();
        return redirect()->route('decomiso.index')->with('flash', 'El decomiso ha sido restaurado.');
    }

    public function buscar($id)
    {
        $partes = explode(", ", $id);
        $criterio="";
        $valor="";

        if (count($partes)>1) {
            debug("tiene criterio");
            $criterio=$partes[0];
            $valor=$partes[1];
            switch($criterio) {
                case('droga'):
                    $drogas=DB::table('decomisos')
                        ->join('decomiso_droga', 'decomisos.id', '=', 'decomiso_droga.decomiso_id')
                        ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('drogas.descripcion','like','%'.$valor. '%')
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('decomiso_droga.deleted_at')
                        ->distinct()
                        ->get();
                        debug($drogas);
                        return response()->json(['decomisos'=>$drogas]);
                    break;
                case('precursor'):
                    $precursores=DB::table('decomisos')
                        ->join('decomiso_precursor', 'decomisos.id', '=', 'decomiso_precursor.decomiso_id')
                        ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('precursors.descripcion', 'like','%'.$valor. '%')
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('decomiso_precursor.deleted_at')
                        ->distinct()
                        ->get();
                        debug($precursores);
                        return response()->json(['decomisos'=>$precursores]);
                    break;
                case('arma'):
                    $armas=DB::table('decomisos')
                        ->join('arma_decomiso', 'decomisos.id', '=', 'arma_decomiso.decomiso_id')
                        ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('armas.descripcion', 'like','%'.$valor. '%')
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('arma_decomiso.deleted_at')
                        ->distinct()
                        ->get();
                        debug($armas);
                        return response()->json(['decomisos'=>$armas]);
                    break;
                case('municion'):
                    $municiones=DB::table('decomisos')
                        ->join('decomiso_tipo_municion', 'decomisos.id', '=', 'decomiso_tipo_municion.decomiso_id')
                        ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('tipo_municions.descripcion', 'like','%'.$valor. '%')
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('decomiso_tipo_municion.deleted_at')
                        ->distinct()
                        ->get();
                        debug($municiones);
                        return response()->json(['decomisos'=>$municiones]);
                    break;
                case('detenido'):
                    $detenidos=DB::table('decomisos')
                        ->join('decomiso_detenidos', 'decomisos.id', '=', 'decomiso_detenidos.decomiso_id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('decomiso_detenidos.nombre', 'like','%'.$valor. '%')
                        ->orWhere('decomiso_detenidos.alias', 'like','%'.$valor. '%')
                        ->orWhere('decomiso_detenidos.identidad', 'like','%'.$valor. '%')
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('decomiso_detenidos.deleted_at')
                        ->distinct()
                        ->get();
                        debug($detenidos);
                        return response()->json(['decomisos'=>$detenidos]);
                    break;
                case('transporte'):
                    $transportes=DB::table('decomisos')
                        ->join('decomiso_transportes', 'decomisos.id', '=', 'decomiso_transportes.id')
                        ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                        ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                        ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                        ->where('decomiso_transportes.placa', $valor)
                        ->orWhere('decomiso_transportes.marca', $valor)
                        ->orWhere('decomiso_transportes.modelo', $valor)
                        ->orWhere('decomiso_transportes.tipo', $valor)
                        ->select('decomisos.id',
                                'decomisos.fecha',
                                'decomisos.observacion',
                                'decomisos.direccion',
                                'municipios.nombre AS municipio',
                                'departamentos.nombre AS departamento',
                                'institucions.nombre AS institucion',
                                'decomisos.latitud',
                                'decomisos.longitud',
                                'decomisos.deleted_at',
                                )
                        ->orderByDesc('fecha')
                        ->whereNull('decomisos.deleted_at')
                        ->whereNull('decomiso_transportes.deleted_at')
                        ->distinct()
                        ->get();
                        debug($transportes);
                        return response()->json(['decomisos'=>$transportes]);
                    break;
                default:
                    debug('Something went wrong.');
            }
        } else {
            $drogas=DB::table('decomisos')
                ->join('decomiso_droga', 'decomisos.id', '=', 'decomiso_droga.decomiso_id')
                ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('drogas.descripcion','like','%'.$id. '%')
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('decomiso_droga.deleted_at')
                ->distinct()
                ->get();

            $precursores=DB::table('decomisos')
                ->join('decomiso_precursor', 'decomisos.id', '=', 'decomiso_precursor.decomiso_id')
                ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('precursors.descripcion', 'like','%'.$id. '%')
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('decomiso_precursor.deleted_at')
                ->distinct()
                ->get();

            $armas=DB::table('decomisos')
                ->join('arma_decomiso', 'decomisos.id', '=', 'arma_decomiso.decomiso_id')
                ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('armas.descripcion', 'like','%'.$id. '%')
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('arma_decomiso.deleted_at')
                ->distinct()
                ->get();

            $municiones=DB::table('decomisos')
                ->join('decomiso_tipo_municion', 'decomisos.id', '=', 'decomiso_tipo_municion.decomiso_id')
                ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('tipo_municions.descripcion', 'like','%'.$id. '%')
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('decomiso_tipo_municion.deleted_at')
                ->distinct()
                ->get();

            $detenidos=DB::table('decomisos')
                ->join('decomiso_detenidos', 'decomisos.id', '=', 'decomiso_detenidos.decomiso_id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('decomiso_detenidos.nombre', 'like','%'.$id. '%')
                ->orWhere('decomiso_detenidos.alias', 'like','%'.$id. '%')
                ->orWhere('decomiso_detenidos.identidad', 'like','%'.$id. '%')
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('decomiso_detenidos.deleted_at')
                ->distinct()
                ->get();

            $transportes=DB::table('decomisos')
                ->join('decomiso_transportes', 'decomisos.id', '=', 'decomiso_transportes.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
                ->where('decomiso_transportes.placa', $id)
                ->orWhere('decomiso_transportes.marca', $id)
                ->orWhere('decomiso_transportes.modelo', $id)
                ->orWhere('decomiso_transportes.tipo', $id)
                ->select('decomisos.id',
                        'decomisos.fecha',
                        'decomisos.observacion',
                        'decomisos.direccion',
                        'municipios.nombre AS municipio',
                        'departamentos.nombre AS departamento',
                        'institucions.nombre AS institucion',
                        'decomisos.latitud',
                        'decomisos.longitud',
                        'decomisos.deleted_at',
                        )
                ->orderByDesc('fecha')
                ->whereNull('decomisos.deleted_at')
                ->whereNull('decomiso_transportes.deleted_at')
                ->distinct()
                ->get();

            $decomisos=collect();
            foreach ($drogas as $item) {
                $decomisos->push($item);
            }
            foreach ($precursores as $item) {
                $decomisos->push($item);
            }
            foreach ($armas as $item) {
                $decomisos->push($item);
            }
            foreach ($municiones as $item) {
                $decomisos->push($item);
            }
            foreach ($detenidos as $item) {
                $decomisos->push($item);
            }
            foreach ($transportes as $item) {
                $decomisos->push($item);
            }
            debug($decomisos);
            return response()->json(['decomisos'=>$decomisos]);
        }

    }

    public function ver_importar()
    {
        //dd("llega a ver importar");


        return view('admin.decomiso.importar');
    }
    public function importar(Request $request)
    {
        $municipios=Municipio::select('id','nombre')->get();
        $drogas=Droga::select('id','descripcion')->get();
        $presentaciones=presentacionDroga::select('id','descripcion')->get();
        $instituciones=Institucion::select('id','nombre')->get();
        $decomisos=json_decode($request->parametro, true); //transformando el valor recibido en el request de texto a arreglo
        debug($decomisos);

        /////////////////////
        $errorers_fila=[];
        $porcentaje=0;
        foreach ($decomisos as $clave => $deco) {
            //$deco['fecha']=preg_match("/^(19[0-9]{2}|2[0-9]{3})-([1-9]|1[012])-([1-9]|[1-2][0-9]|30|31)$/", $deco['fecha'])?$deco['fecha']:"da error";
            $cambio=0;
            if ($deco['Observacion']=="") {
                $errore="hay un error en la fila " .$clave+1 . " en la columna observacion ";
                array_push($errorers_fila, $errore);
            }
            if (!preg_match("/^(19[0-9]{2}|2[0-9]{3})-([1-9]|1[012])-([1-9]|[1-2][0-9]|30|31)$/", $deco['Fecha'])) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna fecha ";
                array_push($errorers_fila, $errore);
            }
            if ($deco['Direccion']=="") {
                $errore="hay un error en la fila " .$clave+1 . " en la columna direccion ";
                array_push($errorers_fila, $errore);
            }
            //debug((($municipios->where('nombre', $deco['Mun_id'])->pluck('id'))[0])>0);
            //debug("llega");
            if (count(($municipios->where('nombre', $deco['Mun_id'])->pluck('id')))==0) {
                //debug("llega");
                $errore="hay un error en la fila " .$clave+1 . " en la columna municipio ";
                array_push($errorers_fila, $errore);
            }
            //($municipios->where('nombre', $deco['municipio_id'])->pluck('id'))[0];
            if (!preg_match("/(\d*\.\d{2})\d*/", $deco['Latitud'])) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna latitud ";
                array_push($errorers_fila, $errore);
            }
            if (!preg_match("/(\d*\.\d{2})\d*/", $deco['Longitud'])) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna longitud ";
                array_push($errorers_fila, $errore);
            }

            if (count(($instituciones->where('nombre', $deco['Int_id'])->pluck('id')))==0) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna institucion ";
                array_push($errorers_fila, $errore);
            }

            if (count(($drogas->where('descripcion', $deco['Id_Droga'])->pluck('id')))==0) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna droga ";
                array_push($errorers_fila, $errore);
            }

            if (count(($presentaciones->where('descripcion', $deco['Id_Presentacion'])->pluck('id')))==0) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna presentacion ";
                array_push($errorers_fila, $errore);
            }

            if (!preg_match("/^[1-9][0-9]*$/", $deco['Cantidad']) || $deco['Cantidad']<0) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna cantidad ";
                array_push($errorers_fila, $errore);
            }
            if ($deco['Peso']=='9.0E-5') {
                debug("esa");
                $deco['Peso']=rtrim(number_format($deco['Peso'],6),0);
            }


            if (preg_match("/^[0-9]+([\,\.][0-9]+)?$/", $deco['Peso'])==false || $deco['Peso']<0) {
                $errore="hay un error en la fila " .$clave+1 . " en la columna peso ";
                array_push($errorers_fila, $errore);
            }
            //100/count($decomisos)*$clave;
            // if ($cambio==1) {
            //     $porcentaje=(100/count($decomisos))*$clave;
            // }

            //$porcentaje=100/count($decomisos);
        }

        /////////////////////////////
        $porcentaje=100/(count($decomisos)*12)*((count($decomisos)*12)-count($errorers_fila));
        //debug($porcentaje);
        $decomisos2=[];
        if (count($errorers_fila)==0) {
            foreach ($decomisos as $key => $dec) {
                $itemsubdecomiso = array("numero"=>$dec['Numero'],"cantidad"=>$dec['Cantidad'], "peso"=>$dec['Peso'], "droga_id"=>$dec['Id_Droga'], "presentacion_droga_id"=>$dec['Id_Presentacion']);
                $itemdecomiso = array("fecha"=>$dec['Fecha'], "observacion"=>$dec['Observacion'], "direccion"=>$dec['Direccion'], "municipio_id"=>$dec['Mun_id'], "latitud"=>$dec['Latitud'], "longitud"=>$dec['Longitud'], "institucion_id"=>$dec['Int_id'], "subdecomiso"=>[]);

                if ($key==0) {
                    $itemdecomiso['subdecomiso'][]=$itemsubdecomiso;
                    $decomisos2[]=$itemdecomiso;
                } else if($decomisos[$key]['Direccion']==$decomisos2[(count($decomisos2))-1]['direccion']) {
                    $decomisos2[(count($decomisos2))-1]['subdecomiso'][]=$itemsubdecomiso;
                }else{
                    $itemdecomiso['subdecomiso'][]=$itemsubdecomiso;
                    $decomisos2[]=$itemdecomiso;
                }
                //debug($decomisos2[$key]['Direccion']);
            }
        }


        debug($decomisos2);

        //debug($errorers_fila);











        debug($errorers_fila);

        if (count($errorers_fila)==0) {
            foreach ($decomisos2 as $deco) {
                $decomiso=new Decomiso;
                $decomiso->fecha=$deco['fecha'];
                $decomiso->observacion=$deco['observacion'];
                $decomiso->direccion=$deco['direccion'];
                $decomiso->municipio_id=($municipios->where('nombre', $deco['municipio_id'])->pluck('id'))[0];
                //$decomiso->municipio_id=$deco['municipio_id'];
                $decomiso->institucion_id=($instituciones->where('nombre', $deco['institucion_id'])->pluck('id'))[0];
                $decomiso->latitud=$deco['latitud'];
                $decomiso->longitud=$deco['longitud'];
                $decomiso->save();

                // foreach ($deco['subdecomiso'] as $subdecomiso) {
                //     $decomiso->drogas()->attach([$subdecomiso['droga_id'] => ['cantidad'=>$subdecomiso['cantidad'], 'peso'=>$subdecomiso['peso'],'presentacion_droga_id'=>$subdecomiso['presentacion_droga_id']]]);
                // }

                foreach ($deco['subdecomiso'] as $clave => $subdecomiso) {
                    debug($clave);
                    try {
                        $decomiso->drogas()->attach([($drogas->where('descripcion', $subdecomiso['droga_id'])->pluck('id'))[0] => ['cantidad'=>$subdecomiso['cantidad'], 'peso'=>$subdecomiso['peso'],'presentacion_droga_id'=>($presentaciones->where('descripcion', $subdecomiso['presentacion_droga_id'])->pluck('id'))[0]]]);
                    } catch (\Throwable $th) {
                        return response()->json(['respuesta'=>'hay un error el la fila ' . $th]);
                    }

                }
            }
            $porcentaje=100;
            return response()->json(['respuesta'=>'La exportacion se realizo correctamente','porcentage'=> $porcentaje]);
        }else{
            return response()->json(['respuesta'=>$errorers_fila ,'porcentage'=> $porcentaje]);
        }




        //return response()->json(['respuesta'=>'La exportacion se realizo correctamente']);
    }
}
