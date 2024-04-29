<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\decomiso_precursor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\DecomisoBitacorae;

class DecomisoPrecursorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver decomisos de precursores'])->only('index'); 
        $this->middleware(['permission:crear decomisos de precursores'])->only('store');
        $this->middleware(['permission:editar decomisos de precursores'])->only('update');
        $this->middleware(['permission:borrar decomisos de precursores'])->only('destroy');
    }    
    public function index(Request $request)
    {
        // $decomisos_precursor=DB::table('decomiso_precursor')
        //     ->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')
        //     ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
        //     ->join('presentacion_precursors', 'decomiso_precursor.presentacion_precursor_id', '=', 'presentacion_precursors.id')
        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('precursors.descripcion AS precursor','presentacion_precursors.descripcion AS presentacion_precursor','cantidad','volumen','departamentos.nombre AS departamento','municipios.nombre AS municipio','decomisos.fecha')
        //     ->whereNull('decomiso_precursor.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     ->get();
        // //dd($decomisos_precursor);
        // return view('admin.informe.precursores',compact('decomisos_precursor'));

        ///////////////////////logica anterior///////////////////////////////////////
        if(count($request->all()) > 0) {////////////////////si el request viene vacío simplemente llena la tabla con la pagina inicial; y si no ejecuta los filtros
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

            $decomisos_precursores=DB::table('decomiso_precursor')
                ->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')
                ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
                ->join('presentacion_precursors', 'decomiso_precursor.presentacion_precursor_id', '=', 'presentacion_precursors.id')
                ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
                ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
                ->select('precursors.descripcion AS precursor',
                        'presentacion_precursors.descripcion AS presentacion_precursor',
                        'cantidad',
                        'volumen',
                        'departamentos.nombre AS departamento',
                        'municipios.nombre AS municipio',
                        'decomisos.fecha')
                ->whereNull('decomiso_precursor.deleted_at')
                ->whereNull('decomisos.deleted_at');
                    
            if($searchValue!=null){
                $decomisos_precursores=$decomisos_precursores->where(function ($query) use ($searchValue) {
                    $query->where('precursors.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('presentacion_precursors.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('cantidad','like','%'. $searchValue.'%')
                    ->orWhere('volumen','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('decomisos.fecha','like','%'. $searchValue.'%');
                });
            }
            
                
            $total=$decomisos_precursores->count();

            $totalFilter=$decomisos_precursores;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'precursor':
                            $totalFilter= $totalFilter->where('drogas.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'presentacion_precursor':
                            $totalFilter= $totalFilter->where('presentacion_drogas.descripcion','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'cantidad':
                            $totalFilter= $totalFilter->where('cantidad','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'volumen':
                            //debug("llega");
                            $totalFilter= $totalFilter->where('peso','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'departamento':
                            //debug("llega");
                            $totalFilter= $totalFilter->where('departamentos.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'municipio':
                            $totalFilter= $totalFilter->where('municipios.nombre','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'fecha':
                            $totalFilter= $totalFilter->where('decomisos.fecha','like','%'. $columna['search']['value'].'%');
                            break;                     
                        default:
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
                $paraimp= clone $totalFilter;
                $imprimir=$paraimp->orderByDesc('fecha')->get();
            }
            
            $filtradototal=$totalFilter->count();///------------
            
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
            return view('admin.informe.precursores');
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
        $decomiso=Decomiso::find($request->precursor_decomiso_id);
        // $rules = [
        //     'precursor_id'=> 'required',
        //     'cantidadPrecursor'=> 'required',
        //     'volumen'=> 'required',
        //     'presentacion_precursor_id'=> 'required',
        // ];

        // $this->validate($request, $rules);

        $validator = Validator::make($request->all(), [
            'precursor_id'=> 'required',
            'cantidadPrecursor'=> 'required',
            'volumen'=> 'required',
            'presentacion_precursor_id'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-precursores');
            $request->session()->flash('modal', '#createPrecursorModal');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $request->session()->flash('modelo', '#pills-precursores');
        //$current_timestamp = Carbon::now()->toDateTimeString();
        //dd($current_timestamp);
        $decomiso->precursores()->attach([$request->precursor_id => ['cantidad'=>$request->cantidadPrecursor, 'volumen'=>$request->volumen, 'presentacion_precursor_id'=>$request->presentacion_precursor_id]]);
        event(new DecomisoBitacorae($decomiso));
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El precursor ha sido agregado al decomiso.');
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
        $decomiso=Decomiso::find($request->precursor_decomiso_id_);
        $validator = Validator::make($request->all(), [
            'precursor_id_'=> 'required',
            'cantidadPrecursor_'=> 'required',
            'volumen_'=> 'required',
            'presentacion_precursor_id_'=> 'required',
        ]);
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-precursores');
            $request->session()->flash('modal', '#editPrecursorModal');
            $request->session()->flash('form1', '#form_edit_precursor');
            $request->session()->flash('form2', '#form_delete_precursor');
            $request->session()->flash('formRoute', '/admin/decomisoprecursor/');
            $request->session()->flash('formID', $request->decomisoprecursorId);
            $request->session()->flash('oculto', '#decomisoprecursorId');


            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }
        //dd($request->presentacion_precursor_id_);
        $decomiso_precursor = decomiso_precursor::find($request->decomisoprecursorId);
        
        $decomiso_precursor->cantidad = $request->cantidadPrecursor_;
        $decomiso_precursor->volumen = $request->volumen_;
        $decomiso_precursor->decomiso_id = $request->precursor_decomiso_id_;
        $decomiso_precursor->precursor_id = $request->precursor_id_!=null?$request->precursor_id_:$decomiso_precursor->precursor_id;
        $decomiso_precursor->presentacion_precursor_id = $request->presentacion_precursor_id_!=null?$request->presentacion_precursor_id_:$decomiso_precursor->presentacion_precursor_id;
        $decomiso_precursor->save();

        event(new DecomisoBitacorae($decomiso));

        
        //$decomiso['algo']="la tab";
        //debug($decomiso);
        $request->session()->flash('modelo', '#pills-precursores');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El precursor ha sido editado en el decomiso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $decomiso_precursor = decomiso_precursor::find($id);
        $decomiso_precursor->delete();

        $decomiso=Decomiso::find($decomiso_precursor->decomiso_id);
        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-precursores');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'El precursor ha sido eliminado en el decomiso.');;
    }

    public function restaurar(Request $request, $Id)
    {
        //dd($Id);
        decomiso_precursor::withTrashed()->find($Id)->restore();
        $request->session()->flash('modelo', '#pills-precursores');
        return back()->with('flash', 'El precursor ha sido restaurado en el decomiso.');;
    }

    public function habilitados($Id, $tipo)
    {
        // debug($Id);
        // debug($tipo);
        // debug($tipo=="habilitados");
        // $decomiso=Decomiso::find($Id);

        // $precursores=collect();

        // if ($tipo=="habilitados") {
        //     foreach ($decomiso->precursores as $precursor) {
        //         if ($precursor->pivot->deleted_at==null) {
        //             debug($precursor->pivot->deleted_at);
        //             $precursores->push($precursor);
        //         }
        //     }
        // } else {
        //     foreach ($decomiso->precursores as $precursor) {
        //         if ($precursor->pivot->deleted_at!=null) {
        //             debug($precursor->pivot->deleted_at);
        //             $precursores->push($precursor);
        //         }
        //     }
        // }

        // foreach ($decomiso->precursores as $precursor) {
        //     if ($tipo=="habilitados" && $precursor->pivot->deleted_at==null) {
        //         $precursores->push($precursor);
        //     }elseif($precursor->pivot->deleted_at!=null){
        //         $precursores->push($precursor);
        //     }
        // }
        $deco_dro=Decomiso::find($Id)->precursores->where('pivot.deleted_at',($tipo=='habilitados'?'==':'!='),null)->sortByDesc('pivot.created_at');
        
        $precursores=collect();
        foreach ($deco_dro as $elem) {
            $precursores->push($elem);
        }

        debug($precursores);

        return response()->json(['decomisos_habilitados'=>$precursores]);
    }
}
