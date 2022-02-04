<?php

use App\Http\Controllers\hotelesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//region RUTAS GLOBALES
Route::post('activate-usr-token', 'SessionController@activateUserByCode');
Route::post('recovery-psw', 'SessionController@generateRecoveryPswToken');
Route::post('review-recovery-token', 'SessionController@reviewToken');
Route::post('change-pwd-token', 'SessionController@changePwdByToken');
//endregion

Route::prefix('dash')->group(function () {
    Route::post('login', 'SessionController@login');

    Route::middleware('verify.jwt')->group(function () {

        //region CONTRATOS
        Route::post('contratos/save-progress', 'ContratoController@saveProcess');
        Route::get('contratos/{num_contrato}', 'ContratoController@getContract');
        //endregion

        Route::post('files/store-docs', 'DocsController@storeFiles');
        Route::post('files/get-docs', 'DocsController@getActiveFiles');
        Route::post('files/delete', 'DocsController@deleteFile');

        //region Catálogo de vehículos

        Route::get('marcas-vehiculos/all', 'MarcasVehiculosController@getAll');
        Route::get('marcas-vehiculos/enable/{id}', 'MarcasVehiculosController@enable');
        Route::resource('marcas-vehiculos', 'MarcasVehiculosController');

        Route::get('categorias-vehiculos/all', 'CategoriasVehiculosController@getAll');
        Route::get('categorias-vehiculos/enable/{id}', 'CategoriasVehiculosController@enable');
        Route::resource('categorias-vehiculos', 'CategoriasVehiculosController');

        Route::get('clases-vehiculos/all', 'ClasesVehiculosController@getAll');
        Route::get('clases-vehiculos/enable/{id}', 'ClasesVehiculosController@enable');
        Route::resource('clases-vehiculos', 'ClasesVehiculosController');

        Route::get('vehiculos/all', 'VehiculosController@getAll');
        Route::get('vehiculos/enable/{id}', 'VehiculosController@enable');
        Route::get('vehiculos/list', 'VehiculosController@getList');
        Route::resource('vehiculos', 'VehiculosController');

        //endregion

        //region CONTROL ACCESO
        Route::get('usuarios/all', 'UsersController@getAll');
        Route::get('usuarios/enable/{id}', 'UsersController@enable');
        Route::post('usuarios/change-psw', 'SessionController@changePwdAdmin');
        Route::resource('usuarios', 'UsersController');

        Route::get('areas-trabajo/all', 'AreasTrabajoController@getAll');
        Route::get('areas-trabajo/enable/{id}', 'AreasTrabajoController@enable');
        Route::resource('areas-trabajo', 'AreasTrabajoController');

        Route::get('roles/all', 'RolesController@getAll');
        Route::get('roles/enable/{id}', 'RolesController@enable');
        Route::resource('roles', 'RolesController');

        Route::get('sucursales/all', 'SucursalesController@getAll');
        Route::get('sucursales/enable/{id}', 'SucursalesController@enable');
        Route::get('sucursales/list', 'SucursalesController@getList');
        Route::resource('sucursales', 'SucursalesController');
        //endregion

        //region LISTADO HOTELES
        Route::get('hoteles/all', 'HotelesController@getAll');
        Route::get('hoteles/enable/{id}', 'HotelesController@enable');
        Route::resource('hoteles', 'HotelesController');

        Route::get('comisionistas/all', 'ComisionistasController@getAll');
        Route::get('comisionistas/enable/{id}', 'ComisionistasController@enable');
        Route::resource('comisionistas', 'ComisionistasController');

        Route::get('clientes/all', 'ClientesController@getAll');
        Route::get('clientes/enable/{id}', 'ClientesController@enable');
        Route::get('clientes/list', 'ClientesController@getList');
        Route::resource('clientes', 'ClientesController');

        Route::get('tarjetas/all', 'TarjetasController@getAll');
        Route::get('tarjetas/enable/{id}', 'TarjetasController@enable');
        Route::resource('tarjetas', 'TarjetasController');
        //endregion

        //region CONFIGURACIÓN APP
        Route::resource('tipos-tarifas', 'TiposTarifasController');

        Route::get('tarifas-extras/all', 'TarifasExtrasController@getAll');
        Route::get('tarifas-extras/enable/{id}', 'TarifasExtrasController@enable');
        Route::get('tarifas-extras/list', 'TarifasExtrasController@getList');
        Route::resource('tarifas-extras', 'TarifasExtrasController');
        //endregion

        //region UBICACIONES
        Route::get('ubicaciones/all', 'UbicacionesController@getAll');
        Route::get('ubicaciones/enable/{id}', 'UbicacionesController@enable');
        Route::resource('ubicaciones', 'UbicacionesController');
        //endregion

    });
});


