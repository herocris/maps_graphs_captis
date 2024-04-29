<?php

use App\Http\Controllers\ApiParametrosController;
use App\Http\Controllers\Admin\ManageApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Passport;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/prueba', function (Request $request) {
    return "probando";
});


Route::get('departamentos', [ApiParametrosController::class,'Departamentos'])->name('api.departamentos');
Route::get('municipios', [ApiParametrosController::class,'Municipios'])->name('api.municipios');
Route::get('paises', [ApiParametrosController::class,'Paises'])->name('api.paises');
Route::get('drogas', [ApiParametrosController::class,'Drogas'])->name('api.drogas');
Route::get('precursores', [ApiParametrosController::class,'Precursores'])->name('api.precursores');
Route::get('presentacionesDroga', [ApiParametrosController::class,'PresentacionesDroga'])->name('api.presentacionesDroga');
Route::get('presentacionesPrecursor', [ApiParametrosController::class,'PresentacionesPrecursor'])->name('api.presentacionesPrecursor');
Route::get('armas', [ApiParametrosController::class,'Armas'])->name('api.armas');
Route::get('municiones', [ApiParametrosController::class,'Municiones'])->name('api.municiones');
Route::get('estadoCiviles', [ApiParametrosController::class,'EstadoCiviles'])->name('api.estadoCiviles');
Route::get('identificaciones', [ApiParametrosController::class,'Identificaciones'])->name('api.identificaciones');
Route::get('ocupaciones', [ApiParametrosController::class,'Ocupaciones'])->name('api.ocupaciones');
Route::get('estructurasCriminales', [ApiParametrosController::class,'EstructurasCriminales'])->name('api.estructurasCriminales');
Route::get('instituciones', [ApiParametrosController::class,'Instituciones'])->name('api.instituciones');

Route::post('login', [ManageApiUserController::class,'login'])->name('api.login');
Route::post('logout', [ManageApiUserController::class,'logout'])->name('api.logout');


Route::get('usuarios', [ApiParametrosController::class,'Usuarios'])->name('api.usuarios');
Route::get('paises/{pais}', [ApiParametrosController::class,'Pais'])->name('api.pais');
Route::get('droga/{droga}', [ApiParametrosController::class,'Droga'])->name('api.droga');

// Route::group([
//     'as' => 'passport.',
//     'prefix' => config('passport.path', 'oauth'),
//     'namespace' => 'Laravel\Passport\Http\Controllers',
// ], function () {
//      Route::post('oauth/token',[AccessTokenController::class, 'issueToken']);
// });
//Route::post('oauth/token',[AccessTokenController::class, 'issueToken']);

