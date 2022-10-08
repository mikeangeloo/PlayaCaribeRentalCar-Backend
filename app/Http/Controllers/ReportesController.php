<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\CobranzaTipoEnum;
use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Contrato;
use App\Models\Vehiculos;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function getEstatusVehiculosReport(Request $request) {
        $vehiculos = Vehiculos::select('id','modelo','placas','estatus')->where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    public function getMantenimientoVehiculosReport(Request $request) {
        $vehiculos = Vehiculos::select('id','modelo','placas','km_recorridos','prox_servicio','estatus')->where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    public function getExedenteKilometrajeGasolinaReport(Request $request) {
        $contratos = Contrato::with([
            'vehiculo'
            ,'usuario'
            ])->whereHas('vehiculo')
            ->whereIn('estatus', [0,2,3])->orderBy('id', 'ASC')->get();

        for ($i = 0; $i < count($contratos); $i++) {
            if (is_null($contratos[$i]->vehiculo->km_final)) {
                $contratos[$i]->vehiculo->km_final = $contratos[$i]->vehiculo->km_recorridos;
            }
            if (is_null($contratos[$i]->km_final)) {
                $contratos[$i]->km_final = $contratos[$i]->km_inicial;
            }
            if(is_null($contratos[$i]->cant_combustible_retorno)) {
                $contratos[$i]->cant_combustible_retorno = $contratos[$i]->cant_combustible_salida;
            }
        }

        return response()->json([
            'ok' => true,
            'contratos' => $contratos
        ], JsonResponse::OK);
    }

    public function getVehiculostWithPolizas(Request $request) {
        $vehiculos = Vehiculos::where('activo', true)->WhereNotNull('poliza_id')->orderBy('id', 'ASC')->get();
        $vehiculos->load('poliza');


        // $_vehiculos = [];

        // for ($i = 0; $i < count($vehiculos); $i++) {
        //     if(isset($vehiculos[$i]->poliza)) {
        //         array_push($_vehiculos, [
        //             'id' => $vehiculos[$i]->id,
        //             'nombre' => $vehiculos[$i]->modelo,
        //             'placas' => $vehiculos[$i]->placas,
        //             'aseguradora' => $vehiculos[$i]->poliza->aseguradora,
        //             'no_poliza' => $vehiculos[$i]->poliza->no_poliza,
        //             'tipo_poliza' => $vehiculos[$i]->poliza->tipo_poliza,
        //             'tel_contacto' => $vehiculos[$i]->poliza->tel_contacto,
        //             'titular' => $vehiculos[$i]->poliza->titular,
        //             'desde' => $vehiculos[$i]->poliza->fecha_inicio,
        //             'hasta' => $vehiculos[$i]->poliza->fecha_fin,
        //         ]);
        //     }

        // }

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos,
        ], JsonResponse::OK);
    }

    public function detallePagos(Request $request) {
        $contratosQ = Contrato::select('id', 'created_at', 'num_contrato', 'ub_salida_id', 'vehiculo_id', 'user_create_id', 'user_close_id', 'total as total_salida', 'total_retorno')
                     ->with(['salida:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre', 'usuario_close:id,username,nombre'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     ->orderBy('created_at', 'DESC');
        $contratos = $contratosQ->withCount(
            [
                'cobranza as cobranza_tarjeta_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGOTARJETA)->where('moneda', 'MXN');
                },
                'cobranza as cobranza_tarjeta_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGOTARJETA)->where('moneda', 'USD');
                },
                'cobranza as cobranza_efectivo_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGOEFECTIVO)->where('moneda', 'MXN');
                },
                'cobranza as cobranza_efectivo_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGOEFECTIVO)->where('moneda', 'USD');
                },
                'cobranza as cobranza_pre_auth_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PREAUTORIZACION)->where('moneda', 'MXN');
                },
                'cobranza as cobranza_pre_auth_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PREAUTORIZACION)->where('moneda', 'USD');
                },
                'cobranza as cobranza_deposito_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGODEPOSITO)->where('moneda', 'MXN');
                },
                'cobranza as cobranza_deposito_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('tipo', CobranzaTipoEnum::PAGODEPOSITO)->where('moneda', 'USD');
                }
            ]
        )->get();

        $totalCobrado = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->total_final = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_final;
        }

        return response()->json([
            'ok' => true,
            'total_cobrado' => $totalCobrado,
            'data' => $contratos
        ], JsonResponse::OK);
    }

    public function _rentasPorVehiculo(Request $request) {
        $rangoFechas = 'Historico';
        if ($request->has('rango_fechas')) {
            $rangoFechas = $request->rango_fechas;
        }
        $vehiculosQ = Vehiculos::select('id','modelo','modelo_ano','placas','color')
        ->with(
            [
                'contratos' => function ($query) {
                    $query->where('estatus', ContratoStatusEnum::CERRADO)->select('id','created_at','num_contrato','vehiculo_id','estatus','total','total_retorno', 'ub_salida_id');
                },
                'contratos.salida:id,alias'
            ]
        );
        $vehiculos = $vehiculosQ
        ->withCount(
            [
                'contratos as total_cobrado' => function($query) {
                    $query->select(DB::raw("COALESCE(SUM(total), 0) + COALESCE(SUM(total_retorno), 0) as total_sales"))->groupBy('vehiculo_id');
                }
            ]
        )->get();

        $totalCobrado = 0;

        for($i = 0; $i < count($vehiculos); $i++) {
            $vehiculos[$i]->rango_fechas = $rangoFechas;
            $totalCobrado += $vehiculos[$i]->total_cobrado;
        }


        return response()->json([
            'ok' => true,
            'total_cobrado' => $totalCobrado,
            'data' => $vehiculos
        ], JsonResponse::OK);
    }

    public function rentasPorVehiculo(Request $request) {
        $rangoFechas = 'Historico';
        if ($request->has('rango_fechas')) {
            $rangoFechas = $request->rango_fechas;
        }
        $contratoQ =  Contrato::select('id', 'created_at', 'num_contrato', 'ub_salida_id', 'vehiculo_id', 'user_create_id', 'user_close_id', 'total as total_salida', 'total_retorno')
                     ->with(['salida:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre', 'usuario_close:id,username,nombre'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     ->orderBy('created_at', 'DESC');
        $contratos = $contratoQ->get();

        $totalCobrado = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->rango_fechas = $rangoFechas;
            $contratos[$i]->total_cobrado = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_cobrado;
        }


        return response()->json([
            'ok' => true,
            'total_cobrado' => $totalCobrado,
            'data' => $contratos
        ], JsonResponse::OK);
    }

    public function rentasPorComisionista(Request $request) {
        $fechaInicio = null;
        $fechaFin = null;
        $userSearch = null;


        $contratoQ =  Contrato::select('id', 'created_at', 'fecha_retorno', 'num_contrato', 'ub_salida_id', 'vehiculo_id', 'total_dias', 'user_create_id', 'user_close_id', 'cliente_id', 'total as total_salida', 'total_retorno', 'modelo_id')
                     ->with(['salida:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre', 'usuario_close:id,username,nombre', 'cliente:id,nombre', 'comisionista:id,nombre,apellidos,comisiones_pactadas'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     ->orderBy('created_at', 'DESC');

        if ($request->has('rango_fechas')) {
            if ($request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratoQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratoQ->where('created_at', '>=', $fechaFin);
            }
        }
        if ($request->has('usuario_data') && $request->usuario_data !== null) {
            if ($request->usuario_data['tipo'] === 'usuarios') {
                $contratoQ->usuario()->where('id', $request->usuario_data['user_id']);
            }

            if ($request->usuario_data['tipo'] === '"comisionistas"') {
                $contratoQ->comisionista()->where('id', $request->usuario_data['user_id']);
            }
        }

        $contratos = $contratoQ->get();
        //dd($contratos);

        $collection = collect($contratos);

        $usersCollect = $collection->filter(function ($item) {
            return $item->usuario != null;
        })
        ->unique(function ($item) {
            return $item->usuario->id;
        })->mapToGroups(function($item) {
            return [$item->usuario];
        });

        $comisionistasCollect = $collection->filter(function ($item) {
            return $item->comisionista != null;
        })
        ->unique(function ($item) {
            return $item->comisionista->id;
        })->mapToGroups(function($item) {
            return [$item->comisionista];
        });

        // return response()->json([
        //     'ok' => true,
        //     'data' => $comisionistasCollect
        // ], JsonResponse::OK);

        $totalCobrado = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->total_cobrado = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_cobrado;
        }


        return response()->json([
            'ok' => true,
            'usuarios_sistema' => $usersCollect[0],
            'comisionistas' => $comisionistasCollect[0],
            'total_cobrado' => $totalCobrado,
            'data' => $contratos
        ], JsonResponse::OK);
    }
}
