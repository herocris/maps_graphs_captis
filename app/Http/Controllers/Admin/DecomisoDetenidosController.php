<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\decomiso_detenido;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\DecomisoBitacorae;

class DecomisoDetenidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver detenidos en decomisos'])->only('index'); 
        $this->middleware(['permission:crear detenidos en decomisos'])->only('store');
        $this->middleware(['permission:editar detenidos en decomisos'])->only('update');
        $this->middleware(['permission:borrar detenidos en decomisos'])->only('destroy');
    }
    public function index(Request $request)
    {
        // $decomisos=Decomiso::all();
        // $detenidos=collect();
        // foreach ($decomisos as $decomiso) {
        //     foreach ($decomiso->detenidos as $detenido) {
        //         $detenido['fecha']=$decomiso->fecha;
        //         $detenido['municipio']=$decomiso->municipio->nombre;
        //         $detenido['departamento']=$decomiso->municipio->departamento->nombre;
        //         $detenidos->push($detenido);
        //     }
        // }

        // $decomisos_detenidos=DB::table('decomiso_detenidos')
        //     ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
        //     //->join('tipo_municions', 'decomiso_detenidos.tipo_municion_id', '=', 'tipo_municions.id')

        //     ->join('pais', 'decomiso_detenidos.pais_id', '=', 'pais.id')
        //     ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
        //     ->join('ocupacions', 'decomiso_detenidos.ocupacion_id', '=', 'ocupacions.id')
        //     ->join('estado_civils', 'decomiso_detenidos.estado_civil_id', '=', 'estado_civils.id')
        //     ->join('identificacions', 'decomiso_detenidos.identificacion_id', '=', 'identificacions.id')
        //     // ->join('municipios AS mun_des', 'decomiso_detenidos.mun_des_id', '=', 'mun_des.id')

        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('decomiso_detenidos.nombre',
        //             'decomiso_detenidos.alias',
        //             'decomiso_detenidos.identidad',
        //             //'decomiso_detenidos.identificacion_id',
        //             'decomiso_detenidos.edad',
        //             'decomiso_detenidos.genero',
        //             // 'pais_pro.nombre AS pais_pro',
        //             // 'pais_des.nombre AS pais_des',
        //             // 'dep_pro.nombre AS dep_pro',
        //             // 'dep_des.nombre AS dep_des',
        //             // 'mun_pro.nombre AS mun_pro',
        //             // 'mun_des.nombre AS mun_des',
        //             'identificacions.descripcion AS tipo_id',
        //             'estructura_criminals.descripcion AS estructura',
        //             'ocupacions.descripcion AS ocupacion',
        //             'estado_civils.descripcion AS estado_civil',
        //             'pais.nombre AS nacionalidad',
        //             'departamentos.nombre AS departamento',
        //             'municipios.nombre AS municipio',
        //             'decomisos.fecha')
        //     ->whereNull('decomiso_detenidos.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     //->whereNotNull('decomiso_detenidos.dep_pro_id')
        //     //->whereNotNull('decomiso_detenidos.dep_pro_id')
        //     ->get();
        // //dd($decomisos_detenidos);
        // return view('admin.informe.detenidos',compact('decomisos_detenidos'));



        ///////////////////////////////logica nueva////////////////////////////////////


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

            $decomisos_detenidos=DB::table('decomiso_detenidos')
            ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
            ->join('pais', 'decomiso_detenidos.pais_id', '=', 'pais.id')
            ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
            ->join('ocupacions', 'decomiso_detenidos.ocupacion_id', '=', 'ocupacions.id')
            ->join('estado_civils', 'decomiso_detenidos.estado_civil_id', '=', 'estado_civils.id')
            ->join('identificacions', 'decomiso_detenidos.identificacion_id', '=', 'identificacions.id')
            // ->join('municipios AS mun_des', 'decomiso_detenidos.mun_des_id', '=', 'mun_des.id')

            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
            ->select('decomiso_detenidos.nombre',
                    'alias',
                    'identidad',
                    'edad',
                    'genero',
                    'identificacions.descripcion AS tipo_id',
                    'estructura_criminals.descripcion AS estructura',
                    'ocupacions.descripcion AS ocupacion',
                    'estado_civils.descripcion AS estado_civil',
                    'pais.nombre AS nacionalidad',
                    'departamentos.nombre AS departamento',
                    'municipios.nombre AS municipio',
                    'decomisos.fecha AS fecha')
            ->whereNull('decomiso_detenidos.deleted_at')
            ->whereNull('decomisos.deleted_at');
            //->whereNotNull('decomiso_detenidos.dep_pro_id')
            //->whereNotNull('decomiso_detenidos.dep_pro_id');
            //->whereNotNull('decomiso_transportes.dep_pro_id')
            //->whereNotNull('decomiso_transportes.dep_pro_id');
                    
            if($searchValue!=null){
                $decomisos_detenidos=$decomisos_detenidos->where(function ($query) use ($searchValue) {
                    $query->where('decomiso_detenidos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('alias','like','%'. $searchValue.'%')
                    ->orWhere('identidad','like','%'. $searchValue.'%')
                    ->orWhere('edad','like','%'. $searchValue.'%')
                    ->orWhere('genero','like','%'. $searchValue.'%')
                    ->orWhere('identificacions.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('estructura_criminals.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('ocupacions.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('estado_civils.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('pais.nombre','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('decomisos.fecha','like','%'. $searchValue.'%');
                    
                    // ->orWhere([['placa','like','%'. $searchValue.'%'],
                    //         ['marca','like','%'. $searchValue.'%']])
                    // ;
                });
            }
            
                
            $total=$decomisos_detenidos->count();

            $totalFilter=$decomisos_detenidos;
            //////////para filtrar por columas
            debug($columnNameArray);
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    debug($columna['data']);
                    switch ($columna['data']) {
                        case 'nombre':
                            $totalFilter= $totalFilter->where('decomiso_detenidos.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'alias':
                            $totalFilter= $totalFilter->where('alias','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'identidad':
                            $totalFilter= $totalFilter->where('identidad','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'edad':
                            $totalFilter= $totalFilter->where('edad','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'genero':
                            $totalFilter= $totalFilter->where('genero','like','%'. $columna['search']['value'].'%');
                            break;  
                        case 'tipo_id':
                            $totalFilter= $totalFilter->where('identificacions.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'estructura':
                            $totalFilter= $totalFilter->where('estructura_criminals.descripcion','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'ocupacion':
                            $totalFilter= $totalFilter->where('ocupacions.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'estado_civil':
                            $totalFilter= $totalFilter->where('estado_civils.descripcion','like','%'. $columna['search']['value'].'%');
                            break;  
                        case 'nacionalidad':
                            $totalFilter= $totalFilter->where('pais.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'departamento':
                            $totalFilter= $totalFilter->where('departamentos.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'municipio':
                            $totalFilter= $totalFilter->where('municipios.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'fecha':
                            debug("llega");
                            $totalFilter= $totalFilter->where('decomisos.fecha','like','%'. $columna['search']['value'].'%');
                            break;                             
                        default:
                            //$totalFilter= $totalFilter->where($columna['data'],'like','%'. $columna['search']['value'].'%');
                            break;
                    }
                }
            }
            ///////////para filtra por fechas
            if ($request->get("fec_ini")!=null && $request->get("fec_fin")!=null) {
                $totalFilter= $totalFilter->whereBetween('decomisos.fecha', [$request->get("fec_ini"), $request->get("fec_fin")]);
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
            debug($response);
            return response()->json($response);
        }else{
            return view('admin.informe.detenidos');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->genero);
        $decomiso=Decomiso::find($request->detenido_decomiso_id);
        // $rules = [
        //     //'nombreDetenido'=> 'required',
        //     //'alias'=> 'required',
        //     'identidad'=> 'required',
        //     //'edad'=> 'required',
        //     'genero'=> 'required',
        //     'detenido_decomiso_id'=> 'required',
        //     'identificacion_id'=> 'required',
        //     'estructura_id'=> 'required',
        //     'ocupacion_id'=> 'required',
        //     'estado_civil_id'=> 'required',
        //     'pais_id'=> 'required',
        // ];

        //$this->validate($request, $rules);

        $validator = Validator::make($request->all(), [
            //'nombreDetenido'=> 'required',
            //'alias'=> 'required',
            //'identidad'=> 'required',
            //'edad'=> 'required',
            'genero'=> 'required',
            'detenido_decomiso_id'=> 'required',
            'identificacion_id'=> 'required',
            'estructura_id'=> 'required',
            'ocupacion_id'=> 'required',
            'estado_civil_id'=> 'required',
            'pais_id'=> 'required',
        ]);
        if ($validator->fails()) {
            //dd("sdf");
            $request->session()->flash('modelo', '#pills-detenidos');
            $request->session()->flash('modal', '#createDetenidoModal');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $detenido=new decomiso_detenido;
        $detenido->nombre=$request->nombreDetenido;
        $detenido->alias=$request->alias;
        $detenido->identidad=$request->identidad;
        $detenido->edad	=$request->edad;
        $detenido->decomiso_id=$request->detenido_decomiso_id;
        $detenido->identificacion_id=$request->identificacion_id;
        $detenido->estructura_id=$request->estructura_id;
        $detenido->ocupacion_id=$request->ocupacion_id;
        $detenido->estado_civil_id=$request->estado_civil_id;
        $detenido->pais_id=$request->pais_id;
        $detenido->genero=$request->genero;

        $detenido->save();

        event(new DecomisoBitacorae($decomiso));
        
        $request->session()->flash('modelo', '#pills-detenidos');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso, 'modelo' => 'arma'])->with('flash', 'El detenido ha sido agregado al decomiso.');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        $detenido=decomiso_detenido::find($id);

        $decomiso=Decomiso::find($request->detenido_decomiso_id_);
        //dd("sdf");
        // $rules = [
        //     'genero_'=> 'required',
        //     'detenido_decomiso_id_'=> 'required',
        //     'identificacion_id_'=> 'required',
        //     'estructura_id_'=> 'required',
        //     'ocupacion_id_'=> 'required',
        //     'estado_civil_id_'=> 'required',
        //     'pais_id_'=> 'required',
        // ];

        // $this->validate($request, $rules);
        
        //dd($this->validate($request, $rules));
        $validator = Validator::make($request->all(), [
            'genero_'=> 'required',
            'detenido_decomiso_id_'=> 'required',
            'identificacion_id_'=> 'required',
            'estructura_id_'=> 'required',
            'ocupacion_id_'=> 'required',
            'estado_civil_id_'=> 'required',
            'pais_id_'=> 'required',
        ]);
        if ($validator->fails()) {
            //dd($validator->fails());
            $request->session()->flash('modelo', '#pills-detenidos');
            $request->session()->flash('modal', '#editDetenidoModal');
            $request->session()->flash('form1', '#form_edit_detenido');
            $request->session()->flash('form2', '#form_delete_detenido');
            $request->session()->flash('formRoute', '/admin/decomisodetenido/');
            $request->session()->flash('formID', $id);
            //$request->session()->flash('oculto', '#detenidoOcultoId_');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        
        $detenido->nombre=$request->nombreDetenido_;
        $detenido->alias=$request->alias_;
        $detenido->identidad=$request->identidad_;
        $detenido->edad	=$request->edad_;
        $detenido->decomiso_id=$request->detenido_decomiso_id_;
        $detenido->identificacion_id=$request->identificacion_id_!=null?$request->identificacion_id_:$detenido->identificacion_id;
        $detenido->estructura_id=$request->estructura_id_!=null?$request->estructura_id_:$detenido->estructura_id;
        //$detenido->ocupacion_id=$request->ocupacion_id_;
        //dd($detenido->ocupacion_id);
        $detenido->ocupacion_id=$request->ocupacion_id_!=null?$request->ocupacion_id_:$detenido->ocupacion_id;
        $detenido->estado_civil_id=$request->estado_civil_id_!=null?$request->estado_civil_id_:$detenido->estado_civil_id;
        $detenido->pais_id=$request->pais_id_!=null?$request->pais_id_:$detenido->pais_id;
        $detenido->genero=$request->genero_!=null?$request->genero_:$detenido->genero;
        //$detenido->isDirty();
        $detenido->save();

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-detenidos');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El detenido ha sido editado al decomiso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $detenido = decomiso_detenido::find($id);
        $detenido->delete();

        $decomiso=Decomiso::find($detenido->decomiso_id);
        $request->session()->flash('modelo', '#pills-detenidos');
        
        event(new DecomisoBitacorae($decomiso));
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El detenido ha sido eliminado al decomiso.');
    }

    public function restaurar(Request $request, $Id)
    {
        //dd($Id);
        decomiso_detenido::withTrashed()->find($Id)->restore();
        $request->session()->flash('modelo', '#pills-detenidos');
        return back()->with('flash', 'El detenido ha sido restaurado al decomiso.');
    }

    public function habilitados($Id, $tipo)
    {
       

        // $deco_dete=Decomiso::find($Id)->detenidos->where('pivot.deleted_at',($tipo=='habilitados'?'==':'!='),null)->sortByDesc('pivot.created_at');
        
        // $detenidos=collect();
        // foreach ($deco_dete as $elem) {
        //     $detenidos->push($elem);
        // }


            if ($tipo=='habilitados') {
                $detenidos=DB::table('decomiso_detenidos')
                ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
                ->join('pais', 'decomiso_detenidos.pais_id', '=', 'pais.id')
                ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
                ->join('ocupacions', 'decomiso_detenidos.ocupacion_id', '=', 'ocupacions.id')
                ->join('estado_civils', 'decomiso_detenidos.estado_civil_id', '=', 'estado_civils.id')
                ->join('identificacions', 'decomiso_detenidos.identificacion_id', '=', 'identificacions.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->where('decomiso_id',$Id)
                ->select('decomiso_detenidos.nombre',
                        'decomiso_detenidos.alias',
                        'decomiso_detenidos.identidad',
                        'decomiso_detenidos.genero',
                        'decomiso_detenidos.edad',
                        'identificacions.descripcion AS tipo_id',
                        'estructura_criminals.descripcion AS estructura',
                        'ocupacions.descripcion AS ocupacion',
                        'estado_civils.descripcion AS estado_civil',
                        'pais.nombre AS nacionalidad',

                        'decomiso_detenidos.identificacion_id',
                        'decomiso_detenidos.estructura_id',
                        'decomiso_detenidos.ocupacion_id',
                        'decomiso_detenidos.estado_civil_id',
                        'decomiso_detenidos.pais_id',
                        'decomiso_detenidos.genero',
                        'decomiso_detenidos.id',
                        'decomiso_detenidos.deleted_at')
                ->whereNull('decomiso_detenidos.deleted_at')
                ->get();
            } else {
                $detenidos=DB::table('decomiso_detenidos')
                ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
                ->join('pais', 'decomiso_detenidos.pais_id', '=', 'pais.id')
                ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
                ->join('ocupacions', 'decomiso_detenidos.ocupacion_id', '=', 'ocupacions.id')
                ->join('estado_civils', 'decomiso_detenidos.estado_civil_id', '=', 'estado_civils.id')
                ->join('identificacions', 'decomiso_detenidos.identificacion_id', '=', 'identificacions.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->where('decomiso_id',$Id)
                ->select('decomiso_detenidos.nombre',
                        'decomiso_detenidos.alias',
                        'decomiso_detenidos.identidad',
                        'decomiso_detenidos.genero',
                        'decomiso_detenidos.edad',
                        'identificacions.descripcion AS tipo_id',
                        'estructura_criminals.descripcion AS estructura',
                        'ocupacions.descripcion AS ocupacion',
                        'estado_civils.descripcion AS estado_civil',
                        'pais.nombre AS nacionalidad',

                        'decomiso_detenidos.identificacion_id',
                        'decomiso_detenidos.estructura_id',
                        'decomiso_detenidos.ocupacion_id',
                        'decomiso_detenidos.estado_civil_id',
                        'decomiso_detenidos.pais_id',
                        'decomiso_detenidos.genero',
                        'decomiso_detenidos.id',
                        'decomiso_detenidos.deleted_at')
                ->whereNotNull('decomiso_detenidos.deleted_at')
                ->get();
            }
            
        


        // $detenidos=$tipo=='habilitados'?$detenidos->whereNull('deleted_at')
        // :$detenidos->whereNotNull('deleted_at');
        //debug($detenidos);
        //$detenidoss=$detenidos->whereNull('decomiso_detenidos.deleted_at');
        debug($detenidos);
        return response()->json(['decomisos_habilitados'=>$detenidos]);
    }
}
