<?php

namespace App\Http\Controllers\Admin;

use App\Models\Decomiso;
use App\Models\decomiso_transporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\DecomisoBitacorae;

class DecomisoTransportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['permission:ver decomisos de transportes'])->only('index'); 
        $this->middleware(['permission:crear decomisos de transportes'])->only('store');
        $this->middleware(['permission:editar decomisos de transportes'])->only('update');
        $this->middleware(['permission:borrar decomisos de transportes'])->only('destroy');
    }
    public function index(Request $request)
    {
        // $paises=DB::table('pais')->get();
        // $departamentos=DB::table('departamentos')->get();
        // $municipios=DB::table('municipios')->get();
        // $decomisos_transportes=DB::table('decomiso_transportes')
        //     ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
        //     //->join('tipo_municions', 'decomiso_transportes.tipo_municion_id', '=', 'tipo_municions.id')

        //     // ->join('pais AS pais_pro', 'decomiso_transportes.pais_pro_id', '=', 'pais_pro.id')
        //     // ->join('pais AS pais_des', 'decomiso_transportes.pais_des_id', '=', 'pais_des.id')
        //     // ->join('departamentos AS dep_pro', 'decomiso_transportes.dep_pro_id', '=', 'dep_pro.id')
        //     // ->join('departamentos AS dep_des', 'decomiso_transportes.dep_des_id', '=', 'dep_des.id')
        //     // ->join('municipios AS mun_pro', 'decomiso_transportes.mun_pro_id', '=', 'mun_pro.id')
        //     // ->join('municipios AS mun_des', 'decomiso_transportes.mun_des_id', '=', 'mun_des.id')

        //     //->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     //->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
        //     ->select('decomiso_transportes.placa',
        //             'decomiso_transportes.marca',
        //             'decomiso_transportes.modelo',
        //             'decomiso_transportes.color',
        //             'decomiso_transportes.tipo',

        //             // 'pais_pro.nombre AS pais_pro',
        //             // 'pais_des.nombre AS pais_des',
        //             // 'dep_pro.nombre AS dep_pro',
        //             // 'dep_des.nombre AS dep_des',
        //             // 'mun_pro.nombre AS mun_pro',
        //             // 'mun_des.nombre AS mun_des',

        //             'decomiso_transportes.pais_pro_id',
        //             'decomiso_transportes.pais_des_id',
        //             'decomiso_transportes.dep_pro_id',
        //             'decomiso_transportes.dep_des_id',
        //             'decomiso_transportes.mun_pro_id',
        //             'decomiso_transportes.mun_des_id',
        //             'decomisos.fecha')
        //     ->whereNull('decomiso_transportes.deleted_at')
        //     ->whereNull('decomisos.deleted_at')
        //     //->whereNotNull('decomiso_transportes.dep_pro_id')
        //     //->whereNotNull('decomiso_transportes.dep_pro_id')
        //     ->get();
        // //dd($paises);
        

        // foreach ($decomisos_transportes as $deco) {
        //     $deco->pais_pro_id=$paises->first(function ($pais) use ($deco) {
        //         return $pais->id == $deco->pais_pro_id;
        //     })->nombre;

        //     $deco->pais_des_id=$paises->first(function ($pais) use ($deco) {
        //         return $pais->id == $deco->pais_des_id;
        //     })->nombre;

        //     $depro=$departamentos->first(function ($depto) use ($deco) {
        //         return $depto->id == $deco->dep_pro_id;
        //     });
        //     $deco->dep_pro_id=$depro==null?'':$depro->nombre;

        //     $depdes=$departamentos->first(function ($depto) use ($deco) {
        //         return $depto->id == $deco->dep_des_id;
        //     });
        //     $deco->dep_des_id=$depdes==null?'':$depdes->nombre;
            
        //     $munpro=$municipios->first(function ($muni) use ($deco) {
        //         return $muni->id == $deco->mun_pro_id;
        //     });
        //     $deco->mun_pro_id=$munpro==null?'':$munpro->nombre;

        //     $mundes=$municipios->first(function ($muni) use ($deco) {
        //         return $muni->id == $deco->mun_des_id;
        //     });
        //     $deco->mun_des_id=$mundes==null?'':$mundes->nombre;
        //     //$deco->pais_pro_id=$pais->nombre;
        //     //dd($deco->dep_pro_id);
        //     # code...
        // }
        // //dd($decomisos_transportes);
        // return view('admin.informe.transportes',compact('decomisos_transportes'));




        //////////////////////logica nueva////////////////////////////////////

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

            $decomisos_transportes=DB::table('decomiso_transportes')
            ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            ->leftJoin('pais AS pais_p', 'decomiso_transportes.pais_pro_id', '=', 'pais_p.id')///leftJoin para mostrar los registros nulos
            ->leftJoin('pais AS pais_d', 'decomiso_transportes.pais_des_id', '=', 'pais_d.id')
            ->leftJoin('departamentos AS dep_p', 'decomiso_transportes.dep_pro_id', '=', 'dep_p.id')
            ->leftJoin('departamentos AS dep_d', 'decomiso_transportes.dep_des_id', '=', 'dep_d.id')
            ->leftJoin('municipios AS mun_p', 'decomiso_transportes.mun_pro_id', '=', 'mun_p.id')
            ->leftJoin('municipios AS mun_d', 'decomiso_transportes.mun_des_id', '=', 'mun_d.id')
            // ->leftJoin('municipios AS mun_d', function($join) {
            //     $join->on('decomiso_transportes.mun_des_id', '=', 'mun_d.id');
            // })
            //->whereNull('decomiso_transportes.mun_des_id')
            //->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            //->join('departamentos', 'municipios.departamento_id', '=', 'departamentos.id')
            ->select('placa',
                    'marca',
                    'modelo',
                    'color',
                    'tipo',

                    'pais_p.nombre AS pais_pr',
                    'pais_d.nombre AS pais_de',
                    'dep_p.nombre AS dep_pr',
                    'dep_d.nombre AS dep_de',
                    'mun_p.nombre AS mun_pr',
                     'mun_d.nombre AS mun_de',

                    // 'decomiso_transportes.pais_pro_id',
                    // 'decomiso_transportes.pais_des_id',
                    // 'decomiso_transportes.dep_pro_id',
                    // 'decomiso_transportes.dep_des_id',
                    // 'decomiso_transportes.mun_pro_id',
                    // 'decomiso_transportes.mun_des_id',
                    'decomisos.fecha AS fecha')
            ->whereNull('decomiso_transportes.deleted_at')
            // ->whereNull('pais_p.nombre')
            // ->whereNull('pais_d.nombre')
            // ->whereNull('dep_p.nombre')
            // ->whereNull('dep_d.nombre')
            // ->whereNull('mun_p.nombre')
            //->whereNull('decomiso_transportes.mun_des_id')
            ->whereNull('decomisos.deleted_at');
            //->whereNotNull('decomiso_transportes.dep_pro_id')
            //->whereNotNull('decomiso_transportes.dep_pro_id');
                    
            if($searchValue!=null){
                $decomisos_transportes=$decomisos_transportes->where(function ($query) use ($searchValue) {
                    $query->where('placa','like','%'. $searchValue.'%')
                    ->orWhere('marca','like','%'. $searchValue.'%')
                    ->orWhere('modelo','like','%'. $searchValue.'%')
                    ->orWhere('color','like','%'. $searchValue.'%')
                    ->orWhere('tipo','like','%'. $searchValue.'%')
                    ->orWhere('pais_p.nombre','like','%'. $searchValue.'%')
                    ->orWhere('pais_d.nombre','like','%'. $searchValue.'%')
                    ->orWhere('dep_p.nombre','like','%'. $searchValue.'%')
                    ->orWhere('dep_d.nombre','like','%'. $searchValue.'%')
                    ->orWhere('mun_p.nombre','like','%'. $searchValue.'%')
                    ->orWhere('mun_d.nombre','like','%'. $searchValue.'%')
                    
                    ->orWhere([['placa','like','%'. $searchValue.'%'],
                            ['marca','like','%'. $searchValue.'%']])
                    ;
                });
            }
            
                
            $total=$decomisos_transportes->count();

            $totalFilter=$decomisos_transportes;
            //////////para filtrar por columas
            foreach ($columnNameArray as $columna) {
                if($columna['search']['value']!=null){
                    switch ($columna['data']) {
                        case 'placa':
                            $totalFilter= $totalFilter->where('placa','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'marca':
                            $totalFilter= $totalFilter->where('marca','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'modelo':
                            $totalFilter= $totalFilter->where('modelo','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'color':
                            $totalFilter= $totalFilter->where('color','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'tipo':
                            $totalFilter= $totalFilter->where('tipo','like','%'. $columna['search']['value'].'%');
                            break;  
                        case 'pais_pr':
                            $totalFilter= $totalFilter->where('pais_p.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'pais_de':
                            $totalFilter= $totalFilter->where('pais_d.nombre','like','%'. $columna['search']['value'].'%');
                            break; 
                        case 'dep_pr':
                            $totalFilter= $totalFilter->where('dep_p.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'dep_de':
                            $totalFilter= $totalFilter->where('dep_d.nombre','like','%'. $columna['search']['value'].'%');
                            break;  
                        case 'mun_pr':
                            $totalFilter= $totalFilter->where('mun_p.nombre','like','%'. $columna['search']['value'].'%');
                            break;
                        case 'mun_de':
                            $totalFilter= $totalFilter->where('mun_d.nombre','like','%'. $columna['search']['value'].'%');
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
            debug($response);
            return response()->json($response);
        }else{
            return view('admin.informe.transportes');
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
        $decomiso=Decomiso::find($request->transporte_decomiso_id);
        // $rules = [
        //     'transporte_decomiso_id'=> 'required',
        //     'tipo_transporte'=> 'required',
        //     'marca'=> 'required',
        //     'modelo'=> 'required',
        //     'color'=> 'required',
        //     'placa'=> 'required',
        //     'pais_pro'=> 'required',
        //     'pais_des'=> 'required',
            
        // ];

        // $this->validate($request, $rules);
        //dd($request->pais_pro);
        if($request->pais_pro==87 && $request->pais_des==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id'=> 'required',
                'tipo_transporte'=> 'required',
                //'marca'=> 'required',
                //'modelo'=> 'required',
                'color'=> 'required',
                //'placa'=> 'required',
                'pais_pro'=> 'required',
                'pais_des'=> 'required',
                'dep_pro'=> 'required',
                'dep_des'=> 'required',
                'mun_pro'=> 'required',
                'mun_des'=> 'required',
            ]);
        }else if($request->pais_pro==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id'=> 'required',
                'tipo_transporte'=> 'required',
                //'marca'=> 'required',
                //'modelo'=> 'required',
                'color'=> 'required',
                //'placa'=> 'required',
                'pais_pro'=> 'required',
                'pais_des'=> 'required',
                'dep_pro'=> 'required',
                //'dep_des'=> 'required',
                'mun_pro'=> 'required',
                //'mun_des'=> 'required',
            ]);
        }else if($request->pais_des==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id'=> 'required',
                'tipo_transporte'=> 'required',
                //'marca'=> 'required',
                //'modelo'=> 'required',
                'color'=> 'required',
                //'placa'=> 'required',
                'pais_pro'=> 'required',
                'pais_des'=> 'required',
                //'dep_pro'=> 'required',
                'dep_des'=> 'required',
                //'mun_pro'=> 'required',
                'mun_des'=> 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id'=> 'required',
                'tipo_transporte'=> 'required',
                //'marca'=> 'required',
                //'modelo'=> 'required',
                'color'=> 'required',
                //'placa'=> 'required',
                'pais_pro'=> 'required',
                'pais_des'=> 'required',
            ]);
        }   
        
        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-transportes');
            $request->session()->flash('modal', '#createTransporteModal');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $transporte=new decomiso_transporte;
        $transporte->tipo=$request->tipo_transporte;
        $transporte->marca=$request->marca;
        $transporte->modelo=$request->modelo;
        $transporte->color=$request->color;
        $transporte->placa=$request->placa;
        $transporte->decomiso_id=$request->transporte_decomiso_id;
        $transporte->pais_pro_id=$request->pais_pro;
        $transporte->pais_des_id=$request->pais_des;
        $transporte->dep_pro_id=$request->dep_pro;
        $transporte->dep_des_id=$request->dep_des;
        $transporte->mun_pro_id=$request->mun_pro;
        $transporte->mun_des_id=$request->mun_des;

        $transporte->save();

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-transportes');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso, 'modelo' => 'arma']);
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
        $decomiso=Decomiso::find($request->transporte_decomiso_id_);
        // $rules = [
        //     'transporte_decomiso_id_'=> 'required',
        //     'tipo_transporte_'=> 'required',
        //     'marca_'=> 'required',
        //     'modelo_'=> 'required',
        //     'color_'=> 'required',
        //     'placa_'=> 'required',
        //     'pais_pro_'=> 'required',
        //     'pais_des_'=> 'required',
            
        // ];

        // $this->validate($request, $rules);

        // $validator = Validator::make($request->all(), [
        //     'transporte_decomiso_id_'=> 'required',
        //     'tipo_transporte_'=> 'required',
        //     'marca_'=> 'required',
        //     'modelo_'=> 'required',
        //     'color_'=> 'required',
        //     'placa_'=> 'required',
        //     'pais_pro_'=> 'required',
        //     'pais_des_'=> 'required',
        // ]);


        if($request->pais_pro_==87 && $request->pais_des_==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id_'=> 'required',
                'tipo_transporte_'=> 'required',
                //'marca_'=> 'required',
                //'modelo_'=> 'required',
                'color_'=> 'required',
                //'placa_'=> 'required',
                'pais_pro_'=> 'required',
                'pais_des_'=> 'required',
                'dep_pro_'=> 'required',
                'dep_des_'=> 'required',
                'mun_pro_'=> 'required',
                'mun_des_'=> 'required',
            ]);
        }else if($request->pais_pro_==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id_'=> 'required',
                'tipo_transporte_'=> 'required',
                //'marca_'=> 'required',
                //'modelo_'=> 'required',
                'color_'=> 'required',
                //'placa_'=> 'required',
                'pais_pro_'=> 'required',
                'pais_des_'=> 'required',
                'dep_pro_'=> 'required',
                //'dep_des'=> 'required',
                'mun_pro_'=> 'required',
                //'mun_des'=> 'required',
            ]);
        }else if($request->pais_des_==87){
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id_'=> 'required',
                'tipo_transporte_'=> 'required',
                //'marca_'=> 'required',
                //'modelo_'=> 'required',
                'color_'=> 'required',
                //'placa_'=> 'required',
                'pais_pro_'=> 'required',
                'pais_des_'=> 'required',
                //'dep_pro'=> 'required',
                'dep_des_'=> 'required',
                //'mun_pro'=> 'required',
                'mun_des_'=> 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'transporte_decomiso_id_'=> 'required',
                'tipo_transporte_'=> 'required',
                //'marca_'=> 'required',
                //'modelo_'=> 'required',
                'color_'=> 'required',
                //'placa_'=> 'required',
                'pais_pro_'=> 'required',
                'pais_des_'=> 'required',
            ]);
        } 


        if ($validator->fails()) {
            $request->session()->flash('modelo', '#pills-transportes');
            $request->session()->flash('modal', '#editTransporteModal');
            $request->session()->flash('form1', '#form_edit_transporte');
            $request->session()->flash('form2', '#form_delete_transporte');
            $request->session()->flash('formRoute', '/admin/decomisotransporte/');
            $request->session()->flash('formID', $id);
            //$request->session()->flash('oculto', '#transporteOcultoId_');

            return redirect()->route('decomiso.edit', ['decomiso' => $decomiso])
                        ->withErrors($validator)
                        ->withInput();     
        }

        $transporte=decomiso_transporte::find($id);
        $transporte->tipo=$request->tipo_transporte_;
        $transporte->marca=$request->marca_;
        $transporte->modelo=$request->modelo_;
        $transporte->color=$request->color_;
        $transporte->placa=$request->placa_;
        $transporte->decomiso_id=$request->transporte_decomiso_id_;
        $transporte->pais_pro_id=$request->pais_pro_;
        $transporte->pais_des_id=$request->pais_des_;
        $transporte->dep_pro_id=$request->dep_pro_;
        $transporte->dep_des_id=$request->dep_des_;
        $transporte->mun_pro_id=$request->mun_pro_;
        $transporte->mun_des_id=$request->mun_des_;

        $transporte->save();

        event(new DecomisoBitacorae($decomiso));

        $request->session()->flash('modelo', '#pills-transportes');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso, 'modelo' => 'arma']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //dd($id);
        $transporte = decomiso_transporte::find($id);
        $transporte->delete();

        $decomiso=Decomiso::find($transporte->decomiso_id);

        event(new DecomisoBitacorae($decomiso));
        
        $request->session()->flash('modelo', '#pills-transportes');
        return redirect()->route('decomiso.edit', ['decomiso' => $decomiso]);
    }

    public function restaurar(Request $request, $Id)
    {
        //dd($Id);
        decomiso_transporte::withTrashed()->find($Id)->restore();
        $request->session()->flash('modelo', '#pills-transportes');
        return back();
    }

    public function habilitados($Id, $tipo)
    {
        // debug($Id);
        // debug($tipo);

        // $decomiso=Decomiso::find($Id);

        // $transportes=collect();
        // if ($tipo=="habilitados") {
        //     foreach ($decomiso->transportes as $transporte) {
        //         if ($transporte->deleted_at==null) {
        //             $transporte['pais_pro']=$transporte->paiss($transporte->pais_pro_id);
        //             $transporte['pais_des']=$transporte->paiss($transporte->pais_des_id);
        //             $transporte['dep_pro']=$transporte->depto($transporte->dep_pro_id);
        //             $transporte['dep_des']=$transporte->depto($transporte->dep_des_id);
        //             $transporte['mun_pro']=$transporte->muni($transporte->mun_pro_id);
        //             $transporte['mun_des']=$transporte->muni($transporte->mun_des_id);
        //             $transportes->push($transporte);
        //         }
        //     }
        // } else {
        //     foreach ($decomiso->transportes as $transporte) {
        //         if ($transporte->deleted_at!=null) {
        //             $transporte['pais_pro']=$transporte->paiss($transporte->pais_pro_id);
        //             $transporte['pais_des']=$transporte->paiss($transporte->pais_des_id);
        //             $transporte['dep_pro']=$transporte->depto($transporte->dep_pro_id);
        //             $transporte['dep_des']=$transporte->depto($transporte->dep_des_id);
        //             $transporte['mun_pro']=$transporte->muni($transporte->mun_pro_id);
        //             $transporte['mun_des']=$transporte->muni($transporte->mun_des_id);
        //             $transportes->push($transporte);
        //         }
        //     }
        // }


        $paises=DB::table('pais')->get();
        $departamentos=DB::table('departamentos')->get();
        $municipios=DB::table('municipios')->get();

        if ($tipo=="habilitados") {
            $decomisos_transportes=DB::table('decomiso_transportes')
            ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            ->where('decomiso_id',$Id)
            ->select('decomiso_transportes.placa',
                    'decomiso_transportes.marca',
                    'decomiso_transportes.modelo',
                    'decomiso_transportes.color',
                    'decomiso_transportes.tipo',
                    'decomiso_transportes.pais_pro_id AS pais_pro',
                    'decomiso_transportes.pais_des_id AS pais_des',
                    'decomiso_transportes.dep_pro_id AS dep_pro',
                    'decomiso_transportes.dep_des_id AS dep_des',
                    'decomiso_transportes.mun_pro_id AS mun_pro',
                    'decomiso_transportes.mun_des_id AS mun_des',
                    'decomiso_transportes.pais_pro_id',
                    'decomiso_transportes.pais_des_id',
                    'decomiso_transportes.dep_pro_id',
                    'decomiso_transportes.dep_des_id',
                    'decomiso_transportes.mun_pro_id',
                    'decomiso_transportes.mun_des_id',
                    'decomiso_transportes.deleted_at',
                    'decomiso_transportes.id',
                    'decomisos.fecha')
            ->whereNull('decomiso_transportes.deleted_at')
            ->get();
        } else {
            $decomisos_transportes=DB::table('decomiso_transportes')
            ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            ->where('decomiso_id',$Id)
            ->select('decomiso_transportes.placa',
                    'decomiso_transportes.marca',
                    'decomiso_transportes.modelo',
                    'decomiso_transportes.color',
                    'decomiso_transportes.tipo',
                    'decomiso_transportes.pais_pro_id AS pais_pro',
                    'decomiso_transportes.pais_des_id AS pais_des',
                    'decomiso_transportes.dep_pro_id AS dep_pro',
                    'decomiso_transportes.dep_des_id AS dep_des',
                    'decomiso_transportes.mun_pro_id AS mun_pro',
                    'decomiso_transportes.mun_des_id AS mun_des',
                    'decomiso_transportes.pais_pro_id',
                    'decomiso_transportes.pais_des_id',
                    'decomiso_transportes.dep_pro_id',
                    'decomiso_transportes.dep_des_id',
                    'decomiso_transportes.mun_pro_id',
                    'decomiso_transportes.mun_des_id',
                    'decomiso_transportes.deleted_at',
                    'decomiso_transportes.id',
                    'decomisos.fecha')
            ->whereNotNull('decomiso_transportes.deleted_at')
            ->get();
        }
        
        

        foreach ($decomisos_transportes as $deco) {
            $deco->pais_pro=$paises->first(function ($pais) use ($deco) {
                return $pais->id == $deco->pais_pro_id;
            })->nombre;

            $deco->pais_des=$paises->first(function ($pais) use ($deco) {
                return $pais->id == $deco->pais_des_id;
            })->nombre;

            $depro=$departamentos->first(function ($depto) use ($deco) {
                return $depto->id == $deco->dep_pro_id;
            });
            $deco->dep_pro=$depro==null?'':$depro->nombre;

            $depdes=$departamentos->first(function ($depto) use ($deco) {
                return $depto->id == $deco->dep_des_id;
            });
            $deco->dep_des=$depdes==null?'':$depdes->nombre;
            
            $munpro=$municipios->first(function ($muni) use ($deco) {
                return $muni->id == $deco->mun_pro_id;
            });
            $deco->mun_pro=$munpro==null?'':$munpro->nombre;

            $mundes=$municipios->first(function ($muni) use ($deco) {
                return $muni->id == $deco->mun_des_id;
            });
            $deco->mun_des=$mundes==null?'':$mundes->nombre;
        }
        debug($decomisos_transportes);
        return response()->json(['decomisos_habilitados'=>$decomisos_transportes]);
    }
}
