<?php

namespace App\Http\Controllers\Admin;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Institucion;
use App\Models\PresentacionDroga;
use App\Models\TipoDroga;
use App\Models\Droga;
use App\Models\Arma;
use App\Models\TipoMunicion;
use App\Models\Decomiso;
use App\Models\Grafica;
use App\Models\decomiso_droga;
use App\Models\PresentacionPrecursor;
use App\Models\Precursor;
use App\Models\EstructuraCriminal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MapasController extends Controller
{
    public function __construct()
    {

        // $this->middleware(['permission:ver decomisos de droga'])->only('generar_droga');
        // $this->middleware(['permission:crear decomisos de droga'])->only('generar_precursor');
        // $this->middleware(['permission:editar decomisos de droga'])->only('generar_arma');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_municion');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_detenido');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_transporte');

        $this->middleware(['permission:crear mapas'])->only(['generar_droga','mostrar','generar_precursor','generar_arma','generar_municion','generar_detenido','generar_transporte']);
    }

    public function mostrar()
    {
        //dd("sdf");
        $grafica=new Grafica;
        $grafica->pres_drogas=[];
        $grafica->drogas=[];
        $departamentos= Departamento::all();
        $municipios= Municipio::all();
        $instituciones= Institucion::all();
        $dro_pres= PresentacionDroga::all();
        $dro_tipos= TipoDroga::all();
        $dro_noms= Droga::all();
        $arm_noms= Arma::all();
        $mun_noms= TipoMunicion::all();
        $prec_pres= PresentacionPrecursor::all();
        $prec_noms= Precursor::all();
        $estr_noms= EstructuraCriminal::all();
        $periodo=[];
        $decomisos=[];
        return view('admin.mapa.mapa', compact('estr_noms','mun_noms','arm_noms','prec_noms','grafica','periodo','decomisos','dro_noms','prec_pres','dro_tipos','dro_pres','departamentos','municipios','instituciones'));
    }

    public function generar_droga(Request $request)
    {
        $user=Auth::user();
        //debug($request);
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Drogas', 'drogas' => implode(", ",$request->drogas), 'presentación' => implode(", ",$request->pres_drogas), 'tipo_mapa' => $request->tipo_mapa, 'magnitud' => $request->parametro]])
        //->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Drogas', 'drogas' => $request->drogas, 'presentación' => $request->pres_drogas, 'tipo_mapa' => $request->tipo_mapa, 'magnitud' => $request->parametro]])
        ->causedBy($user)
        ->event('created')
        ->useLog($user->name)
        ->log('Se ha generado un mapa de drogas');

        $decomisoso=[];
        $cantidad_total=0;

        $departamentos= DB::table('departamentos')->get();
        $municipios= DB::table('municipios')->get();
        //$drogas_= DB::table('drogas')->get();
        //$presentaciones_drogas= DB::table('presentacion_drogas')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

///////////////////////////////
//////////////////////////////
        // $decomisos_totales111=Departamento::with('municipios.decomisos.drogas')->get()->pluck('municipios');

        //     debug($decomisos_totales111[0]);
/////////////////////////////
/////////////////////////////

        $decomisos_totales=DB::table('decomiso_droga')
            ->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')
            ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
            ->join('presentacion_drogas', 'decomiso_droga.presentacion_droga_id', '=', 'presentacion_drogas.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('presentacion_droga_id',$request->pres_drogas)
            ->whereIn('droga_id', $request->drogas)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_droga.deleted_at')
            ->select('presentacion_droga_id','droga_id','municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','peso','decomisos.fecha','decomisos.municipio_id','decomisos.id','presentacion_drogas.descripcion AS presentacion','drogas.descripcion AS droga')
            ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales[0]);
        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;

            foreach ($departamentos as $departamento) {

                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => ($request->parametro=="cantidad"?$vkd2:round($vkd2,3))]);
                    }
                }
                $municipios_deps=$this->colorear($municipios_deps);
                //debug("aquie");
                //debug($municipios_deps);
                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => ($request->parametro=="cantidad"?$vkd:round($vkd,2)),'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }
            debug("datos a modificars");

            if($request->parametro2=="porcentaje"){
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $drogas=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($drogas, ['descripcion' => $decomiso->droga, 'cantidad' => $decomiso->cantidad, 'peso' => $decomiso->peso, 'presentacion' => $decomiso->presentacion,'fecha' => $decomiso->fecha]);
                    array_push($decomisoso, ['drogas' => $drogas,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['drogas'], ['descripcion' => $decomiso->droga, 'cantidad' => $decomiso->cantidad, 'peso' => $decomiso->peso, 'presentacion' => $decomiso->presentacion , 'fecha' => $decomiso->fecha]);
                }
            }

            debug("decomisos por ubicacion o por calor");
            debug($decomisoso);
        }
        debug($decomisoso) ;
        debug("suma total de pesos");
        debug($cantidad_total) ; // output 5

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);
    }

    public function generar_precursor(Request $request)
    {
        debug($request);
        $user=Auth::user();
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Precursores', 'precursores' => implode(", ",$request->precursores), 'presentación' => implode(", ",$request->pres_precursores), 'tipo_mapa' => $request->tipo_mapa, 'magnitud' => $request->parametro]])
        ->causedBy($user)
        ->event('created')
        ->useLog($user->name)
        ->log('Se ha generado un mapa de precurores');
        ///////////////////////////////////////////



        $decomisoso=[];
        $cantidad_total=0;

        $departamentos= DB::table('departamentos')->get();
        $municipios= DB::table('municipios')->get();

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_precursor')
            ->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')
            ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
            ->join('presentacion_precursors', 'decomiso_precursor.presentacion_precursor_id', '=', 'presentacion_precursors.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('presentacion_precursor_id',$request->pres_precursores)
            ->whereIn('precursor_id', $request->precursores)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_precursor.deleted_at')
            ->select('presentacion_precursor_id','precursor_id','municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','volumen','decomisos.fecha','decomisos.municipio_id','decomisos.id','presentacion_precursors.descripcion AS presentacion','precursors.descripcion AS precursor')
            ->get();

        debug("decomisos totales");
        //debug($decomisos_totales[0]);
        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;

            foreach ($departamentos as $departamento) {

                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => ($request->parametro=="cantidad"?$vkd2:round($vkd2,2))]);
                    }

                }
                $municipios_deps=$this->colorear($municipios_deps);
                // debug("aquie");
                // debug($municipios_deps);
                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => ($request->parametro=="cantidad"?$vkd:round($vkd,2)),'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }
            //debug($request->parametro2);

            if($request->parametro2=="porcentaje"){
                //debug("llega porcentages");
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $precursores=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($precursores, ['descripcion' => $decomiso->precursor, 'cantidad' => $decomiso->cantidad, 'volumen' => $decomiso->volumen, 'presentacion' => $decomiso->presentacion,'fecha' => $decomiso->fecha]);
                    array_push($decomisoso, ['precursores' => $precursores,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['precursores'], ['descripcion' => $decomiso->precursor, 'cantidad' => $decomiso->cantidad, 'volumen' => $decomiso->volumen, 'presentacion' => $decomiso->presentacion , 'fecha' => $decomiso->fecha]);
                }
            }

            debug("decomisos por ubicacion o por calor");
            debug($decomisoso);
        }
        debug($decomisoso) ;
        debug("suma total de volumenes");
        debug($cantidad_total) ; // output 5

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);





        // ///////////////////////////////////////////
        // //debug("llega");
        // $fecha1 = Carbon::parse($request->fecha_ini);
        // $fecha2 = Carbon::parse($request->fecha_fin);

        //  //////////////////////////////////////////
        // $decomisoso=[];
        // $cantidad_total=0;

        // $departamentos= Departamento::all();
        // $fecha1 = Carbon::parse($request->fecha_ini);
        // $fecha2 = Carbon::parse($request->fecha_fin);


        // $decomisos_totales=DB::table('decomiso_precursor')
        //     ->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')
        //     ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
        //     ->join('presentacion_precursors', 'decomiso_precursor.presentacion_precursor_id', '=', 'presentacion_precursors.id')
        //     ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
        //     ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
        //     ->whereIn('presentacion_precursor_id',$request->pres_precursores)
        //     ->whereIn('precursor_id', $request->precursores)
        //     ->whereNull('decomisos.deleted_at')
        //     ->whereNull('decomiso_precursor.deleted_at')
        //     ->select('municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','volumen','decomisos.fecha','decomisos.municipio_id','decomisos.id','presentacion_precursors.descripcion AS presentacion','precursors.descripcion AS precursor')
        //     ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales);

        // if ($request->tipo_mapa=="departamentos") {
        //     $decomisoso=[];
        //     $vkd=0;
        //     foreach ($departamentos as $departamento) {
        //         $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
        //         array_push($decomisoso, ['nombre' => $departamento->nombre, 'cantidades' => ($request->parametro=="cantidad"?$vkd:round($vkd,2)),'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6']);
        //     }

        //     $cantidad_total=array_sum(array_column($decomisoso, 'cantidades'));

        //     if($request->parametro2=="porcentaje"){
        //         for ($i = 0; $i < count($decomisoso); $i++) {
        //             $decomisoso[$i]['cantidades']=round((100/$cantidad_total)*$decomisoso[$i]['cantidades'],2);
        //         }
        //     }

        //     function build_sorter($key) {
        //         return function ($a, $b) use ($key) {
        //             return strnatcmp($a[$key], $b[$key]);
        //         };
        //     }

        //     usort($decomisoso, build_sorter('cantidades'));//ordena arreglos de manera acendente en base a llave 'cantidades' usando la función build_sorter

        //     $colores=['#56c78b','#FE9923','#FE5C22'];
        //     $conteo_colores=count($colores)-1;
        //     for ($i=count($decomisoso)-1; $i >= 0; $i--) {
        //         if($conteo_colores>=0){
        //             $decomisoso[$i]['color']=$colores[$conteo_colores];
        //         }else{
        //             $decomisoso[$i]['color']=$colores[0];
        //         }
        //         $conteo_colores--;
        //     }
        // }else{
        //     $decomisoso=[];

        //     foreach ($decomisos_totales as $decomiso) {
        //         $precursor=[];
        //         if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
        //             array_push($precursor, ['descripcion' => $decomiso->precursor, 'cantidad' => $decomiso->cantidad, 'volumen' => $decomiso->volumen, 'presentacion' => $decomiso->presentacion]);
        //             array_push($decomisoso, ['precursores' => $precursor,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id]);
        //         }else{
        //             array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['precursores'], ['descripcion' => $decomiso->precursor, 'cantidad' => $decomiso->cantidad, 'volumen' => $decomiso->volumen, 'presentacion' => $decomiso->presentacion]);
        //         }
        //     }

        //     debug("decomisos por ubicacion o por calor");
        //     debug($decomisoso);
        // }

        // debug("decomisos finales");
        // debug($decomisoso);

        //  ///////////////////////////////////////
        // return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);
        //return view('admin.graficas.grafica', compact('periodo','decomisos'));
    }

    public function generar_arma(Request $request)
    {
        //debug($request);
        $user=Auth::user();
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Armas', 'tipo_mapa' => $request->tipo_mapa, 'armas' => implode(", ",$request->armas)]])
        ->causedBy($user)
        ->event('created')
        ->useLog($user->name)
        ->log('Se ha generado un mapa de armas');




        //////////////////////////////////////////
        $decomisoso=[];
        $cantidad_total=0;

        $departamentos= DB::table('departamentos')->get();
        $municipios= DB::table('municipios')->get();

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);


        $decomisos_totales=DB::table('arma_decomiso')
            ->join('decomisos', 'arma_decomiso.decomiso_id', '=', 'decomisos.id')
            ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('arma_id', $request->armas)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('arma_decomiso.deleted_at')
            ->select('municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','decomisos.fecha','decomisos.municipio_id','decomisos.id','armas.descripcion AS arma')
            ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales);
        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;
            // foreach ($departamentos as $departamento) {
            //     $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud
            //     array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud,  'color'=>'#A6A6A6']);
            // }
            foreach ($departamentos as $departamento) {

                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => $vkd2]);
                    }

                }
                $municipios_deps=$this->colorear($municipios_deps);

                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }

            //$cantidad_total=array_sum(array_column($decomisoso2, 'cantidades'));

            if($request->parametro2=="porcentaje"){
                //debug("llega porcentages");
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $arma=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($arma, ['descripcion' => $decomiso->arma, 'cantidad' => $decomiso->cantidad, 'fecha' => $decomiso->fecha]);
                    array_push($decomisoso, ['armas' => $arma,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['armas'], ['descripcion' => $decomiso->arma, 'cantidad' => $decomiso->cantidad, 'fecha' => $decomiso->fecha]);
                }
            }

            //debug("decomisos por ubicacion o por calor");
            //debug($decomisoso);
        }

        debug("decomisos finales");
        debug($decomisoso);

         ///////////////////////////////////////

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);

        //return view('admin.graficas.grafica', compact('periodo','decomisos'));
    }

    public function generar_municion(Request $request)
    {
        debug($request);
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Municiones', 'tipo_mapa' => $request->tipo_mapa, 'municiones' => implode(", ",$request->municiones)]])
        ->causedBy(Auth::user())
        ->event('created')
        ->useLog(Auth::user()->name)
        ->log('Se ha generado un mapa de municiones');

// //////////////////////////////////////////
//         $decomisoso2=[];
//         $cantidad_total=0;

//         $departamentos= Departamento::all();
//         $fecha1 = Carbon::parse($request->fecha_ini);
//         $fecha2 = Carbon::parse($request->fecha_fin);


//         $decomisos_totales=DB::table('decomiso_tipo_municion')
//             ->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')
//             ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
//             ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
//             ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
//             ->whereIn('tipo_municion_id', $request->municiones)
//             ->whereNull('decomisos.deleted_at')
//             ->whereNull('decomiso_tipo_municion.deleted_at')
//             ->select('municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','decomisos.fecha','decomisos.municipio_id','decomisos.id','tipo_municions.descripcion AS municion')
//             ->get();

//         debug("decomisos totales");
//         debug($decomisos_totales);

//         if ($request->tipo_mapa=="departamentos") {
//             $decomisoso2=[];
//             $vkd=0;
//             foreach ($departamentos as $departamento) {
//                 $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud
//                 array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud,'color'=>'#A6A6A6']);
//             }

//             $cantidad_total=array_sum(array_column($decomisoso2, 'cantidades'));

//             if($request->parametro2=="porcentaje"){
//                 for ($i = 0; $i < count($decomisoso2); $i++) {
//                     $decomisoso2[$i]['cantidades']=round((100/$cantidad_total)*$decomisoso2[$i]['cantidades'],2);
//                 }
//             }

//             function build_sorter($key) {
//                 return function ($a, $b) use ($key) {
//                     return strnatcmp($a[$key], $b[$key]);
//                 };
//             }

//             usort($decomisoso2, build_sorter('cantidades'));//ordena arreglos de manera acendente en base a llave 'cantidades' usando la función build_sorter

//             $colores=['#56c78b','#FE9923','#FE5C22'];
//             $conteo_colores=count($colores)-1;
//             for ($i=count($decomisoso2)-1; $i >= 0; $i--) {
//                 if($conteo_colores>=0){
//                     $decomisoso2[$i]['color']=$colores[$conteo_colores];
//                 }else{
//                     $decomisoso2[$i]['color']=$colores[0];
//                 }
//                 $conteo_colores--;
//             }
//         }else{
//             $decomisoso2=[];

//             foreach ($decomisos_totales as $decomiso) {
//                 $municiones=[];
//                 if (array_search($decomiso->id, array_column($decomisoso2, 'id'))===false) {
//                     array_push($municiones, ['descripcion' => $decomiso->municion, 'cantidad' => $decomiso->cantidad]);
//                     array_push($decomisoso2, ['municiones' => $municiones,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id]);
//                 }else{
//                     array_push($decomisoso2[array_search($decomiso->id, array_column($decomisoso2, 'id'))]['municiones'], ['descripcion' => $decomiso->municion, 'cantidad' => $decomiso->cantidad]);
//                 }
//             }

//             //debug("decomisos por ubicacion o por calor");
//             //debug($decomisoso2);
//         }

//         debug("decomisos finales");
//         debug($decomisoso2);

//  ///////////////////////////////////////

//         return response()->json(['decomisos'=>$decomisoso2]);

        //return view('admin.graficas.grafica', compact('periodo','decomisos'));

        //////////////////////////////////////////
        $decomisoso=[];
        $cantidad_total=0;

        $departamentos= DB::table('departamentos')->get();
        $municipios= DB::table('municipios')->get();

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);


        $decomisos_totales=DB::table('decomiso_tipo_municion')
            ->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')
            ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('tipo_municion_id', $request->municiones)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_tipo_municion.deleted_at')
            ->select('municipios.departamento_id','decomisos.latitud','decomisos.longitud','cantidad','decomisos.fecha','decomisos.municipio_id','decomisos.id','tipo_municions.descripcion AS municion')
            ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales);
        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;
            // foreach ($departamentos as $departamento) {
            //     $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud
            //     array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud,  'color'=>'#A6A6A6']);
            // }
            foreach ($departamentos as $departamento) {

                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->sum('cantidad');//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => $vkd2]);
                    }

                }
                $municipios_deps=$this->colorear($municipios_deps);

                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }

            //$cantidad_total=array_sum(array_column($decomisoso2, 'cantidades'));

            if($request->parametro2=="porcentaje"){
                //debug("llega porcentages");
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $municion=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($municion, ['descripcion' => $decomiso->municion, 'cantidad' => $decomiso->cantidad, 'fecha' => $decomiso->fecha]);
                    array_push($decomisoso, ['municions' => $municion,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['municions'], ['descripcion' => $decomiso->municion, 'cantidad' => $decomiso->cantidad, 'fecha' => $decomiso->fecha]);
                }
            }

            //debug("decomisos por ubicacion o por calor");
            //debug($decomisoso);
        }

        debug("decomisos finales");
        debug($decomisoso);

         ///////////////////////////////////////

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);


    }


    public function generar_detenido(Request $request)
    {
        //debug($request);
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Detenidos', 'tipo_mapa' => $request->tipo_mapa, 'genero' => implode(", ",$request->genero), 'estructura' => implode(", ",$request->estructura)]])
        ->causedBy(Auth::user())
        ->event('created')
        ->useLog(Auth::user()->name)
        ->log('Se ha generado un mapa de detenidos');



        //////////////////////////////////////////

//         $decomisoso2=[];
//         $cantidad_total=0;

//         $departamentos= Departamento::all();
//         $fecha1 = Carbon::parse($request->fecha_ini);
//         $fecha2 = Carbon::parse($request->fecha_fin);


//         $decomisos_totales=DB::table('decomiso_detenidos')
//             ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
//             ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
//             ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
//             ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
//             ->whereIn('estructura_id', $request->estructura)
//             ->whereIn('genero', $request->genero)
//             ->whereNull('decomisos.deleted_at')
//             ->whereNull('decomiso_detenidos.deleted_at')
//             ->select('municipios.departamento_id',
//                     'decomisos.latitud',
//                     'decomisos.longitud',
//                     'decomisos.fecha',
//                     'decomisos.municipio_id',
//                     'decomisos.id',
//                     'decomiso_detenidos.nombre',
//                     'decomiso_detenidos.edad',
//                     'estructura_criminals.descripcion AS estructura',
//                     'decomiso_detenidos.identidad')
//             ->get();

//         debug("decomisos totales");
//         debug($decomisos_totales);

//         if ($request->tipo_mapa=="departamentos") {
//             $decomisoso2=[];
//             $vkd=0;
//             foreach ($departamentos as $departamento) {
//                 $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
//                 array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6']);
//             }

//             $cantidad_total=array_sum(array_column($decomisoso2, 'cantidades'));

//             if($request->parametro2=="porcentaje"){
//                 for ($i = 0; $i < count($decomisoso2); $i++) {
//                     $decomisoso2[$i]['cantidades']=round((100/$cantidad_total)*$decomisoso2[$i]['cantidades'],2);
//                 }
//             }

//             function build_sorter($key) {
//                 return function ($a, $b) use ($key) {
//                     return strnatcmp($a[$key], $b[$key]);
//                 };
//             }

//             usort($decomisoso2, build_sorter('cantidades'));//ordena arreglos de manera acendente en base a llave 'cantidades' usando la función build_sorter

//             $colores=['#56c78b','#FE9923','#FE5C22'];
//             $conteo_colores=count($colores)-1;
//             for ($i=count($decomisoso2)-1; $i >= 0; $i--) {
//                 if($conteo_colores>=0){
//                     $decomisoso2[$i]['color']=$colores[$conteo_colores];
//                 }else{
//                     $decomisoso2[$i]['color']=$colores[0];
//                 }
//                 $conteo_colores--;
//             }
//         }else{
//             $decomisoso2=[];

//             foreach ($decomisos_totales as $decomiso) {
//                 $detenidos=[];
//                 if (array_search($decomiso->id, array_column($decomisoso2, 'id'))===false) {
//                     array_push($detenidos, ['nombre' => $decomiso->nombre, 'edad' => $decomiso->edad, 'estructura' => $decomiso->estructura, 'identidad' => $decomiso->identidad]);
//                     array_push($decomisoso2, ['detenidos' => $detenidos,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id]);
//                 }else{
//                     array_push($decomisoso2[array_search($decomiso->id, array_column($decomisoso2, 'id'))]['detenidos'], ['nombre' => $decomiso->nombre, 'edad' => $decomiso->edad, 'estructura' => $decomiso->estructura, 'identidad' => $decomiso->identidad]);
//                 }
//             }

//             //debug("decomisos por ubicacion o por calor");
//             //debug($decomisoso2);
//         }

//         debug("decomisos finales");
//         debug($decomisoso2);

//  ///////////////////////////////////////

//         return response()->json(['decomisos'=>$decomisoso2]);


        //////////////////////
        //$decomisoso2=[];
        //$cantidad_total=0;

        $departamentos= Departamento::all();
        $municipios= DB::table('municipios')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);


        $decomisos_totales=DB::table('decomiso_detenidos')
            ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
            ->join('estructura_criminals', 'decomiso_detenidos.estructura_id', '=', 'estructura_criminals.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('estructura_id', $request->estructura)
            ->whereIn('genero', $request->genero)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_detenidos.deleted_at')
            ->select('municipios.departamento_id',
                    'decomisos.latitud',
                    'decomisos.longitud',
                    'decomisos.fecha',
                    'decomisos.municipio_id',
                    'decomisos.id',
                    'decomiso_detenidos.nombre',
                    'decomiso_detenidos.edad',
                    'estructura_criminals.descripcion AS estructura',
                    'decomiso_detenidos.identidad')
            ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales);

        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;
            foreach ($departamentos as $departamento) {
                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                //array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6']);

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => $vkd2]);
                    }

                }

                $municipios_deps=$this->colorear($municipios_deps);

                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }

            if($request->parametro2=="porcentaje"){
                //debug("llega porcentages");
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $detenidos=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($detenidos, ['nombre' => $decomiso->nombre, 'edad' => $decomiso->edad, 'estructura' => $decomiso->estructura, 'identidad' => $decomiso->identidad]);
                    array_push($decomisoso, ['detenidos' => $detenidos,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['detenidos'], ['nombre' => $decomiso->nombre, 'edad' => $decomiso->edad, 'estructura' => $decomiso->estructura, 'identidad' => $decomiso->identidad]);
                }
            }

            //debug("decomisos por ubicacion o por calor");
            //debug($decomisoso);
        }

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);


    }

    public function generar_transporte(Request $request)
    {
        debug($request);
        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'tipo_decomiso' => 'Transportes', 'tipo_mapa' => $request->tipo_mapa, 'tipo_transporte' => implode(", ",$request->tipo_tr)]])
        ->causedBy(Auth::user())
        ->event('created')
        ->useLog(Auth::user()->name)
        ->log('Se ha generado un mapa de transportes');

        //////////////////////////////////////////

//         $decomisoso2=[];
//         $cantidad_total=0;

//         $departamentos= Departamento::all();
//         $fecha1 = Carbon::parse($request->fecha_ini);
//         $fecha2 = Carbon::parse($request->fecha_fin);


//         $decomisos_totales=DB::table('decomiso_transportes')
//             ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
//             //->join('estructura_criminals', 'decomiso_transportes.estructura_id', '=', 'estructura_criminals.id')
//             ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
//             ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
//             //->whereIn('estructura_id', $request->estructura)
//             ->whereIn('tipo', $request->tipo_tr)
//             ->whereNull('decomisos.deleted_at')
//             ->whereNull('decomiso_transportes.deleted_at')
//             ->select('municipios.departamento_id','decomisos.latitud','decomisos.longitud','decomisos.fecha','decomisos.municipio_id','decomisos.id','decomiso_transportes.color','decomiso_transportes.marca','decomiso_transportes.modelo','decomiso_transportes.placa')
//             ->get();

//         debug("decomisos totales");
//         debug($decomisos_totales);

//         if ($request->tipo_mapa=="departamentos") {
//             $decomisoso2=[];
//             $vkd=0;
//             foreach ($departamentos as $departamento) {
//                 $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
//                 array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6']);
//             }

//             $cantidad_total=array_sum(array_column($decomisoso2, 'cantidades'));

//             if($request->parametro2=="porcentaje"){
//                 for ($i = 0; $i < count($decomisoso2); $i++) {
//                     $decomisoso2[$i]['cantidades']=round((100/$cantidad_total)*$decomisoso2[$i]['cantidades'],2);
//                 }
//             }

//             function build_sorter($key) {
//                 return function ($a, $b) use ($key) {
//                     return strnatcmp($a[$key], $b[$key]);
//                 };
//             }

//             usort($decomisoso2, build_sorter('cantidades'));//ordena arreglos de manera acendente en base a llave 'cantidades' usando la función build_sorter

//             $colores=['#56c78b','#FE9923','#FE5C22'];
//             $conteo_colores=count($colores)-1;
//             for ($i=count($decomisoso2)-1; $i >= 0; $i--) {
//                 if($conteo_colores>=0){
//                     $decomisoso2[$i]['color']=$colores[$conteo_colores];
//                 }else{
//                     $decomisoso2[$i]['color']=$colores[0];
//                 }
//                 $conteo_colores--;
//             }
//         }else{
//             $decomisoso2=[];

//             foreach ($decomisos_totales as $decomiso) {
//                 $transportes=[];
//                 if (array_search($decomiso->id, array_column($decomisoso2, 'id'))===false) {
//                     array_push($transportes, ['color' => $decomiso->color, 'marca' => $decomiso->marca, 'modelo' => $decomiso->modelo, 'placa' => $decomiso->placa]);
//                     array_push($decomisoso2, ['transportes' => $transportes,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id]);
//                 }else{
//                     array_push($decomisoso2[array_search($decomiso->id, array_column($decomisoso2, 'id'))]['transportes'], ['color' => $decomiso->color, 'marca' => $decomiso->marca, 'modelo' => $decomiso->modelo, 'placa' => $decomiso->placa]);
//                 }
//             }

//             //debug("decomisos por ubicacion o por calor");
//             //debug($decomisoso2);
//         }

//         debug("decomisos finales");
//         debug($decomisoso2);

//  ///////////////////////////////////////
//         return response()->json(['decomisos'=>$decomisoso2]);

/////////////////////////////////////////////////////////////////
        $departamentos= Departamento::all();
        $municipios= DB::table('municipios')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);


        $decomisos_totales=DB::table('decomiso_transportes')
            ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->whereIn('tipo', $request->tipo_tr)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_transportes.deleted_at')
            ->select('municipios.departamento_id',
                'decomisos.latitud',
                'decomisos.longitud',
                'decomisos.fecha',
                'decomisos.municipio_id',
                'decomisos.id',
                'decomiso_transportes.color',
                'decomiso_transportes.marca',
                'decomiso_transportes.modelo',
                'decomiso_transportes.placa')
            ->get();

        // debug("decomisos totales");
        // debug($decomisos_totales);

        $cantidad_total=count($decomisos_totales);

        if ($request->tipo_mapa=="departamentos") {
            $decomisoso=[];
            $vkd=0;
            foreach ($departamentos as $departamento) {
                $vkd=$decomisos_totales->where('departamento_id', $departamento->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                //array_push($decomisoso2, ['nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6']);

                $municipios_deps=[];
                $vkd2=0;
                foreach ($municipios as $municipio) {
                    if ($departamento->id==$municipio->departamento_id) {
                        $vkd2=$decomisos_totales->where('municipio_id', $municipio->id)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        array_push($municipios_deps, ['mun_id' => $municipio->id,'nombre' => $municipio->nombre, 'cantidades' => $vkd2]);
                    }

                }

                $municipios_deps=$this->colorear($municipios_deps);

                if($request->parametro2=="porcentaje"){
                    $municipios_deps=$this->porcentages($municipios_deps);
                }
                array_push($decomisoso, ['dep_id' => $departamento->id,'nombre' => $departamento->nombre, 'cantidades' => $vkd,'latitud'=>$departamento->latitud,'longitud'=>$departamento->longitud, 'color'=>'#A6A6A6', 'municipios' =>$municipios_deps]);
            }

            if($request->parametro2=="porcentaje"){
                //debug("llega porcentages");
                $decomisoso=$this->porcentages($decomisoso);
            }
            $decomisoso=$this->colorear($decomisoso);
        }else{
            $decomisoso=[];

            foreach ($decomisos_totales as $decomiso) {
                $transportes=[];
                if (array_search($decomiso->id, array_column($decomisoso, 'id'))===false) {
                    array_push($transportes, ['placa' => $decomiso->placa, 'marca' => $decomiso->marca, 'modelo' => $decomiso->modelo, 'color' => $decomiso->color]);
                    array_push($decomisoso, ['transportes' => $transportes,'latitud'=>$decomiso->latitud,'longitud'=>$decomiso->longitud,'id'=>$decomiso->id, 'departamento_id' => $decomiso->departamento_id]);
                }else{
                    array_push($decomisoso[array_search($decomiso->id, array_column($decomisoso, 'id'))]['transportes'], ['placa' => $decomiso->placa, 'marca' => $decomiso->marca, 'modelo' => $decomiso->modelo, 'color' => $decomiso->color]);
                }
            }

            //debug("decomisos por ubicacion o por calor");
            //debug($decomisoso);
        }

        return response()->json(['decomisos'=>$decomisoso, 'cant_total'=>$cantidad_total]);

    }

    public function generar_dash(Request $request)
    {
        $inicio = Carbon::parse(Carbon::now()->format('Y').'-01-01');
        $final = Carbon::now();
        debug($inicio);
        debug($final);

        //$fecha1 = Carbon::parse($request->fecha_ini);
        //$fecha2 = Carbon::parse($request->fecha_fin);
        $decomisos=DB::table('decomisos')
            //->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            //->join('estructura_criminals', 'decomiso_transportes.estructura_id', '=', 'estructura_criminals.id')
            ->join('institucions', 'decomisos.institucion_id', '=', 'institucions.id')
            ->whereBetween('decomisos.fecha', [$inicio, $final])
            //->whereIn('estructura_id', $request->estructura)
            //->whereIn('tipo', $request->tipo_tr)
            ->whereNull('decomisos.deleted_at')
            //->whereNull('decomiso_transportes.deleted_at')
            ->select('decomisos.latitud','decomisos.longitud','decomisos.fecha','decomisos.observacion','decomisos.direccion','institucions.nombre AS institucion')
            ->get();


        return view('admin.dashboard', compact('decomisos'));
    }





    public function generar_droga_detalles_lugar(Request $request)
    {
        $cantidad_total=0;

        $drogas_= DB::table('drogas')->get();
        $presentaciones_drogas= DB::table('presentacion_drogas')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_droga')
            ->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')
            ->join('drogas', 'decomiso_droga.droga_id', '=', 'drogas.id')
            ->join('presentacion_drogas', 'decomiso_droga.presentacion_droga_id', '=', 'presentacion_drogas.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            ->whereIn('presentacion_droga_id',$request->pres_drogas)
            ->whereIn('droga_id', $request->drogas)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_droga.deleted_at')
            ->select('presentacion_droga_id','droga_id','municipios.departamento_id','cantidad','peso','decomisos.fecha','decomisos.municipio_id','decomisos.id','presentacion_drogas.descripcion AS presentacion','drogas.descripcion AS droga')
            ->get();

        debug("decomisos totales");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->sum(($request->parametro=="cantidad"?'cantidad':'peso'));

            $total_por_dep=0;
            $drogs=[];
            $pres_drogs=[];

            foreach ($request->drogas as $droga) {
                $droga_nombre =$drogas_->firstWhere('id',$droga);

                $pres_drogs=[];
                foreach ($request->pres_drogas as $pres) {
                    $pres_nombre =$presentaciones_drogas->firstWhere('id',$pres);

                    $prescount=$decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('droga_id', $droga)->where('presentacion_droga_id', $pres)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));

                    if ($prescount!=0) {
                        if($request->parametro2=="porcentaje"){
                            $prescount=round((100/$cantidad_total)*$prescount,2);
                        }
                        array_push($pres_drogs,['nombre' => $pres_nombre->descripcion, ($request->parametro=="cantidad"?'cantidad':'peso') => round($prescount, 3)]);
                    }

                    $total_por_dep=$total_por_dep+$prescount;
                }
                if (count($pres_drogs)!=0) {
                    array_push($drogs,["nombre" => $droga_nombre->descripcion, "presentaciones" => $pres_drogs]);
                }
            }

            debug("datos a modificars");

        debug("decomisos");
        debug($drogs);

        return response()->json(['drogas_detalles'=>$drogs,'totales_por_dep' => $total_por_dep]);
    }

    public function generar_precursor_detalles_lugar(Request $request)
    {
        $cantidad_total=0;

        $precursores_= DB::table('precursors')->get();
        $presentaciones_precursores= DB::table('presentacion_precursors')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_precursor')
            ->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')
            ->join('precursors', 'decomiso_precursor.precursor_id', '=', 'precursors.id')
            ->join('presentacion_precursors', 'decomiso_precursor.presentacion_precursor_id', '=', 'presentacion_precursors.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            ->whereIn('presentacion_precursor_id',$request->pres_precursores)
            ->whereIn('precursor_id', $request->precursores)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_precursor.deleted_at')
            ->select('presentacion_precursor_id','precursor_id','municipios.departamento_id','cantidad','volumen','decomisos.fecha','decomisos.municipio_id','decomisos.id','presentacion_precursors.descripcion AS presentacion','precursors.descripcion AS precursor')
            ->get();

        debug("decomisos totalesaa");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));

            $total_por_dep=0;
            $precursors=[];
            $pres_precursors=[];

            foreach ($request->precursores as $precursor) {
                $precursor_nombre =$precursores_->firstWhere('id',$precursor);

                $pres_precursors=[];
                foreach ($request->pres_precursores as $pres) {
                    $pres_nombre =$presentaciones_precursores->firstWhere('id',$pres);

                    $prescount=$decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('precursor_id', $precursor)->where('presentacion_precursor_id', $pres)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));

                    if ($prescount!=0) {
                        if($request->parametro2=="porcentaje"){
                            $prescount=round((100/$cantidad_total)*$prescount,2);
                        }
                        array_push($pres_precursors,['nombre' => $pres_nombre->descripcion, ($request->parametro=="cantidad"?'cantidad':'peso') => round($prescount, 3)]);
                    }

                    $total_por_dep=$total_por_dep+$prescount;
                }
                if (count($pres_precursors)!=0) {
                    array_push($precursors,["nombre" => $precursor_nombre->descripcion, "presentaciones" => $pres_precursors]);
                }
            }

            debug("datos a modificars");

        debug("decomisos");
        debug($precursors);

        return response()->json(['precursores_detalles'=>$precursors,'totales_por_dep' => $total_por_dep]);
    }

    public function generar_arma_detalles_lugar(Request $request)
    {
        $cantidad_total=0;

        $armas_= DB::table('armas')->get();
        //$presentaciones_armas= DB::table('presentacion_precursors')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('arma_decomiso')
            ->join('decomisos', 'arma_decomiso.decomiso_id', '=', 'decomisos.id')
            ->join('armas', 'arma_decomiso.arma_id', '=', 'armas.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            ->whereIn('arma_id', $request->armas)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('arma_decomiso.deleted_at')
            ->select('arma_id','municipios.departamento_id','cantidad','decomisos.fecha','decomisos.municipio_id','decomisos.id','armas.descripcion AS arma')
            ->get();

        debug("decomisos totales");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->sum('cantidad');

            $total_por_dep=0;
            $armas=[];
            //$pres_armas=[];

            foreach ($request->armas as $arma) {
                $arma_nombre =$armas_->firstWhere('id',$arma);

                //$pres_armas=[];
                //foreach ($request->pres_armas as $pres) {
                    //$pres_nombre =$presentaciones_armas->firstWhere('id',$pres);

                    $armascount=$decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('arma_id', $arma)->sum('cantidad');

                    if ($armascount!=0) {
                        if($request->parametro2=="porcentaje"){
                            $armascount=round((100/$cantidad_total)*$armascount,2);
                        }
                        //array_push($pres_armas,['nombre' => $pres_nombre->descripcion, ($request->parametro=="cantidad"?'cantidad':'peso') => round($armascount, 3)]);
                    }

                    $total_por_dep=$total_por_dep+$armascount;
                //}
                //debug(count($armas)!=0);
                if ($armascount!=0) {
                    array_push($armas,["nombre" => $arma_nombre->descripcion, "cantidad" => $armascount]);
                }
            }

            debug("datos a modificars");

        debug("decomisos");
        debug($armas);

        return response()->json(['armas_detalles'=>$armas,'totales_por_dep' => $total_por_dep]);
    }

    public function generar_municion_detalles_lugar(Request $request)
    {
        $cantidad_total=0;

        $municiones_= DB::table('tipo_municions')->get();
        //$presentaciones_tipo_municions= DB::table('presentacion_precursors')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_tipo_municion')
            ->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')
            ->join('tipo_municions', 'decomiso_tipo_municion.tipo_municion_id', '=', 'tipo_municions.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            ->whereIn('tipo_municion_id', $request->municiones)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_tipo_municion.deleted_at')
            ->select('tipo_municion_id','municipios.departamento_id','cantidad','decomisos.fecha','decomisos.municipio_id','decomisos.id','tipo_municions.descripcion AS municion')
            ->get();

        debug("decomisos totales");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->sum('cantidad');

            $total_por_dep=0;
            $municiones=[];
            //$pres_municiones=[];

            foreach ($request->municiones as $municion) {
                $municion_nombre =$municiones_->firstWhere('id',$municion);

                //$pres_municions=[];
                //foreach ($request->pres_municions as $pres) {
                    //$pres_nombre =$presentaciones_municions->firstWhere('id',$pres);

                    $municionscount=$decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('tipo_municion_id', $municion)->sum('cantidad');

                    if ($municionscount!=0) {
                        if($request->parametro2=="porcentaje"){
                            $municionscount=round((100/$cantidad_total)*$municionscount,2);
                        }
                        //array_push($pres_armas,['nombre' => $pres_nombre->descripcion, ($request->parametro=="cantidad"?'cantidad':'peso') => round($municionscount, 3)]);
                    }

                    $total_por_dep=$total_por_dep+$municionscount;
                //}
                //debug(count($armas)!=0);
                if ($municionscount!=0) {
                    array_push($municiones,["nombre" => $municion_nombre->descripcion, "cantidad" => $municionscount]);
                }
            }

            debug("datos a modificars");

        debug("decomisos");
        debug($municiones);

        return response()->json(['municiones_detalles'=>$municiones,'totales_por_dep' => $total_por_dep]);
    }

    public function generar_detenido_detalles_lugar(Request $request)
    {
        //debug($request);
        $cantidad_total=0;

        $estructuras_= DB::table('estructura_criminals')->get();
        //$presentaciones_tipo_municions= DB::table('presentacion_precursors')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_detenidos')
            ->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            ->whereIn('estructura_id', $request->estructura)
            ->whereIn('genero', $request->genero)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_detenidos.deleted_at')
            //->select('*')
            ->get();



        debug("decomisos totaless");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->count();

             $total_por_dep=0;
             $estructuras=[];
        //     //$pres_estructuras=[];

            foreach ($request->estructura as $estructura) {
                //$municion_nombre =$estructuras_->firstWhere('id',$municion);

                $generos=[];
                //foreach ($request->pres_municions as $pres) {
                    //$pres_nombre =$presentaciones_municions->firstWhere('id',$pres);

                    // $hom = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', 'M')->count();
                    // $fem = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', 'F')->count();

                    //array_push($estructuras,["estructuras" => $estructura, "cantidades" => ["hombres" => $hom, "mujeres" => $fem]]);
                    $cant_estr=0;
                    foreach ($request->genero as $genero) {
                        $canti=0;
                        $canti = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', $genero)->count();
                        if ($canti!=0) {
                            if($request->parametro2=="porcentaje"){
                                $canti=round((100/$cantidad_total)*$canti,2);
                            }
                            array_push($generos,["cantidad" => $canti,"genero" =>$genero]);
                        }

                        $cant_estr=$cant_estr+$canti;
                        $total_por_dep=$total_por_dep+$canti;
                    }
                    if ($cant_estr!=0) {
                        $nombre_est=$estructuras_->where('id',$estructura)->pluck('descripcion')->first();
                        debug($nombre_est);
                        array_push($estructuras,["nombre" => $nombre_est, "cantidades_detenidos" => $generos]);
                    }
                    // if ($municionscount!=0) {
                    //     if($request->parametro2=="porcentaje"){
                    //         $municionscount=round((100/$cantidad_total)*$municionscount,2);
                    //     }
                    //     //array_push($pres_armas,['nombre' => $pres_nombre->descripcion, ($request->parametro=="cantidad"?'cantidad':'peso') => round($municionscount, 3)]);
                    // }

                    //$total_por_dep=$total_por_dep+$municionscount;
                //}
                //debug(count($armas)!=0);

            }

        //     debug("datos a modificars");

        // debug("decomisos");
        // debug($estructuras);

         return response()->json(['detenidos_detalles'=>$estructuras,'totales_por_dep' => $total_por_dep]);
    }

    public function generar_transporte_detalles_lugar(Request $request)
    {
        //debug($request);
        $cantidad_total=0;

        //$estructuras_= DB::table('estructura_criminals')->get();
        //$presentaciones_tipo_municions= DB::table('presentacion_precursors')->get();
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        $decomisos_totales=DB::table('decomiso_transportes')
            ->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')
            ->join('municipios', 'decomisos.municipio_id', '=', 'municipios.id')
            ->whereBetween('decomisos.fecha', [$fecha1, $fecha2])
            ->when($request->tip_pl=='departamento'?$request->id_pl:false, function ($query, $id_pl) {
                debug("es departamento");//////////////////consultas condicionales
                $query->where('municipios.departamento_id', $id_pl);
            })
            ->when($request->tip_pl=='municipio'?$request->id_pl:false, function ($query, $id_pl) { ////////////////////consultas condicionales
                debug("es municipio");
                $query->where('municipios.id',$id_pl);
            })
            //->whereIn('estructura_id', $request->estructura)
            ->whereIn('tipo', $request->tipo_tr)
            ->whereNull('decomisos.deleted_at')
            ->whereNull('decomiso_transportes.deleted_at')
            //->select('*')
            ->get();



        debug("decomisos totaless");
        debug($decomisos_totales);

        $cantidad_total=$decomisos_totales->count();

             $total_por_dep=0;
             $tipos=[];
        //     //$pres_estructuras=[];

            foreach ($request->tipo_tr as $tipo) {
                //$municion_nombre =$estructuras_->firstWhere('id',$municion);

                //$generos=[];
                //foreach ($request->pres_municions as $pres) {
                    //$pres_nombre =$presentaciones_municions->firstWhere('id',$pres);

                    // $hom = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', 'M')->count();
                    // $fem = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', 'F')->count();

                    //array_push($estructuras,["estructuras" => $estructura, "cantidades" => ["hombres" => $hom, "mujeres" => $fem]]);
                    $cant_tipo = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('tipo', $tipo)->count();

                    // foreach ($request->genero as $genero) {
                    //     $canti=0;
                    //     $canti = $decomisos_totales->where(($request->tip_pl=='departamento'?'departamento_id':'municipio_id'), $request->id_pl)->where('estructura_id', $estructura)->where('genero', $genero)->count();
                    //     if ($canti!=0) {
                    //         array_push($generos,["cantidad" => $canti,"genero" =>$genero]);
                    //     }

                    //     $cant_estr=$cant_estr+$canti;
                    //     $total_por_dep=$total_por_dep+$canti;
                    // }
                    if ($cant_tipo!=0) {
                        if($request->parametro2=="porcentaje"){
                            $cant_tipo=round((100/$cantidad_total)*$cant_tipo,2);
                        }
                    }
                    if ($cant_tipo!=0) {
                        //$nombre_est=$estructuras_->where('id',$estructura)->pluck('descripcion')->first();
                        //debug($nombre_est);
                        array_push($tipos,["nombre" => $tipo, "cantidades_transportes" => $cant_tipo]);
                    }


                    $total_por_dep=$total_por_dep+$cant_tipo;
                //}
                //debug(count($armas)!=0);

            }

        //     debug("datos a modificars");

        // debug("decomisos");
        // debug($estructuras);

         return response()->json(['transportes_detalles'=>$tipos,'totales_por_dep' => $total_por_dep]);
    }



    protected function build_sorterr($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }
    protected function colorear($arreglo){
        usort($arreglo, $this->build_sorterr('cantidades'));
        //debug("coloreando muni");
        //debug($arreglo);
        $colores=['#56c78b','#FE9923','#FE5C22'];
        $conteo_colores=count($colores)-1;
        for ($i=count($arreglo)-1; $i >= 0; $i--) {
            if($conteo_colores>=0){
                $arreglo[$i]['color']=$colores[$conteo_colores];
            }else{
                $arreglo[$i]['color']=$colores[0];
            }
            $conteo_colores--;
        }
        return $arreglo;
    }
    protected function porcentages($arreglo){
        $total=array_sum(array_column($arreglo, 'cantidades'));
        //debug(['vu',$arreglo,$total]);
        if ($total>0) {
            for ($i = 0; $i < count($arreglo); $i++) {
                $arreglo[$i]['cantidades']=round((100/$total)*$arreglo[$i]['cantidades'],4);
            }
        }


        return $arreglo;
    }
}


//Linea 941:detalle: cuando son cantidades demaciado
// pequeñas; setea el valor cero en cantidades por
//lo que en el front lo pinta de gris; si en lugar
//de 2 se setea un numero mayor funciona
