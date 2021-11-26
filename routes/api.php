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
        Route::get('empresas/all', 'EmpresasController@getAll');
        Route::get('empresas/enable/{id}', 'EmpresasController@enable');
        Route::resource('empresas', 'EmpresasController');

        Route::get('sucursales/all', 'SucursalesController@getAll');
        Route::get('sucursales/enable/{id}', 'SucursalesController@enable');
        Route::resource('sucursales', 'SucursalesController');

        Route::get('colores/all', 'ColoresController@getAll');
        Route::get('colores/enable/{id}', 'ColoresController@enable');
        Route::resource('colores', 'ColoresController');

        Route::get('marcas/all', 'MarcasController@getAll');
        Route::get('marcas/enable/{id}', 'MarcasController@enable');
        Route::resource('marcas', 'MarcasController');

        Route::get('modelos/all', 'ModelosController@getAll');
        Route::get('modelos/enable/{id}', 'ModelosController@enable');
        Route::resource('modelos', 'ModelosController');

        Route::get('usuarios/all', 'UsersController@getAll');
        Route::get('usuarios/enable/{id}', 'UsersController@enable');
        Route::resource('usuarios', 'UsersController');

        Route::get('areas-trabajo/all', 'AreasTrabajoController@getAll');
        Route::get('areas-trabajo/enable/{id}', 'AreasTrabajoController@enable');
        Route::resource('areas-trabajo', 'AreasTrabajoController');

        Route::get('roles/all', 'RolesController@getAll');
        Route::get('roles/enable/{id}', 'RolesController@enable');
        Route::resource('roles', 'RolesController');
    });
});


