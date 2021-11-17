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

Route::prefix('dash')->group(function () {
    Route::post('login', 'SessionController@login');

    Route::middleware('verify.jwt')->group(function () {
        Route::get('empresas/all', 'EmpresasController@getAll');
        Route::resource('empresas', 'EmpresasController');

        Route::get('sucursales/all', 'SucursalesController@getAll');
        Route::resource('sucursales', 'SucursalesController');



        Route::resource('users', 'UsersController');
    });
});


