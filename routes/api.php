<?php

use App\Http\Controllers\EmpresasController;
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


        //region Catálogo de vehículos

        Route::get('marcas-vehiculos/all', 'MarcasVehiculosController@getAll');
        Route::get('marcas-vehiculos/enable/{id}', 'MarcasVehiculosController@enable');
        Route::resource('marcas-vehiculos', 'MarcasVehiculosController');

        Route::get('categorias-vehiculos/all', 'CategoriasVehiculosController@getAll');
        Route::get('categorias-vehiculos/enable/{id}', 'CategoriasVehiculosController@enable');
        Route::resource('categorias-vehiculos', 'CategoriasVehiculosController');

        Route::get('vehiculos/all', 'VehiculosController@getAll');
        Route::get('vehiculos/enable/{id}', 'VehiculosController@enable');
        Route::resource('vehiculos', 'VehiculosController');

        //endregion

        //region CONTROL ACCESO
        Route::get('usuarios/all', 'UsersController@getAll');
        Route::get('usuarios/enable/{id}', 'UsersController@enable');
        Route::resource('usuarios', 'UsersController');

        Route::get('areas-trabajo/all', 'AreasTrabajoController@getAll');
        Route::get('areas-trabajo/enable/{id}', 'AreasTrabajoController@enable');
        Route::resource('areas-trabajo', 'AreasTrabajoController');

        Route::get('roles/all', 'RolesController@getAll');
        Route::get('roles/enable/{id}', 'RolesController@enable');
        Route::resource('roles', 'RolesController');

        Route::get('sucursales/all', 'SucursalesController@getAll');
        Route::get('sucursales/enable/{id}', 'SucursalesController@enable');
        Route::resource('sucursales', 'SucursalesController');
        //endregion

        //region LISTADO EMPRESAS
        Route::get('empresas/all', 'EmpresasController@getAll');
        Route::get('empresas/enable/{id}', 'EmpresasController@enable');
        Route::resource('empresas', 'EmpresasController');

        Route::get('comisionistas/all', 'ComisionistasController@getAll');
        Route::get('comisionistas/enable/{id}', 'ComisionistasController@enable');
        Route::resource('comisionistas', 'ComisionistasController');

        Route::get('clientes/all', 'ClientesController@getAll');
        Route::get('clientes/enable/{id}', 'ClientesController@enable');
        Route::resource('clientes', 'ClientesController');
        //endregion

    });
});


