<?php

namespace App\Http\Controllers\Admin;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Institucion;
use App\Models\PresentacionDroga;
use App\Models\TipoDroga;
use App\Models\Droga;
use App\Models\User;
use App\Models\Arma;
use App\Models\EstructuraCriminal;
use App\Models\TipoMunicion;
use App\Models\Decomiso;
use App\Models\Grafica;
use App\Models\decomiso_droga;
use App\Models\PresentacionPrecursor;
use App\Models\Precursor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class GraficasController extends Controller
{
    public function __construct()
    {

        // $this->middleware(['permission:ver decomisos de droga'])->only('generar_droga');
        // $this->middleware(['permission:crear decomisos de droga'])->only('generar_precursor');
        // $this->middleware(['permission:editar decomisos de droga'])->only('generar_arma');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_municion');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_detenido');
        // $this->middleware(['permission:borrar decomisos de droga'])->only('generar_transporte');

        //$this->middleware(['permission:crear graficas'])->only(['generar_droga','mostrar','generar_precursor','generar_arma','generar_municion','generar_detenido','generar_transporte']);
    }
    //use LogsActivity;

    public function mostrar()
    {
        //dd("dsfs");
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
        return view('admin.graficas.grafica', compact('estr_noms','mun_noms','arm_noms','prec_noms','grafica','periodo','decomisos','dro_noms','prec_pres','dro_tipos','dro_pres','departamentos','municipios','instituciones'));
    }
    public function generar_droga(Request $request)
    {
        debug($request);
        $usuario=Auth::user();
        $datos=$request->tipo_gr=="comp_drogas"?
        ['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'criterio' => $request->tipo_gr, 'drogas' => implode(", ",$request->drogas), 'magnitud' => $request->parametro]]:
        ['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'criterio' => $request->tipo_gr, 'drogas' => implode(", ",$request->drogas), 'presentación' => implode(", ",$request->pres_drogas), 'magnitud' => $request->parametro]];

        $tipo_actividad=$request->tipo_gr=="comp_drogas"?"Se ha generado una grafica comparativa de drogas":($request->tipo_gr=="drogas"?"Se ha generado una grafica de drogas por presentación especifica":"Se ha generado una grafica de presentaciones por droga especifica");

        activity()
        ->withProperties($datos)
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log($tipo_actividad);


        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        debug($request->fecha_ini);
        debug($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }

        debug($fecha1->toString());
        debug($fecha2->toString());

        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        $obj_drogas=DB::table('drogas')->whereIn('id', $request->drogas)->get();//coleccion de objetos de presentaciones de drogas
        if($request->tipo_gr!="comp_drogas"){
            $obj_pre_drogas=DB::table('presentacion_drogas')->whereIn('id', $request->pres_drogas)->get();//coleccion de objetos de drogas
        }
        $coleccionn=$request->tipo_gr=="drogas"?$request->drogas:($request->tipo_gr=="comp_drogas"?$request->drogas:$request->pres_drogas);//coleccion de elementos a iterar segun criterio de la grafica
        $tipo_criterio=$request->tipo_gr=="drogas"?true:false;//tipo de criterio para grafica de drogas

        //$dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas
        //debug($request->instituciones);
        $decomisos_totales=new Collection;
        if ($request->tipo_gr=="comp_drogas") {
            $decomisos_totales=DB::table('decomiso_droga')->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereIn('droga_id', $request->drogas)->whereNull('decomisos.deleted_at')->whereNull('decomiso_droga.deleted_at')->get();//coleccion de decomisos totales
        } else {
            $decomisos_totales=DB::table('decomiso_droga')->join('decomisos', 'decomiso_droga.decomiso_id', '=', 'decomisos.id')->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereIn('droga_id', $request->drogas)->whereIn('presentacion_droga_id', $request->pres_drogas)->whereNull('decomisos.deleted_at')->whereNull('decomiso_droga.deleted_at')->get();//coleccion de decomisos totales
        }




        //////para graficas porcentuales//////
        $cant_total=0;
        //////////////////////////
        foreach($coleccionn as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                    if ($request->tipo_gr=="comp_drogas") {
                        $datos['datasets'][]=['label'=> $obj_drogas->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    } else {
                        $datos['datasets'][]=['label'=> ($tipo_criterio?$obj_drogas->where('id', $elemen)->pluck('descripcion'):$obj_pre_drogas->where('id', $elemen)->pluck('descripcion')), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    }


                    break;
                case ('line'):
                    if ($request->tipo_gr=="comp_drogas") {
                        $datos['datasets'][]=['label'=> $obj_drogas->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    } else {
                        $datos['datasets'][]=['label'=> ($tipo_criterio?$obj_drogas->where('id', $elemen)->pluck('descripcion'):$obj_pre_drogas->where('id', $elemen)->pluck('descripcion')), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    }


                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                    if($request->tipo_gr=="comp_drogas"){
                        $datos['labels'][]=$obj_drogas->where('id', $elemen)->pluck('descripcion');
                        $cantidades_=$decomisos_totales->where('droga_id', $elemen)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    }else{
                        $datos['labels'][]=($tipo_criterio?$obj_drogas->where('id', $elemen)->pluck('descripcion'):$obj_pre_drogas->where('id', $elemen)->pluck('descripcion'));
                        $cantidades_=$decomisos_totales->where($tipo_criterio?'droga_id':'presentacion_droga_id', $elemen)->whereIn($tipo_criterio?'presentacion_droga_id':'droga_id', $tipo_criterio?$request->pres_drogas:$request->drogas)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    }

                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $datos['datasets'][0]['data'][]=$cantidades_;

                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }


                    break;
            }


        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }


        if($request->grafica=="bar" || $request->grafica=="line"){
            //$diff = $fecha1->diffInDays($fecha2);
            //debug($diff);
            //foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':'1 month'), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
            //$periodo=CarbonPeriod::create($fecha1, $fecha2);
            //debug($periodo->toString());

            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                //foreach ($periodo as $period) {//iteracion de periodos segun tipo de periodo
                //foreach (CarbonPeriod::create($fecha1, '3 year', $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                //foreach (CarbonPeriod::create($fecha1, '1 month 31 day',$fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                // $algop=$period->month

                // if ($period->subMonth(1)) {
                //     # code...
                // }
                debug("periodo ". $period->toString());
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica
                //$allo=$period->first();
                //debug($mesfin);
                //$dt->addMonths(3);
                //debug($dt);
                //$dec_tiempo=$request->tipo_tiempo=="Anual"?
                //(DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->whereYear('fecha', $period->year)->pluck('id')):
                //(DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->whereYear('fecha', $period->year)->whereMonth('fecha', $period->month)->pluck('id'));//ids de decomisos contenidos entre las dos fechas ingresadas
                //$decomisos_totaless=$decomisos_totales->whereIn('decomiso_id', $dec_tiempo);//coleccion de decomisos totales

                $fecha1->subDay(1);

                $decomisos_totaless=new Collection;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }

                //////para graficas porcentuales//////
                $cant_total=0;
                foreach ($coleccionn as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    if ($request->tipo_gr=="comp_drogas") {
                        $cantidades_=$decomisos_totaless->where('droga_id', $elemen)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    } else {
                        $cantidades_=$decomisos_totaless->where($tipo_criterio?'droga_id':'presentacion_droga_id', $elemen)->whereIn($tipo_criterio?'presentacion_droga_id':'droga_id', $tipo_criterio?$request->pres_drogas:$request->drogas)->sum(($request->parametro=="cantidad"?'cantidad':'peso'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    }


                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }

                }
                debug($request->parametro);
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }

            }
            //debug("sin filter");
            //debug($decomisos_totaless2);
            //debug("con filter");
            //debug($decomisos_totaless);
        }
        //debug("coleccion de decomisos");
        //debug($datos);
        $nombre_prese="";
        if ($request->tipo_gr=="comp_drogas") {
            $nombre_prese="Lista de drogas";
        } else {
            $nombre_prese=$request->tipo_gr=="drogas"?$obj_pre_drogas->where('id', $request->pres_drogas[0])->pluck('descripcion'):$obj_drogas->where('id', $request->drogas[0])->pluck('descripcion');
        }



        return response()->json(['nombre_prese'=>$nombre_prese, 'otros_datos'=>$datos]);
    }

    public function generar_precursor(Request $request)
    {
        $usuario=Auth::user();
        $datos=$request->crit_prec=="comp_precursores"?
        ['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'criterio' => $request->crit_prec, 'precursores' => implode(", ",$request->precursores), 'magnitud' => $request->parametro]]:
        ['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'criterio' => $request->crit_prec, 'precursores' => implode(", ",$request->precursores), 'presentación' => implode(", ",$request->pres_precursores), 'magnitud' => $request->parametro]];

        $tipo_actividad=$request->crit_prec=="comp_precursores"?"Se ha generado una grafica comparativa de precursores":($request->crit_prec=="precursores"?"Se ha generado una grafica de precursores por presentación especifica":"Se ha generado una grafica de presentaciones por precursor especifico");

        activity()
        ->withProperties($datos)
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log($tipo_actividad);

        //debug($grafica);
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }

/////////////////////////////////
        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        //$ids_precursores=DB::table('precursors')->whereIn('id', $request->precursores)->pluck('id');
        $obj_precursores=DB::table('precursors')->whereIn('id', $request->precursores)->get();//coleccion de objetos de presentaciones de drogas
        if ($request->crit_prec!="comp_precursores") {
            $obj_pre_precursores=DB::table('presentacion_precursors')->whereIn('id', $request->pres_precursores)->get();//coleccion de objetos de drogas
        }

        $coleccionn=$request->crit_prec=="precursores"?$request->precursores:($request->crit_prec=="comp_precursores"?$request->precursores:$request->pres_precursores);//coleccion de elementos a iterar segun criterio de la grafica
        $tipo_criterio=$request->crit_prec=="precursores"?true:false;//tipo de criterio para grafica de drogas

        // $dec_tiempo_=[];
        // $dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas

        // $decomisos_totales=DB::table('decomiso_precursor')->whereIn('precursor_id', $request->precursores)->whereIn('presentacion_precursor_id', $request->pres_precursores)->whereIn('decomiso_id', $dec_tiempo_)->get();//coleccion de decomisos totales

        $decomisos_totales=new Collection;
        if ($request->crit_prec=="comp_precursores") {
            $decomisos_totales=DB::table('decomiso_precursor')->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereIn('precursor_id', $request->precursores)->whereNull('decomisos.deleted_at')->whereNull('decomiso_precursor.deleted_at')->get();//coleccion de decomisos totales
        } else {
            $decomisos_totales=DB::table('decomiso_precursor')->join('decomisos', 'decomiso_precursor.decomiso_id', '=', 'decomisos.id')->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereIn('precursor_id', $request->precursores)->whereIn('presentacion_precursor_id', $request->pres_precursores)->whereNull('decomisos.deleted_at')->whereNull('decomiso_precursor.deleted_at')->get();//coleccion de decomisos totales
        }


        //////para graficas porcentuales//////
        $cant_total=0;
        foreach($coleccionn as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                        if ($request->crit_prec=="comp_precursores") {
                            $datos['datasets'][]=['label'=> $obj_precursores->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                        } else {
                            $datos['datasets'][]=['label'=> ($tipo_criterio?$obj_precursores->where('id', $elemen)->pluck('descripcion'):$obj_pre_precursores->where('id', $elemen)->pluck('descripcion')), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                        }
                    break;
                case ('line'):
                        if ($request->crit_prec=="comp_precursores") {
                            $datos['datasets'][]=['label'=> $obj_precursores->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                        } else {
                            $datos['datasets'][]=['label'=> ($tipo_criterio?$obj_precursores->where('id', $elemen)->pluck('descripcion'):$obj_pre_precursores->where('id', $elemen)->pluck('descripcion')), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                        }
                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                        if($request->crit_prec=="comp_precursores"){
                            $datos['labels'][]=$obj_precursores->where('id', $elemen)->pluck('descripcion');
                            $cantidades_=$decomisos_totales->where('precursor_id', $elemen)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        }else{
                            $datos['labels'][]=($tipo_criterio?$obj_precursores->where('id', $elemen)->pluck('descripcion'):$obj_pre_precursores->where('id', $elemen)->pluck('descripcion'));
                            $cantidades_=$decomisos_totales->where($tipo_criterio?'precursor_id':'presentacion_precursor_id', $elemen)->whereIn($tipo_criterio?'presentacion_precursor_id':'precursor_id', $tipo_criterio?$request->pres_precursores:$request->precursores)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                        }

                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $datos['datasets'][0]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                    break;
            }


        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }
        if($request->grafica=="bar" || $request->grafica=="line"){
            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo

                //$dec_tiempo=$request->tipo_tiempo=="Anual"?(DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->whereYear('fecha', $period->year)->pluck('id')):(DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->whereYear('fecha', $period->year)->whereMonth('fecha', $period->month)->pluck('id'));//ids de decomisos contenidos entre las dos fechas ingresadas
                //$decomisos_totaless=$decomisos_totales->whereIn('decomiso_id', $dec_tiempo);//coleccion de decomisos totales
                debug("periodo ". $period->toString());
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica

                $fecha1->subDay(1);

                $decomisos_totaless;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    debug("llega");
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }



                //////para graficas porcentuales//////
                $cant_total=0;
                foreach ($coleccionn as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    if ($request->crit_prec=="comp_precursores") {
                        $cantidades_=$decomisos_totaless->where('precursor_id', $elemen)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    } else {
                        $cantidades_=$decomisos_totaless->where($tipo_criterio?'precursor_id':'presentacion_precursor_id', $elemen)->whereIn($tipo_criterio?'presentacion_precursor_id':'precursor_id', $tipo_criterio?$request->pres_precursores:$request->precursores)->sum(($request->parametro=="cantidad"?'cantidad':'volumen'));//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    }

                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                }
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }
            }
        }
        //debug("coleccion de decomisos");
        //debug($datos);
        $nombre_prese;
        if ($request->crit_prec=="comp_precursores") {
            $nombre_prese="Lista de precursores";
        } else {
            $nombre_prese=$request->crit_prec=="precursores"?$obj_pre_precursores->where('id', $request->pres_precursores[0])->pluck('descripcion'):$obj_precursores->where('id', $request->precursores[0])->pluck('descripcion');
        }


///////////////////////////////



        return response()->json(['nombre_prese'=>$nombre_prese ,'otros_datos'=>$datos]);

        //return view('admin.graficas.grafica', compact('periodo','decomisos'));
    }

    public function generar_arma(Request $request)
    {
        $usuario=Auth::user();

        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'armas' => implode(", ",$request->armas)]])
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log('Se ha generado una grafica de armas');

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }

        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        $obj_armas=DB::table('armas')->whereIn('id', $request->armas)->get();//coleccion de objetos de presentaciones de drogas

        //$dec_tiempo_=[];
        //$dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas

        $decomisos_totales=DB::table('arma_decomiso')->join('decomisos', 'arma_decomiso.decomiso_id', '=', 'decomisos.id')->whereIn('arma_id', $request->armas)->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereNull('decomisos.deleted_at')->whereNull('arma_decomiso.deleted_at')->get();//coleccion de decomisos totales
        //////para graficas porcentuales//////
        $cant_total=0;
        foreach($request->armas as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                    $datos['datasets'][]=['label'=> $obj_armas->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('line'):
                    $datos['datasets'][]=['label'=> $obj_armas->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                    $datos['labels'][]=$obj_armas->where('id', $elemen)->pluck('descripcion');
                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $cantidades_=$decomisos_totales->where('arma_id', $elemen)->sum("cantidad");//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][0]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                    break;
            }

        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }
        if($request->grafica=="bar" || $request->grafica=="line"){
            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica

                $fecha1->subDay(1);

                $decomisos_totaless;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }
                //////para graficas porcentuales//////
                $cant_total=0;
                foreach ($request->armas as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    $cantidades_=$decomisos_totaless->where('arma_id', $elemen)->sum("cantidad");//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                }
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }
            }
        }
        debug("coleccion de decomisos");
        debug($datos);

        return response()->json(['otros_datos'=>$datos]);
    }

    public function generar_municion(Request $request)
    {
        //debug($request);
        $usuario=Auth::user();

        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'municiones' => implode(", ",$request->municiones)]])
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log('Se ha generado una grafica de municiones');

        //debug($grafica);
        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }
///////
        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        $obj_municiones=DB::table('tipo_municions')->whereIn('id', $request->municiones)->get();//coleccion de objetos de presentaciones de drogas

        //$dec_tiempo_=[];
        //$dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas

        $decomisos_totales=DB::table('decomiso_tipo_municion')->join('decomisos', 'decomiso_tipo_municion.decomiso_id', '=', 'decomisos.id')->whereIn('tipo_municion_id', $request->municiones)->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereNull('decomisos.deleted_at')->whereNull('decomiso_tipo_municion.deleted_at')->get();//coleccion de decomisos totales
        //////para graficas porcentuales//////
        $cant_total=0;
        foreach($request->municiones as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                    $datos['datasets'][]=['label'=> $obj_municiones->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('line'):
                    $datos['datasets'][]=['label'=> $obj_municiones->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                    $datos['labels'][]=$obj_municiones->where('id', $elemen)->pluck('descripcion');
                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $cantidades_=$decomisos_totales->where('tipo_municion_id', $elemen)->sum("cantidad");//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][0]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                    break;
            }

        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }

        if($request->grafica=="bar" || $request->grafica=="line"){
            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica

                $fecha1->subDay(1);

                $decomisos_totaless;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }
                //////para graficas porcentuales//////
                $cant_total=0;
                foreach ($request->municiones as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    $cantidades_=$decomisos_totaless->where('tipo_municion_id', $elemen)->sum("cantidad");//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                }
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }
            }
        }
        debug("coleccion de decomisos");
        debug($datos);

        return response()->json(['otros_datos'=>$datos]);

        //return view('admin.graficas.grafica', compact('periodo','decomisos'));
    }

    public function generar_detenido(Request $request)
    {
        debug($request->rango_edad);
        $usuario=Auth::user();

        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'genero' => implode(", ",$request->dete_gen), 'estructura' => implode(", ",$request->dete_estr)]])
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log('Se ha generado una grafica de detenidos');

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }

        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        $dec_tiempo_=[];
        $estructuras=DB::table('estructura_criminals')->whereIn('id', $request->dete_estr)->get();
        $dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas
        $decomisos_totales=DB::table('decomiso_detenidos')->join('decomisos', 'decomiso_detenidos.decomiso_id', '=', 'decomisos.id')->whereIn('genero', $request->dete_gen)->whereIn('estructura_id', $request->dete_estr)->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereBetween('edad', [$request->rango_edad[0], $request->rango_edad[1]])->whereIn('decomisos.institucion_id', $request->instituciones)->whereNull('decomisos.deleted_at')->whereNull('decomiso_detenidos.deleted_at')->get();//coleccion de objetos de presentaciones de drogas

        //////para graficas porcentuales//////
        $cant_total=0;
        foreach($request->dete_estr as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                    $datos['datasets'][]=['label'=> $estructuras->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('line'):
                    $datos['datasets'][]=['label'=> $estructuras->where('id', $elemen)->pluck('descripcion'), 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                    $datos['labels'][]=$estructuras->where('id', $elemen)->pluck('descripcion');
                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $cantidades_=$decomisos_totales->where('estructura_id', $elemen)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][0]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                    break;
            }

        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }

        if($request->grafica=="bar" || $request->grafica=="line"){
            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica

                $fecha1->subDay(1);

                $decomisos_totaless;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }
                //////para graficas porcentuales//////
                $cant_total=0;
                foreach ($request->dete_estr as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    $cantidades_=$decomisos_totaless->where('estructura_id', $elemen)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                }
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }

            }
        }
        debug("coleccion de decomisos");
        debug($datos);

        return response()->json(['otros_datos'=>$datos]);
    }

    public function generar_transporte(Request $request)
    {
        $usuario=Auth::user();

        activity()
        ->withProperties(['attributes' => ['fecha_inicial' => $request->fecha_ini, 'fecha_final' => $request->fecha_fin, 'periodo' => $request->tipo_tiempo, 'tipo_decomiso' => $request->tipo_decomiso, 'transporte_tipo' => implode(", ",$request->trans_tip)]])
        ->causedBy($usuario)
        ->event('created')
        ->useLog($usuario->name)
        ->log('Se ha generado una grafica de transportes');

        $fecha1 = Carbon::parse($request->fecha_ini);
        $fecha2 = Carbon::parse($request->fecha_fin);

        if ($request->tipo_tiempo=="Mensual" || $request->tipo_tiempo=="Trimestral" || $request->tipo_tiempo=="Semestral") {
            $fecha1=$fecha1->startOfMonth();
            $fecha2=$fecha2->endOfMonth();
        }
        if ($request->tipo_tiempo=="Anual" && $request->fecha_ini) {
            $fecha1=$fecha1->year($request->fecha_ini)->startOfYear();
            $fecha2=$fecha2->year($request->fecha_fin)->endOfYear();
        }

        $datos;
        if($request->grafica=="bar" || $request->grafica=="line"){
            $datos=['datasets'=> [],'labels'=>[]];
        }else{
            $datos=['datasets'=> [],'labels'=>[]];
            array_push($datos['datasets'], ['label'=> 'nombre', 'backgroundColor'=>[], 'data'=>[], 'borderWidth'=> 1, 'hoverOffset'=> 30]);
        }

        $dec_tiempo_=[];
        $dec_tiempo_=DB::table('decomisos')->whereBetween('fecha', [$fecha1, $fecha2])->pluck('id');//ids de decomisos contenidos entre las dos fechas ingresadas
        $decomisos_totales=DB::table('decomiso_transportes')->join('decomisos', 'decomiso_transportes.decomiso_id', '=', 'decomisos.id')->whereIn('tipo', $request->trans_tip)->whereBetween('decomisos.fecha', [$fecha1, $fecha2])->whereIn('decomisos.institucion_id', $request->instituciones)->whereNull('decomisos.deleted_at')->whereNull('decomiso_transportes.deleted_at')->get();//coleccion de objetos de presentaciones de drogas

        //////para graficas porcentuales//////
        $cant_total=0;
        foreach($request->trans_tip as $elemen2 => $elemen){
            $fondo='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            $borde='rgba('.rand(1, 210).', '.rand(1, 210).', '.rand(1, 210).', 1)';
            switch ($request->grafica) {
                case ('bar'):
                    $datos['datasets'][]=['label'=> $elemen, 'backgroundColor'=>$fondo, 'borderColor'=>$borde, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('line'):
                    $datos['datasets'][]=['label'=> $elemen, 'backgroundColor'=>$fondo, 'fill'=>false, 'borderColor'=>$fondo, 'tension'=>0, 'data'=>[], 'borderWidth'=> 1];
                    break;
                case ('pie' || 'doughnuts'):
                    $fecha1->subDay(1);
                    $datos['labels'][]=$elemen;
                    $datos['datasets'][0]['backgroundColor'][]=$fondo;
                    $cantidades_=$decomisos_totales->where('tipo', $elemen)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][0]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                    break;
            }

        }
        //////para graficas porcentuales//////
        if($request->parametro2=="porcentaje"){
            for ($i = 0; $i < count($datos['datasets'][0]['data']); $i++) {
                $datos['datasets'][0]['data'][$i]=(100/$cant_total)*$datos['datasets'][0]['data'][$i];
            }
        }

        if($request->grafica=="bar" || $request->grafica=="line"){
            //////para graficas porcentuales//////
            $contap=0;
            foreach (CarbonPeriod::create($fecha1, ($request->tipo_tiempo=="Anual"?'1 year':($request->tipo_tiempo=="Mensual"?'1 month':($request->tipo_tiempo=="Trimestral"?'3 month':($request->tipo_tiempo=="Diario"?'10 year':'6 month')))), $fecha2) as $period) {//iteracion de periodos segun tipo de periodo
                $mesfin = Carbon::create($period->year, $period->month, $period->day)->addMonths($request->tipo_tiempo=="Trimestral"?2:($request->tipo_tiempo=="Semestral"?5:0))->monthName;//creación de fecha para poner el ultimo mes en caso de que sea trimestral o semestral
                $datos['labels'][]=$request->tipo_tiempo=="Anual"?$period->year:($request->tipo_tiempo=="Mensual"?$period->monthName. " ".$period->year:($request->tipo_tiempo=="Trimestral"?$period->monthName. " - ".$mesfin. " ".$period->year:($request->tipo_tiempo=="Semestral"?$period->monthName. " - ".$mesfin. " ".$period->year:$fecha1->format('Y-m-d'). " - ".$fecha2->format('Y-m-d'))));//seteo de nombre de fecha en labels de la grafica

                $fecha1->subDay(1);

                $decomisos_totaless;
                if ($request->tipo_tiempo=="Anual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year;
                    });
                }else if($request->tipo_tiempo=="Mensual") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        return Carbon::parse($value->fecha)->year === $period->year && Carbon::parse($value->fecha)->month === $period->month;
                    });
                }else if($request->tipo_tiempo=="Diario") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2]);
                }else if($request->tipo_tiempo=="Trimestral") {
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //debug(Carbon::parse($value->fecha)->quarter);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2);
                    });
                }else{
                    $decomisos_totaless=$decomisos_totales->whereBetween('fecha', [$fecha1, $fecha2])->filter(function ($value) use ($period) {
                        //$period->addMonth(1);
                        //debug($period->month);
                        return Carbon::parse($value->fecha)->year === $period->year && (Carbon::parse($value->fecha)->month === $period->month
                        || Carbon::parse($value->fecha)->month === ($period->month)+1
                        || Carbon::parse($value->fecha)->month === ($period->month)+2
                        || Carbon::parse($value->fecha)->month === ($period->month)+3
                        || Carbon::parse($value->fecha)->month === ($period->month)+4
                        || Carbon::parse($value->fecha)->month === ($period->month)+5);
                    });
                }
                //////para graficas porcentuales//////
                $cant_total=0;
                //$decomisos_totaless=$decomisos_totales->whereIn('decomiso_id', $dec_tiempo);//coleccion de decomisos totales
                foreach ($request->trans_tip as $elemen2 => $elemen) {//iteracion de elementos segun criterio de la grafica
                    $cantidades_=$decomisos_totaless->where('tipo', $elemen)->count();//suma total de el elemento que se esta iterando segun el criterio de magnitud
                    $datos['datasets'][$elemen2]['data'][]=$cantidades_;
                    //////para graficas porcentuales//////
                    if($request->parametro2=="porcentaje"){
                        $cant_total=$cant_total+$cantidades_;
                    }
                }
                //////para graficas porcentuales//////
                if($request->parametro2=="porcentaje"){
                    for ($i = 0; $i < count($datos['datasets']); $i++) {
                        debug("llega d");
                        $datos['datasets'][$i]['data'][$contap]=(100/($cant_total==0?1:$cant_total))*$datos['datasets'][$i]['data'][$contap];
                    }
                    $contap++;
                }
            }
        }
        debug("coleccion de decomisos");
        debug($datos);


        return response()->json(['otros_datos'=>$datos]);
    }
//////////////////////////////////para grafica de bitacora///////////////////////////
////////se copio accion que genera datos de grafica del controlador de Bitacora a controlador de Graficas para poder mostrarlo a no administradores 03/07/2023
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

    ////////se copio accion que muestra vista de grafica del controlador de Bitacora a controlador de Graficas para poder mostrarlo a no administradores 03/07/2023
    public function mostrarGrafica(){
        $usuarios= User::all();
        $instituciones= Institucion::all();

        return view('admin.bitacora.grafica_estadistica', compact('usuarios','instituciones'));
    }


}
