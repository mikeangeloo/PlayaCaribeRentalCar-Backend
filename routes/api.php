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

Route::get('contratos/pdf/{id}', 'ContratoController@viewPDF');
Route::get('test/pdf', 'ContratoController@viewReservaPDF');
//endregion

Route::prefix('dash')->group(function () {
    Route::post('login', 'SessionController@login');
    Route::middleware('verify.jwt')->group(function () {

        //region DASHBOARD
        Route::get('dashboard-info', 'ReportesController@dashboardInfo');
        //endregion

        //region CONTRATOS
        Route::post('contratos/save-progress', 'ContratoController@saveProcess');
        Route::get('contratos/{num_contrato}', 'ContratoController@getContract');
        Route::get('contratos/pdf/{id}', 'ContratoController@getContractPDF');
        Route::get('reservas/pdf/{id}/{idioma}/{sendMailToClient}', 'ContratoController@getReservaPDF');
        Route::get('contratos/view/pdf/{id}', 'ContratoController@viewPDF');
        Route::get('reservas/view/pdf/{id}/{idioma}', 'ContratoController@viewReservaPDF');
        Route::delete('contratos/cancel/{id}', 'ContratoController@cancelContract');
        Route::get('reservas', 'ContratoController@getReservas');
        //endregion

        //region CHECKLIST
        Route::post('check-list/save', 'CheckListController@saveUpdate');
        Route::get('check-list/{id}', 'CheckListController@show');
        Route::get('check-list/delete/{id}', 'CheckListController@remove');
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
        Route::get('vehiculos/list-estatus-contract', 'VehiculosController@listWithContract');
        Route::resource('vehiculos', 'VehiculosController');
        Route::put('vehiculos/change-status/{id}', 'VehiculosController@updateStatus');
        Route::get('vehiculos/polizas', 'VehiculosController@getListWithPolizas');

        //endregion

        //region REPORTES
        Route::get('reportes/estatus-vehiculos', 'ReportesController@getEstatusVehiculosReport');
        Route::get('reportes/mantenimiento-vehiculos', 'ReportesController@getMantenimientoVehiculosReport');
        Route::post('reportes/exedente-kilometraje-gasolina', 'ReportesController@getExedenteKilometrajeGasolinaReport');
        Route::post('reportes/polizas-seguros', 'ReportesController@getVehiculostWithPolizas');
        Route::post('reportes/detalle-pagos', 'ReportesController@detallePagos');
        Route::post('reportes/rentas-por-vehiculo', 'ReportesController@rentasPorVehiculo');
        Route::post('reportes/rentas-comisionistas', 'ReportesController@rentasPorComisionista');
        Route::post('reportes/general', 'ReportesController@reporteGeneral');
        //endregion

        //region CONTROL ACCESO
        Route::get('usuarios/all', 'UsersController@getAll');
        Route::post('usuarios/enable-disable', 'UsersController@enableDisable');
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

            //region SESION DE USUARIO
            Route::post('change-password', 'SessionController@changePwd');
            Route::post('change-pwd-token', 'SessionController@changePwdByToken');
            Route::post('activate-usr-token', 'SessionController@activateUserByCode');
            Route::post('recovery-psw', 'SessionController@generateRecoveryPswToken');
            Route::post('review-recovery-token', 'SessionController@reviewToken');
            //endregion

        //endregion

        //region HOTELES
        Route::get('hoteles/all', 'HotelesController@getAll');
        Route::get('hoteles/enable/{id}', 'HotelesController@enable');
        Route::resource('hoteles', 'HotelesController');
        //endregion

        //region TIPOSEXTERNOS
        Route::get('tipos-externos', 'TiposExternosController@index');
        //endregion

        //region COMISIONISTAS
        Route::get('comisionistas/all', 'ComisionistasController@getAll');
        Route::get('comisionistas/enable/{id}', 'ComisionistasController@enable');
        Route::resource('comisionistas', 'ComisionistasController');
        //endregion

        //region CLIENTES
        Route::get('clientes/all', 'ClientesController@getAll');
        Route::get('clientes/enable/{id}', 'ClientesController@enable');
        Route::get('clientes/list', 'ClientesController@getList');
        Route::resource('clientes', 'ClientesController');
        //endregion

        //region TARJETAS
        Route::get('tarjetas/all', 'TarjetasController@getAll');
        Route::get('tarjetas/enable/{id}', 'TarjetasController@enable');
        Route::resource('tarjetas', 'TarjetasController');
        //endregion

        //region COBRANZA
        Route::post('cobranza/cancel', 'CobranzaController@cancel');
        Route::post('cobranza/get-cobranza', 'CobranzaController@getCobranza');
        //endregion


        //region CONFIGURACIÓN APP
            Route::resource('tipos-tarifas', 'TiposTarifasController');

            //region TARIFAS EXTRAS
            Route::get('tarifas-extras/all', 'TarifasExtrasController@getAll');
            Route::get('tarifas-extras/enable/{id}', 'TarifasExtrasController@enable');
            Route::get('tarifas-extras/list', 'TarifasExtrasController@getList');
            Route::resource('tarifas-extras', 'TarifasExtrasController');
            //endregion

            //region CARGOS RETORNO EXTRAS
            Route::get('cargos-extras/all', 'CargosRetornoExtrasController@getAll');
            Route::get('cargos-extras/enable/{id}', 'CargosRetornoExtrasController@enable');
            Route::get('cargos-extras/list', 'CargosRetornoExtrasController@getList');
            Route::resource('cargos-extras', 'CargosRetornoExtrasController');
            //endregion

              //region TARIFAS APOLLO CONF
              Route::get('tarifas-apollo-conf/all', 'TarifasApolloConfController@getAll');
              Route::get('tarifas-apollo-conf/enable/{id}', 'TarifasApolloConfController@enable');
              Route::resource('tarifas-apollo-conf', 'TarifasApolloConfController');
              //endregion

              //region TARIFAS X CATEGORIA
              Route::get('tarifas-categorias/all', 'TarifasCategoriasController@getAll');
              Route::get('tarifas-categorias/enable/{id}', 'TarifasCategoriasController@enable');
              Route::resource('tarifas-categorias', 'TarifasCategoriasController');
              //endregion

              //region TIPO DE CAMBIO
              Route::post('tipo-cambio', 'ConversionMonedaController@getTipoCambio');
              Route::get('tipo-cambio/all', 'ConversionMonedaController@getAllTiposCambio');
              Route::get('tipo-cambio/all-history', 'ConversionMonedaController@getAllHistory');
              Route::post('tipo-cambio/save', 'ConversionMonedaController@save');
              Route::get('tipo-cambio/divisas', 'ConversionMonedaController@getDivisas');
              Route::delete('tipo-cambio/{id}', 'ConversionMonedaController@deleteTipoCambio');
              //endregion

        //endregion

        //region UBICACIONES
        Route::get('ubicaciones/all', 'UbicacionesController@getAll');
        Route::get('ubicaciones/enable/{id}', 'UbicacionesController@enable');
        Route::get('ubicaciones/list', 'UbicacionesController@getList');
        Route::resource('ubicaciones', 'UbicacionesController');
        //endregion

        //region NOTAS
        Route::post('notas/save', 'NotasController@saveUpdate');
        Route::get('notas/delete/{id}', 'NotasController@remove');
        //endregion

        //region POLIZAS
        Route::get('polizas/all', 'PolizasController@getAll');
        Route::get('polizas/enable/{id}', 'PolizasController@enable');
        Route::get('polizas/list', 'PolizasController@getList');
        Route::resource('polizas', 'PolizasController');
        //endregion

    });
});


