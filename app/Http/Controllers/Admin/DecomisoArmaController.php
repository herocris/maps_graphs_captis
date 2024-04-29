<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\decomiso_arma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\DecomisoBitacorae;


class DecomisoArmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver decomisos de armas'])->only('index'); 
        $this->middleware(['permission:crear decomisos de armas'])->only('store');
        $this->middleware(['permission:editar decomisos de armas'])->only('update');
        $this->middleware(['permission:borrar decomisos de armas'])->only('destroy');
    }
    public function index(Request $request)
    {
        // $decomisos_armas=DB::table('arma_decomiso')
        //     ->join('decomisos', 'arma_decomiso.decomiso_id', '=', 'decomisos.id')
        //     ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
        //     //->join('presentacion_armas', 'arma_decomiso.presentacion_precursor_id', '=', 'presentacion_armas.id')
        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('armas.descripcion AS arma','cantidad','departamentos.nombre AS departamento','municipios.nombre AS municipio','decomisos.fecha')
        //     ->whereNull('arma_decomiso.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     ->get();
        // //dd($decomisos_armas);
        // return view('admin.informe.armas',compact('decomisos_armas'));







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

            $decomisos_armas=DB::table('arma_decomiso')
                ->join('decomisos', 'arma_decomiso.decomiso_id', '=', 'decomisos.id')
                ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
                //->join('presentacion_armas', 'arma_decomiso.presentacion_precursor_id', '=', 'presentacion_armas.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->select('armas.descripcion AS arma',
                        'cantidad',
                        'departamentos.nombre AS departamento',
                        'municipios.nombre AS municipio',
                        'decomisos.fecha AS fecha')
                ->whereNull('arma_decomiso.deleted_at')
                ->whereNull('decomisos.deleted_at');
                    
            if($searchValue!=null){
                $decomisos_armas=$decomisos_armas->where(function ($query) use ($searchValue) {
                    $query->where('armas.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('cantidad','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('decomisos.fecha','like','%'. $searchValue.'%');
                });
            }
            
                
            $total=$decomisos_armas->count();

            $totalFilter=$decomisos_armas;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'arma':
                            $totalFilter= $totalFilter->where('armas.descripcion','like','%'. $columna['search']['value'].'%');
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
            return view('admin.informe.armas');
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
        $decomiso=Decomiso::find($request->arma_decomiso_id);
        // $rules = [
        //     'arma_id'=> 'required',
        //     'cantidadArma'=> 'required',
        // ];

        // $this->validate($request, $rules);

        // $decomiso=Decomiso::find($request->arma_decomiso_id);
        
        $validator = Validator::make($request->all(), [
            'cantidadArma'=> 'required',
            'arma_id'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-armas');
            $request->session()->flash('modal', '#createArmaModal');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }
        
        $decomiso->armas()->attach([$request->arma_id => ['cantidad'=>$request->cantidadArma]]);

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-armas');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El arma ha sido agregada al decomiso.');;
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
        $decomiso=Decomiso::find($request->arma_decomiso_id_);
        $validator = Validator::make($request->all(), [
            'armaOcultoId_'=> 'required',
            'cantidadArma_'=> 'required',
            'arma_decomiso_id_'=> 'required',
            'arma_id_'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-armas');
            $request->session()->flash('modal', '#editArmaModal');
            $request->session()->flash('form1', '#form_edit_arma');
            $request->session()->flash('form2', '#form_delete_arma');
            $request->session()->flash('formRoute', '/admin/decomisoarma/');
            $request->session()->flash('formID', $request->armaOcultoId_);
            $request->session()->flash('oculto', '#armaOcultoId_');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $decomiso_arma = decomiso_arma::find($request->armaOcultoId_);
        $decomiso_arma->cantidad = $request->cantidadArma_;
        $decomiso_arma->decomiso_id = $request->arma_decomiso_id_;
        $decomiso_arma->arma_id = $request->arma_id_!=null?$request->arma_id_:$decomiso_arma->arma_id;
        $decomiso_arma->save();

        event(new DecomisoBitacorae($decomiso));
        
        $request->session()->flash('modelo', '#pills-armas');
        //$request['kdk']="joos";
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El arma ha sido editada en el decomiso.');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $decomiso_arma = decomiso_arma::find($id);
        $decomiso_arma->delete();

        $decomiso=Decomiso::find($decomiso_arma->decomiso_id);
        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-armas');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El arma ha sido eliminada en el decomiso.');;
    }

    public function restaurar(Request $request, $Id)
    {
        //dd($Id);
        decomiso_arma::withTrashed()->find($Id)->restore();
        $request->session()->flash('modelo', '#pills-armas');
        return back()->with('flash', 'El arma ha sido restaurada en el decomiso.');
    }

    public function habilitados($Id, $tipo)
    {
        // debug($Id);
        // debug($tipo);
        // //debug($tipo=="habilitados");
        // $decomiso=Decomiso::find($Id);

        // $armas=collect();

        // if ($tipo=="habilitados") {
        //     foreach ($decomiso->armas as $arma) {
        //         if ($arma->pivot->deleted_at==null) {
        //             debug($arma->pivot->deleted_at);
        //             $armas->push($arma);
        //         }
        //     }
        // } else {
        //     foreach ($decomiso->armas as $arma) {
        //         if ($arma->pivot->deleted_at!=null) {
        //             debug($arma->pivot->deleted_at);
        //             $armas->push($arma);
        //         }
        //     }
        // }

        $deco_dro=Decomiso::find($Id)->armas->where('pivot.deleted_at',($tipo=='habilitados'?'==':'!='),null)->sortByDesc('created_at');
        
        $armas=collect();
        foreach ($deco_dro as $elem) {
            $armas->push($elem);
        }

        debug($armas);

        return response()->json(['decomisos_habilitados'=>$armas]);
    }
}
