<?php

use App\Models\User;
use App\Models\Decomiso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OscuroController;
use App\Http\Controllers\Admin\RutaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TipoDrogaController;
use App\Http\Controllers\Admin\PresentacionController;
use App\Http\Controllers\Admin\PresentacionDrogaController;
use App\Http\Controllers\Admin\PresentacionPrecursorController;
use App\Http\Controllers\Admin\UsersRolesController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PrecursorController;
use App\Http\Controllers\Admin\ArmaController;
use App\Http\Controllers\Admin\EstructuraCriminalController;
use App\Http\Controllers\Admin\EstadoCivilController;
use App\Http\Controllers\Admin\TipoMunicionController;
use App\Http\Controllers\Admin\IdentificacionController;
use App\Http\Controllers\Admin\OcupacionController;
use App\Http\Controllers\Admin\InstitucionController;
use App\Http\Controllers\Admin\DecomisoDrogaController;
use App\Http\Controllers\Admin\DecomisoPrecursorController;
use App\Http\Controllers\Admin\DecomisoMunicionController;
use App\Http\Controllers\Admin\DecomisoArmaController;
use App\Http\Controllers\Admin\DecomisoDetenidosController;
use App\Http\Controllers\Admin\DecomisoTransportesController;
use App\Http\Controllers\Admin\TipoParametroController;
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\DecomisoController;
use App\Http\Controllers\Admin\DrogaController;
use App\Http\Controllers\Admin\GraficaController;
use App\Http\Controllers\Admin\GraficasController;
use App\Http\Controllers\Admin\MapasController;
use App\Http\Controllers\Admin\RespaldoController;
use App\Http\Controllers\Admin\UsersPermissionsController;
use App\Http\Controllers\Admin\enviar_authController;
use App\Http\Controllers\Admin\Activity_log;
use App\Http\Controllers\Admin\ApiGestionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    try {
        Schema::hasTable('users');
        return view('auth.login');
    } catch (\Exception $e) {
        //dd("llega");
        //return redirect()->route('respaldo.mostrar_restaurarRespaldo');
        $error=false;
        return view('admin.respaldo.restaurar', compact('error'));
    }
    //dump($mostrar);
    //return view('auth.login',compact('mostrar','error'));
})->middleware(['guest:'.config('fortify.guard'),'prevent-back-history']);//para e

Route::get('mostrarrestaurarrespaldo', [RespaldoController::class,'mostrar_restaurarRespaldo'])->name('respaldo.mostrar_restaurarRespaldo');
Route::post('restaurarrespaldo', [RespaldoController::class,'cargar_Respaldo'])->name('respaldo.cargarRespaldo');

Route::get('ruta',[RutaController::class,'direccionar'])->name('direccionar');

Route::group(['prefix'=>'admin','middleware'=>['auth:sanctum','prevent-back-history','verified','role:Administrador|SuperAdministrador']],function () {



    Route::resource('user', UserController::class);
    Route::get('userr/{Id}', [UserController::class,'restaurar'])->name('user.restore');


    Route::resource('role', RoleController::class);
    Route::get('rolee/{Id}', [RoleController::class,'restaurar'])->name('role.restore');

    Route::resource('permission', PermissionController::class);
    Route::get('permissionn/{Id}', [PermissionController::class,'restaurar'])->name('permission.restore');

    Route::resource('tipodroga', TipoDrogaController::class);
    Route::get('tipodrogaa/{Id}', [TipoDrogaController::class,'restaurar'])->name('tipodroga.restore');

    Route::resource('presentacion', PresentacionController::class);

    Route::resource('presentaciondroga', PresentacionDrogaController::class);
    Route::get('presentaciondrogaa/{Id}', [PresentacionDrogaController::class,'restaurar'])->name('presentaciondroga.restore');

    Route::resource('presentacionprecursor', PresentacionPrecursorController::class);
    Route::get('presentacionprecursorr/{Id}', [PresentacionPrecursorController::class,'restaurar'])->name('presentacionprecursor.restore');

    Route::resource('precursor', PrecursorController::class);
    Route::get('precursorr/{Id}', [PrecursorController::class,'restaurar'])->name('precursor.restore');

    Route::resource('tipomunicion', TipoMunicionController::class);
    Route::get('tipomunicionn/{Id}', [TipoMunicionController::class,'restaurar'])->name('tipomunicion.restore');

    Route::resource('identificacion', IdentificacionController::class);
    Route::get('identificacionn/{Id}', [IdentificacionController::class,'restaurar'])->name('identificacion.restore');

    Route::resource('ocupacion', OcupacionController::class);
    Route::get('ocupacionn/{Id}', [OcupacionController::class,'restaurar'])->name('ocupacion.restore');

    Route::resource('estructura', EstructuraCriminalController::class);
    Route::get('estructuraa/{Id}', [EstructuraCriminalController::class,'restaurar'])->name('estructura.restore');

    Route::resource('arma', ArmaController::class);
    Route::get('armaa/{Id}', [ArmaController::class,'restaurar'])->name('arma.restore');

    Route::resource('estado', EstadoCivilController::class);
    Route::get('estadoo/{Id}', [EstadoCivilController::class,'restaurar'])->name('estado.restore');

    Route::resource('institucion', InstitucionController::class);
    Route::get('institucionn/{Id}', [InstitucionController::class,'restaurar'])->name('institucion.restore');

    Route::resource('tipoParametro', TipoParametroController::class);
    Route::resource('parametro', ParametroController::class);
    Route::resource('droga', DrogaController::class);
    Route::get('drogaa/{Id}', [DrogaController::class,'restaurar'])->name('droga.restore');

    Route::resource('decomiso', DecomisoController::class);



    Route::get('mostrarrespaldo', [RespaldoController::class,'mostrar_generarRespaldo'])->name('respaldo.generar');
    Route::post('generarrespaldo', [RespaldoController::class,'generarRespaldo'])->name('respaldo.generarRespaldo');

    //Route::get('mostrarrestaurarrespaldo', [RespaldoController::class,'mostrar_restaurarRespaldo'])->name('respaldo.restaurar');



    //Route::resource('respaldo', RespaldoController::class);

    Route::post('autentificar',[enviar_authController::class,'store'])->name('autentificar');
    Route::put('users/{user}/roles',[UsersRolesController::class,'update'])->name('admin.users.roles.update');
    Route::put('users/{user}/permissions',[UsersPermissionsController::class,'update'])->name('admin.users.permissions.update');
    Route::get('bitacora',[Activity_log::class,'index'])->name('bitacora');



    Route::get('decomiso_importar_ver', [DecomisoController::class,'ver_importar'])->name('decomiso.decomiso_importar_ver');
    Route::post('prueba_ajax',[RutaController::class,'prueba_ajax'])->name('prueba_ajax');
    Route::post('decomiso_importar', [DecomisoController::class,'importar'])->name('decomiso.decomiso_importar');

    /////////rutas de api////////////////////
    Route::get('personalTokens', [ApiGestionController::class,'indexTokens'])->name('api.TokensPersonales');
    Route::get('apiClients', [ApiGestionController::class,'indexApiClients'])->name('api.ClientesApi');
    Route::get('authorizedApiClients', [ApiGestionController::class,'indexAuthorizedApiClients'])->name('api.ClientesAutorizadosApi');

    Route::post('/pruebapost', [ApiGestionController::class,'pruebapost'])->name('api.pruebapost');


});

Route::get('tablero', [MapasController::class,'generar_dash'])->name('index')->middleware(['auth:sanctum','verified','prevent-back-history']);///////// ruta a la pantalla de inicio

Route::group(['prefix'=>'/','middleware'=>['auth:sanctum','verified','prevent-back-history']],function () {





    Route::resource('decomiso', DecomisoController::class);
    Route::get('decomisooo/{Id}', [DecomisoController::class,'restaurar'])->name('decomiso.restore');
    Route::get('decomisoo/{decomiso}/{Id}', [DecomisoController::class,'desabilitados'])->name('decomisoo');
    Route::get('decomiso_buscar/{Id}', [DecomisoController::class,'buscar'])->name('decomiso_buscar');
    Route::get('decomisosPaginados', [DecomisoController::class,'indexpaginado'])->name('decomiso.decomisosPaginados');
    //Route::get('decomiso_ajax', [DecomisoController::class,'index2'])->name('decomiso_ajax');

    Route::resource('decomisodroga', DecomisoDrogaController::class);
    Route::get('decomisodrogaa/{Id}', [DecomisoDrogaController::class,'restaurar'])->name('decomisodroga.restaurar');
    Route::get('decomisodroga1/{Id}/{tipo}', [DecomisoDrogaController::class,'habilitados'])->name('decomisodroga.habilitados');

    Route::resource('decomisoprecursor', DecomisoPrecursorController::class);
    Route::get('decomisoprecursorr/{Id}', [DecomisoPrecursorController::class,'restaurar'])->name('decomisoprecursor.restaurar');
    Route::get('decomisoprecursor1/{Id}/{tipo}', [DecomisoPrecursorController::class,'habilitados'])->name('decomisoprecursor.habilitados');

    Route::resource('decomisoarma', DecomisoArmaController::class);
    Route::get('decomisoarmaa/{Id}', [DecomisoArmaController::class,'restaurar'])->name('decomisoarma.restaurar');
    Route::get('decomisoarma1/{Id}/{tipo}', [DecomisoArmaController::class,'habilitados'])->name('decomisoarma.habilitados');

    Route::resource('decomisomunicion', DecomisoMunicionController::class);
    Route::get('decomisomunicionn/{Id}', [DecomisoMunicionController::class,'restaurar'])->name('decomisomunicion.restaurar');
    Route::get('decomisomunicion1/{Id}/{tipo}', [DecomisoMunicionController::class,'habilitados'])->name('decomisomunicion.habilitados');

    Route::resource('decomisodetenido', DecomisoDetenidosController::class);
    Route::get('decomisodetenidoo/{Id}', [DecomisoDetenidosController::class,'restaurar'])->name('decomisodetenido.restaurar');
    Route::get('decomisodetenido1/{Id}/{tipo}', [DecomisoDetenidosController::class,'habilitados'])->name('decomisodetenido.habilitados');

    Route::resource('decomisotransporte', DecomisoTransportesController::class);
    Route::get('decomisotransportee/{Id}', [DecomisoTransportesController::class,'restaurar'])->name('decomisotransporte.restaurar');
    Route::get('decomisotransporte1/{Id}/{tipo}', [DecomisoTransportesController::class,'habilitados'])->name('decomisotransporte.habilitados');


    //Route::resource('grafica', GraficaController::class);
    Route::get('motrargraficas', [GraficasController::class,'mostrar'])->name('mostrar');
    Route::get('grafica_drogas', [GraficasController::class,'generar_droga'])->name('generar_droga');
    Route::get('grafica_precursores', [GraficasController::class,'generar_precursor'])->name('generar_precursor');
    Route::get('grafica_arma', [GraficasController::class,'generar_arma'])->name('generar_arma');
    Route::get('grafica_municion', [GraficasController::class,'generar_municion'])->name('generar_municion');
    Route::get('grafica_detenido', [GraficasController::class,'generar_detenido'])->name('generar_detenido');
    Route::get('grafica_transporte', [GraficasController::class,'generar_transporte'])->name('generar_transporte');


    ///////////////////////////////graficas de bitacora////////////////////////////////
    ////////se copiaron rutas de graficas de bitacora que estaban en la parte administrativa para poder ser usadas por no administradores 03/07/2023
    Route::get('graficassbitacora',[GraficasController::class,'generargrafica'])->name('graficassBitacora');
    Route::get('mostrargraficassbitacora',[GraficasController::class,'mostrarGrafica'])->name('mostrarGraficassBitacora');

    ///////////
    //Route::get('grafica_comp_drogas', [GraficasController::class,'generar_comparativo_droga'])->name('grafica_comp_drogas');
    ////////////

    Route::get('mostrarmapas', [MapasController::class,'mostrar'])->name('mapa.mostrar_mapa');
    Route::get('mapa_drogas', [MapasController::class,'generar_droga'])->name('mapa.generar_droga');
    ///////
    Route::get('detalles_droga_lugar', [MapasController::class,'generar_droga_detalles_lugar'])->name('detalles_droga_lugar');
    //Route::get('droga_por_municipio', [MapasController::class,'generar_droga_por_departamento'])->name('droga_por_municipio');
    //Route::get('detalles_droga_municipio', [MapasController::class,'generar_droga_detalles_municipio'])->name('detalles_droga_municipio');
    ///////
    Route::get('mapa_precursores', [MapasController::class,'generar_precursor'])->name('mapa.generar_precursor');
    Route::get('detalles_precursor_lugar', [MapasController::class,'generar_precursor_detalles_lugar'])->name('detalles_precursor_lugar');

    Route::get('mapa_armas', [MapasController::class,'generar_arma'])->name('mapa.generar_arma');
    Route::get('detalles_arma_lugar', [MapasController::class,'generar_arma_detalles_lugar'])->name('detalles_arma_lugar');

    Route::get('mapa_municiones', [MapasController::class,'generar_municion'])->name('mapa.generar_municion');
    Route::get('detalles_municion_lugar', [MapasController::class,'generar_municion_detalles_lugar'])->name('detalles_municion_lugar');

    Route::get('mapa_detenidos', [MapasController::class,'generar_detenido'])->name('mapa.generar_detenido');
    Route::get('detalles_detenido_lugar', [MapasController::class,'generar_detenido_detalles_lugar'])->name('detalles_detenido_lugar');

    Route::get('mapa_transportes', [MapasController::class,'generar_transporte'])->name('mapa.generar_transporte');
    Route::get('detalles_transporte_lugar', [MapasController::class,'generar_transporte_detalles_lugar'])->name('detalles_transporte_lugar');

    // Route::get('mostrarrespaldo', [RespaldoController::class,'mostrar_generarRespaldo'])->name('respaldo.generar');
    // Route::post('generarrespaldo', [RespaldoController::class,'generarRespaldo'])->name('respaldo.generarRespaldo');

    Route::get('mostrarrestaurarrespaldo', [RespaldoController::class,'mostrar_restaurarRespaldo'])->name('respaldo.restaurar');



    //Route::resource('respaldo', RespaldoController::class);


});
Route::put('oscuro/{user}', [OscuroController::class,'oscuro'])->name('oscuro.oscuro');

// Route::get('dashboard', function () {
//     $decomisos=Decomiso::all();
//         //debug($decomisos);
//     return view('admin.dashboard',compact('decomisos'));
//     //return view('admin.dashboard');
// })->name('dashboard');



