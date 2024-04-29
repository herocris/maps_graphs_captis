<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\TipoDroga;
use App\Models\Departamento;
use App\Models\Pais;
use App\Models\Municipio;
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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\DecomisoBitacorae;

class DecomisoDrogaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

        $this->middleware(['permission:ver decomisos de droga'])->only('index');
        $this->middleware(['permission:crear decomisos de droga'])->only('store');
        $this->middleware(['permission:editar decomisos de droga'])->only('update');
        $this->middleware(['permission:borrar decomisos de droga'])->only('destroy');
    }
    public function index(Request $request)
    {
        debug("ds");

        // $decomisos_drogas=DB::table('decomiso_droga')
        //     ->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')
        //     ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
        //     ->join('presentacion_drogas', 'decomiso_droga.presentacion_droga_id', '=', 'presentacion_drogas.id')
        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('drogas.descripcion AS droga','presentacion_drogas.descripcion AS presentacion_droga','cantidad','peso','departamentos.nombre AS departamento','municipios.nombre AS municipio','decomisos.fecha')
        //     ->whereNull('decomiso_droga.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     ->get();
        // debug($decomisos_drogas);
        // $dehabilitado=false;
        // // $decomisos=Decomiso::all();
        // // $drogas=collect();
        // // foreach ($decomisos as $decomiso) {
        // //     foreach ($decomiso->drogas as $droga) {
        // //         $droga['fecha']=$decomiso->fecha;
        // //         $droga['municipio']=$decomiso->municipio->nombre;
        // //         $droga['departamento']=$decomiso->municipio->departamento->nombre;
        // //         if($droga->pivot->deleted_at==null){
        // //             $drogas->push($droga);
        // //         }

        // //     }
        // // }
        // // debug($drogas);
        // return view('admin.informe.drogas',compact('decomisos_drogas','dehabilitado'));

/////////////////////////////////////fin logica anterior///////////////////////////////////////////

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

            $decomisos_drogas=DB::table('decomiso_droga')
            ->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')
            ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
            ->join('presentacion_drogas', 'decomiso_droga.presentacion_droga_id', '=', 'presentacion_drogas.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
            ->select('drogas.descripcion AS droga',
                    'presentacion_drogas.descripcion AS presentacion_droga',
                    'cantidad',
                    'peso',
                    'decomiso_id',
                    'departamentos.nombre AS departamento',
                    'municipios.nombre AS municipio',
                    'decomisos.fecha AS fecha')
            ->whereNull('decomiso_droga.deleted_at')
            ->whereNull('decomisos.deleted_at');

            if($searchValue!=null){
                $decomisos_drogas=$decomisos_drogas->where(function ($query) use ($searchValue) {
                    $query->where('drogas.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('presentacion_drogas.descripcion','like','%'. $searchValue.'%')
                    ->orWhere('cantidad','like','%'. $searchValue.'%')
                    ->orWhere('peso','like','%'. $searchValue.'%')
                    ->orWhere('departamentos.nombre','like','%'. $searchValue.'%')
                    ->orWhere('municipios.nombre','like','%'. $searchValue.'%')
                    ->orWhere('decomisos.fecha','like','%'. $searchValue.'%');
                });
            }


            $total=$decomisos_drogas->count();

            $totalFilter=$decomisos_drogas;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'droga':
                            $totalFilter= $totalFilter->where('drogas.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'presentacion_droga':
                            $totalFilter= $totalFilter->where('presentacion_drogas.descripcion','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'cantidad':
                            $totalFilter= $totalFilter->where('cantidad','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'peso':
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
                //debug($request->get("fec_ini"));
                //debug($request->get("fec_fin"));
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
            return view('admin.informe.drogas');
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
        $decomiso=Decomiso::find($request->droga_decomiso_id);
        // $rules = [
        //     'descripcion_droga'=> 'required',
        //     'cantidad'=> 'required',
        //     'peso'=> 'required',
        //     'presentacion_droga'=> 'required',
        // ];

        // $this->validate($request, $rules);
        $messages = [
            'integer' => 'La cantidad debe ser un numero',
            'Numeric' => 'El peso debe ser un numero'
        ];
        $validator = Validator::make($request->all(), [
            'droga_id'=> 'required',
            'drogaCantidad'=> 'required|integer',
            'drogaPeso'=> 'required|Numeric',
            'presentacion_droga_id'=> 'required',
        ],$messages);


        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-drogas');
            $request->session()->flash('modal', '#createDrogaModal');
            debug("validador fallo");
            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();
        }
        //dd($request);


        $decomiso->drogas()->attach([
            $request->droga_id => [
                'cantidad'=>$request->drogaCantidad,
                'peso'=>$request->drogaPeso,
                'presentacion_droga_id'=>$request->presentacion_droga_id
            ]
        ]);

        event(new DecomisoBitacorae($decomiso));
        // $decomiso->user_update=Auth::user()->id;
        // $decomiso->save();
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'La droga ha sido agregada al decomiso.');
    //
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
        $decomisos=Decomiso::all();
        $drogas=collect();
        foreach ($decomisos as $decomiso) {
            foreach ($decomiso->drogas as $droga) {
                $droga['fecha']=$decomiso->fecha;
                $droga['municipio']=$decomiso->municipio->nombre;
                $droga['departamento']=$decomiso->municipio->departamento->nombre;
                $drogas->push($droga);
            }
        }
        //dd($drogas);
        return view('admin.informe.drogas',compact('drogas','dehabilitado'));
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
        //dd("llego aqui");
        $decomiso=Decomiso::find($request->droga_decomiso_id_);
        $messages = [
            'integer' => 'La cantidad debe ser un numero',
            'Numeric' => 'El peso debe ser un numero'
        ];
        $validator = Validator::make($request->all(), [
            'droga_id_'=> 'required',
            'presentacion_droga_id_'=> 'required',
            'drogaCantidad_'=> 'required|integer',
            'drogaPeso_'=> 'required|Numeric',
            //'presentacion_droga_id_'=> 'required',
        ]);
        if ($validator->fails()) {
            //dd("llega aqui");
            $request->session()->flash('modelo', '#pills-drogas');
            $request->session()->flash('modal', '#editDrogaModal');
            $request->session()->flash('form1', '#form_edit_droga');
            $request->session()->flash('form2', '#form_delete_droga');
            $request->session()->flash('formRoute','/admin/decomisodroga/');
            $request->session()->flash('formID', $request->drogaOcultoId_);
            $request->session()->flash('oculto', '#drogaOcultoId_');
            //dd("ho");
            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();
        }
        //dd($request->droga_id_);
        $decomiso_drogas = decomiso_droga::find($request->drogaOcultoId_);
        $decomiso_drogas->cantidad = $request->drogaCantidad_;
        $decomiso_drogas->peso = $request->drogaPeso_;
        $decomiso_drogas->decomiso_id = $request->droga_decomiso_id_;
        $decomiso_drogas->droga_id = $request->droga_id_!=null?$request->droga_id_:$decomiso_drogas->droga_id;
        $decomiso_drogas->presentacion_droga_id = $request->presentacion_droga_id_!=null?$request->presentacion_droga_id_:$decomiso_drogas->presentacion_droga_id;
        $decomiso_drogas->save();
        ////////logica nueva//////////////////
        // $decomiso->drogas()->sync([$request->droga_id_ => [
        //     'cantidad'=>$request->drogaCantidad_,
        //     'peso'=>$request->drogaPeso_,
        //     'presentacion_droga_id'=>$request->presentacion_droga_id_
        // ]]);
        /////////fin logica nueva////////////


        event(new DecomisoBitacorae($decomiso));
        // $decomiso->user_update=Auth::user()->id;
        // $decomiso->save();



        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'La droga ha sido editada en el decomiso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $decomiso_drogas = decomiso_droga::find($id);
        //dd($decomiso_drogas);
        $decomiso_drogas->delete();

        $decomiso=Decomiso::find($decomiso_drogas->decomiso_id);
        event(new DecomisoBitacorae($decomiso));
        // $decomiso->user_update=Auth::user()->id;
        // $decomiso->save();
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])->with('flash', 'La droga ha sido eliminada del decomiso.');
    }

    public function restaurar($Id)
    {
        //dd($Id);
        decomiso_droga::withTrashed()->find($Id)->restore();

        return back()->with('flash', 'La droga ha sido restaurada en el decomiso.');
    }

    public function habilitados($Id, $tipo)
    {
        $deco_dro=Decomiso::find($Id)->drogas->where('pivot.deleted_at',($tipo=='habilitados'?'==':'!='),null)->sortByDesc('pivot.created_at');

        $drogas=collect();
        foreach ($deco_dro as $elem) {
            $drogas->push($elem);
        }

        debug($drogas);



        return response()->json(['decomisos_habilitados'=>$drogas]);
    }


}
