<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\decomiso_municion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\DecomisoBitacorae;

class DecomisoMunicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver decomisos de municiones'])->only('index'); 
        $this->middleware(['permission:crear decomisos de municiones'])->only('store');
        $this->middleware(['permission:editar decomisos de municiones'])->only('update');
        $this->middleware(['permission:borrar decomisos de municiones'])->only('destroy');
    }
    public function index(Request $request)
    {
        // $decomisos_municiones=DB::table('decomiso_tipo_municion')
        //     ->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')
        //     ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
        //     //->join('presentacion_tipo_municions', 'decomiso_tipo_municion.presentacion_precursor_id', '=', 'presentacion_tipo_municions.id')
        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('tipo_municions.descripcion AS municion','cantidad','departamentos.nombre AS departamento','municipios.nombre AS municipio','decomisos.fecha')
        //     ->whereNull('decomiso_tipo_municion.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     ->get();
        // //dd($decomisos_municiones);
        // return view('admin.informe.municiones',compact('decomisos_municiones'));




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

            $decomisos_municiones=DB::table('decomiso_tipo_municion')
                    ->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')
                    ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
                    //->join('presentacion_tipo_municions', 'decomiso_tipo_municion.presentacion_precursor_id', '=', 'presentacion_tipo_municions.id')
                    ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                    ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                    ->select('tipo_municions.descripcion AS municion',
                            'cantidad',
                            'departamentos.nombre AS departamento',
                            'municipios.nombre AS municipio',
                            'decomisos.fecha')
                    ->whereNull('decomiso_tipo_municion.deleted_at')
                    ->whereNull('decomisos.deleted_at');
                    
            if($searchValue!=null){
                $decomisos_municiones=$decomisos_municiones->where(function ($query) use ($searchValue) {
                    $query->where('tipo_municions.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('cantidad','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('decomisos.fecha','like','%'. $searchValue.'%');
                });
            }
            
                
            $total=$decomisos_municiones->count();

            $totalFilter=$decomisos_municiones;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'municion':
                            $totalFilter= $totalFilter->where('tipo_municions.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'cantidad':
                            $totalFilter= $totalFilter->where('cantidad','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'departamento':
                            $totalFilter= $totalFilter->where('departamentos.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'municipio':
                            //debug("llega");
                            $totalFilter= $totalFilter->where('municipios.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'fecha':
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

            return response()->json($response);
        }else{
            return view('admin.informe.municiones');
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
        //dd($request);
        $decomiso=Decomiso::find($request->municion_decomiso_id);

        // $rules = [
        //     'municion_id'=> 'required',
        //     'cantidadMunicion'=> 'required',
        // ];

        // $this->validate($request, $rules);

        $validator = Validator::make($request->all(), [
            'municion_id'=> 'required',
            'cantidadMunicion'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-municiones');
            $request->session()->flash('modal', '#createMunicionModal');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $decomiso->municiones()->attach([$request->municion_id => ['cantidad'=>$request->cantidadMunicion]]);

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-municiones');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso, 'modelo' => 'arma'])->with('flash', 'La munición ha sido agregada al decomiso.');
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
        $decomiso=Decomiso::find($request->municion_decomiso_id_);

        $validator = Validator::make($request->all(), [
            'municionOcultoId_'=> 'required',
            'cantidadMunicion_'=> 'required',
            'municion_decomiso_id_'=> 'required',
            'municion_id_'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-municiones');
            $request->session()->flash('modal', '#editMunicionModal');
            $request->session()->flash('form1', '#form_edit_municion');
            $request->session()->flash('form2', '#form_delete_municion');
            $request->session()->flash('formRoute', '/admin/decomisomunicion/');
            $request->session()->flash('formID', $request->municionOcultoId_);
            $request->session()->flash('oculto', '#municionOcultoId_');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        
        $decomiso_municion = decomiso_municion::find($request->municionOcultoId_);
        //dd($decomiso_municion->isDirty());
        // $decomiso_municion->cantidad = $decomiso_municion->isDirty('cantidad')?$request->cantidadMunicion_:$decomiso_municion->cantidad;
        // $decomiso_municion->decomiso_id = $decomiso_municion->isDirty('decomiso_id')?$request->municion_decomiso_id_:$decomiso_municion->decomiso_id;
        // $decomiso_municion->tipo_municion_id = $decomiso_municion->isDirty('tipo_municion_id')?$request->municion_id_:$decomiso_municion->tipo_municion_id;

        $decomiso_municion->cantidad = $request->cantidadMunicion_;
        $decomiso_municion->decomiso_id = $request->municion_decomiso_id_;
        //$decomiso_municion->tipo_municion_id =$request->municion_id_;
        //dd($decomiso_municion->isDirty('tipo_municion_id'));
        //dd($request->municion_id_);
        $decomiso_municion->tipo_municion_id = $request->municion_id_!=null?$request->municion_id_:$decomiso_municion->tipo_municion_id;
        //dd($decomiso_municion->isDirty('tipo_municion_id'));
        //dd($decomiso_municion->isDirty());
        //debug("ñodsifja");
        $decomiso_municion->save();
        //dd($decomiso_municion->isDirty());
        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-municiones');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'La munición ha sido editada al decomiso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $decomiso_municion = decomiso_municion::find($id);
        $decomiso_municion->delete();

        
        $decomiso=Decomiso::find($decomiso_municion->decomiso_id);

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-municiones');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'La munición ha sido eliminada al decomiso.');
    }

    public function restaurar(Request $request, $Id)
    {
        //dd($Id);
        decomiso_municion::withTrashed()->find($Id)->restore();
        $request->session()->flash('modelo', '#pills-municiones');
        return back()->with('flash', 'La munición ha sido restaurada al decomiso.');
    }

    public function habilitados($Id, $tipo)
    {
        // debug($Id);
        // debug($tipo);
        // debug("aqui");

        // $decomiso=Decomiso::find($Id);

        // $municiones=collect();
        // if ($tipo=="habilitados") {
        //     foreach ($decomiso->municiones as $municion) {
        //         if ($municion->pivot->deleted_at==null) {
        //             //debug($municion->pivot->deleted_at);
        //             $municiones->push($municion);
        //         }
        //     }
        // } else {
        //     foreach ($decomiso->municiones as $municion) {
        //         if ($municion->pivot->deleted_at!=null) {
        //             //debug($municion->pivot->deleted_at);
        //             $municiones->push($municion);
        //         }
        //     }
        // }

        $deco_mun=Decomiso::find($Id)->municiones->where('pivot.deleted_at',($tipo=='habilitados'?'==':'!='),null)->sortByDesc('pivot.created_at');
        
        $municiones=collect();
        foreach ($deco_mun as $elem) {
            $municiones->push($elem);
        }

        //debug($municiones);
        debug($municiones);
        return response()->json(['decomisos_habilitados'=>$municiones]);
    }
}
