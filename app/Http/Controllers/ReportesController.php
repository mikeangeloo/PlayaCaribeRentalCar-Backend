<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\CobranzaTipoEnum;
use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Enums\VehiculoStatusEnum;
use App\Models\Cobranza;
use App\Models\Comisionistas;
use App\Models\Contrato;
use App\Models\TiposCambio;
use App\Models\User;
use App\Models\Vehiculos;
use GrahamCampbell\ResultType\Success;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        $vehiculos = Vehiculos::select('id','modelo','placas','km_recorridos','fecha_prox_servicio','prox_km_servicio','estatus')->where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    public function getExedenteKilometrajeGasolinaReport(Request $request) {
        $fechaInicio = null;
        $fechaFin = null;

        $contratosQ = Contrato::with([
            'vehiculo'
            ,'usuario'
            ])->whereHas('vehiculo')
            ->whereIn('estatus', [0,2,3])->orderBy('id', 'ASC');

        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratosQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratosQ->whereDate('created_at', '<=', $fechaFin);
            }
        }

        if ($request->has('vehiculoId') && $request->vehiculoId > 0) {
            $contratosQ->whereHas('vehiculo', function(Builder $query) use($request) {
                $query->where('id', $request->vehiculoId);
            });
        }

        if ($request->has('num_contrato') && isset($request->num_contrato)) {
            $contratosQ->where('num_contrato', $request->num_contrato)->orWhere('num_reserva', $request->num_contrato);
        }



        $contratos = $contratosQ->get();

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
        $fechaInicio = null;
        $fechaFin = null;

        $vehiculosQ = Vehiculos::where('activo', true)->WhereNotNull('poliza_id')->orderBy('id', 'ASC');

        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $vehiculosQ->whereHas('poliza', function(Builder $query) use($fechaInicio) {
                    $query->where('created_at', '>=', $fechaInicio);
                });
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $vehiculosQ->whereHas('poliza', function(Builder $query) use($fechaFin) {
                    $query->where('created_at', '<=', $fechaFin);
                });
            }
        }

        $vehiculos = $vehiculosQ->get();
        $vehiculos->load('poliza');

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos,
        ], JsonResponse::OK);
    }

    public function detallePagos(Request $request) {
        $fechaInicio = null;
        $fechaFin = null;

        $contratosQ = Contrato::select('id', 'created_at', 'num_contrato', 'num_reserva', 'folio_cupon', 'valor_cupon', 'ub_salida_id', 'ub_retorno_id', 'vehiculo_id', 'user_create_id', 'user_close_id', 'total as total_salida', 'total_retorno')
                     ->with(['salida:id,alias','retorno:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre', 'usuario_close:id,username,nombre', 'cobranza.cobro_depositos',
                     'cobranza.tarjeta'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     //->where('id', 26)
                     ->orderBy('created_at', 'DESC');

        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratosQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratosQ->whereDate('created_at', '<=', $fechaFin);
            }
        }

        //$contratos = $contratosQ->get();

        $contratos = $contratosQ->withCount(
            [
                'cobranza as cobranza_tarjeta_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGOTARJETA)->where('moneda_cobrada', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_tarjeta_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGOTARJETA)->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_efectivo_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGOEFECTIVO)->where('moneda_cobrada', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_efectivo_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGOEFECTIVO)->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_pre_auth_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PREAUTORIZACION)->where('moneda_cobrada', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_pre_auth_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PREAUTORIZACION)->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_deposito_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGODEPOSITO)->where('moneda_cobrada', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as cobranza_deposito_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('tipo', CobranzaTipoEnum::PAGODEPOSITO)->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO);
                },
                'cobranza as total_cobrado_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('moneda', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO)->where('tipo', '!=', CobranzaTipoEnum::PREAUTORIZACION);
                },
                'cobranza as total_cobrado_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO)->where('tipo', '!=', CobranzaTipoEnum::PREAUTORIZACION);
                }
            ]
        )->get();

        $totalCobrado = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->total_final = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_final;

            $collection = collect($contratos[$i]->cobranza);

            $contratos[$i]->cobranza_tarjeta_mxn = [
                'total' => $contratos[$i]->cobranza_tarjeta_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOTARJETA && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_tarjeta_usd = [
                'total' => $contratos[$i]->cobranza_tarjeta_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOTARJETA && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_efectivo_mxn = [
                'total' => $contratos[$i]->cobranza_efectivo_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOEFECTIVO && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_efectivo_usd = [
                'total' => $contratos[$i]->cobranza_efectivo_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOEFECTIVO && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_pre_auth_mxn = [
                'total' => $contratos[$i]->cobranza_pre_auth_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PREAUTORIZACION && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_pre_auth_usd = [
                'total' => $contratos[$i]->cobranza_pre_auth_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PREAUTORIZACION && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_deposito_mxn = [
                'total' => $contratos[$i]->cobranza_deposito_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGODEPOSITO && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_deposito_usd = [
                'total' => $contratos[$i]->cobranza_deposito_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGODEPOSITO && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];
        }

        return response()->json([
            'ok' => true,
            'total_cobrado' => $totalCobrado,
            'data' => $contratos
        ], JsonResponse::OK);
    }

    public function rentasPorVehiculo(Request $request) {
        $fechaInicio = null;
        $fechaFin = null;

        $rangoFechas = 'Historico';
        if ($request->has('rango_fechas')) {
            $rangoFechas = $request->rango_fechas;
        }
        $contratoQ =  Contrato::select('id', 'created_at', 'num_contrato', 'ub_salida_id', 'vehiculo_id', 'user_create_id', 'user_close_id', 'total as total_salida', 'total_retorno')
                     ->with(['salida:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre', 'usuario_close:id,username,nombre'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     ->orderBy('created_at', 'DESC');

        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratoQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratoQ->whereDate('created_at', '<=', $fechaFin);
            }
        }

        if ($request->has('vehiculoId') && $request->vehiculoId > 0) {
            $contratoQ->whereHas('vehiculo', function(Builder $query) use($request) {
                $query->where('id', $request->vehiculoId);
            });
        }

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
        $comisionistaSearch = null;

        if ($request->has('search_users') && isset($request->search_users)) {
            $userSearch = array_values(array_filter($request->search_users, function($item) {
                if ($item['tipo'] === 'usuarios') {
                    return $item;
                }
            }))[0] ?? null;

            $comisionistaSearch = array_values(array_filter($request->search_users, function($item) {
                if ($item['tipo'] === 'comisionistas') {
                    return $item;
                }
            }))[0] ?? null;
        }

        $contratoQ =  Contrato::select('id', 'created_at', 'fecha_retorno', 'num_contrato', 'ub_salida_id', 'vehiculo_id', 'total_dias', 'user_create_id', 'user_close_id', 'cliente_id', 'total as total_salida', 'total_retorno', 'modelo_id', 'comision')
                     ->with(['salida:id,alias','vehiculo:id,modelo,modelo_ano,placas,color',  'usuario:id,username,nombre,apellidos','usuario_close:id,username,nombre', 'cliente:id,nombre', 'comisionista:id,nombre,apellidos,comisiones_pactadas'])
                     ->where('estatus', ContratoStatusEnum::CERRADO)
                     ->orderBy('created_at', 'DESC');

        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratoQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratoQ->whereDate('created_at', '<=', $fechaFin);
            }
        }

        if ($comisionistaSearch && count($comisionistaSearch) > 0) {
            if ($comisionistaSearch['tipo'] === "comisionistas") {
                $contratoQ->where('modelo_id', $comisionistaSearch['user_id']);
            }
        }

        if ($userSearch && count($userSearch) > 0) {
            if ($userSearch['tipo'] === "usuarios") {
                $contratoQ->where('user_create_id', $userSearch['user_id']);
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

        $totalCobrado = 0;
        $totalComisiones = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->total_cobrado = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_cobrado;
            $totalComisiones += $contratos[$i]->comision;
        }


        return response()->json([
            'ok' => true,
            'usuarios_sistema' => isset($usersCollect[0]) ? $usersCollect[0] : [],
            'comisionistas' => isset($comisionistasCollect[0]) ? $comisionistasCollect[0] : [],
            'total_cobrado' => $totalCobrado,
            'total_comisiones' => $totalComisiones,
            'data' => $contratos
        ], JsonResponse::OK);
    }

    public function reporteGeneral(Request $request) {
        $fechaInicio = null;
        $fechaFin = null;

        $contratosQ = Contrato::select(
                        'id', 'created_at', 'fecha_salida', 'hora_salida', 'fecha_retorno', 'folio_cupon', 'valor_cupon', 'hora_retorno', 'num_contrato', 'num_reserva', 'ub_salida_id', 'ub_retorno_id', 'vehiculo_id',
                        'user_create_id', 'user_close_id','total as total_salida', 'total_retorno', 'cliente_id', 'km_inicial', 'km_final', 'cant_combustible_salida', 'cant_combustible_retorno','estatus'
                      )
                     ->with(
                        [
                            'salida:id,alias',
                            'retorno:id,alias',
                            'vehiculo:id,modelo,modelo_ano,placas,color',
                            'cliente:id,nombre,telefono,num_licencia,licencia_mes,licencia_ano,email,direccion',
                            'usuario:id,username,nombre',
                            'usuario_close:id,username,nombre',
                            'cobranza.cobro_depositos',
                            'cobranza.tarjeta'
                        ])
                     ->orderBy('created_at', 'DESC');

        if($request->has('estatus') && isset($request->estatus)) {
            $contratosQ->where('estatus', $request->estatus);
        }


        if ($request->has('rango_fechas') && isset($request->rango_fechas)) {
            if ($request->rango_fechas['start'] && $request->rango_fechas['start'] !== 'Invalid date') {
                $fechaInicio = $request->rango_fechas['start'];

                $contratosQ->whereDate('created_at', '>=', $fechaInicio);
            }

            if ($request->rango_fechas['end'] && $request->rango_fechas['end'] !== 'Invalid date') {
                $fechaFin = $request->rango_fechas['end'];
                $contratosQ->whereDate('created_at', '<=', $fechaFin);
            }
        }

        if ($request->has('status') && isset($request->status) && count($request->status) > 0) {
            $contratosQ->whereIn('estatus', $request->status);
        }

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
                },
                'cobranza as total_cobrado_mxn' => function($query) {
                    $query->select(DB::raw("SUM(monto) as total"))->where('moneda', 'MXN')->where('estatus', CobranzaStatusEnum::COBRADO)->where('tipo', '!=', CobranzaTipoEnum::PREAUTORIZACION);
                },
                'cobranza as total_cobrado_usd' => function($query) {
                    $query->select(DB::raw("SUM(monto_cobrado) as total"))->where('moneda_cobrada', 'USD')->where('estatus', CobranzaStatusEnum::COBRADO)->where('tipo', '!=', CobranzaTipoEnum::PREAUTORIZACION);
                }
            ]
        )->get();

        $totalRentados = 0;
        $totalCerrados = 0;
        $totalCancelados = 0;
        $totalReservados = 0;
        $totalBorradores = 0;
        $totalCobrado = 0;

        for($i = 0; $i < count($contratos); $i++) {
            $contratos[$i]->total_final = $contratos[$i]->total_salida + $contratos[$i]->total_retorno;
            $totalCobrado += $contratos[$i]->total_final;

            if ($contratos[$i]->estatus === ContratoStatusEnum::RENTADO) {
                $totalRentados += $contratos[$i]->total_final;
            }

            if ($contratos[$i]->estatus === ContratoStatusEnum::CERRADO) {
                $totalCerrados += $contratos[$i]->total_final;
            }

            if ($contratos[$i]->estatus === ContratoStatusEnum::CANCELADO) {
                $totalCancelados += $contratos[$i]->total_final;
            }

            if ($contratos[$i]->estatus === ContratoStatusEnum::RESERVA) {
                $totalReservados += $contratos[$i]->total_final;
            }

            if ($contratos[$i]->estatus === ContratoStatusEnum::BORRADOR) {
                $totalBorradores += $contratos[$i]->total_final;
            }

            $collection = collect($contratos[$i]->cobranza);

            $contratos[$i]->cobranza_tarjeta_mxn = [
                'total' => $contratos[$i]->cobranza_tarjeta_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOTARJETA && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_tarjeta_usd = [
                'total' => $contratos[$i]->cobranza_tarjeta_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOTARJETA && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_efectivo_mxn = [
                'total' => $contratos[$i]->cobranza_efectivo_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOEFECTIVO && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_efectivo_usd = [
                'total' => $contratos[$i]->cobranza_efectivo_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGOEFECTIVO && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_pre_auth_mxn = [
                'total' => $contratos[$i]->cobranza_pre_auth_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PREAUTORIZACION && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_pre_auth_usd = [
                'total' => $contratos[$i]->cobranza_pre_auth_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PREAUTORIZACION && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];

            $contratos[$i]->cobranza_deposito_mxn = [
                'total' => $contratos[$i]->cobranza_deposito_mxn,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGODEPOSITO && $item->moneda_cobrada === 'MXN';
                })->flatten()
            ];

            $contratos[$i]->cobranza_deposito_usd = [
                'total' => $contratos[$i]->cobranza_deposito_usd,
                'data' => $collection->filter(function ($item) {
                    return $item->tipo === CobranzaTipoEnum::PAGODEPOSITO && $item->moneda_cobrada === 'USD';
                })->flatten()
            ];
        }

        return response()->json([
            'ok' => true,
            'total_cobrado' => $totalCobrado,
            'total_rentados' => $totalRentados,
            'total_cerrados' => $totalCerrados,
            'total_cancelados' => $totalCancelados,
            'total_reservados' => $totalReservados,
            'total_borradores' => $totalBorradores,
            'data' => $contratos
        ], JsonResponse::OK);
    }

    public function dashboardInfo(Request $request) {
        $activeUsers = User::where('activo', true)->count();
        $activeComisionistas = Comisionistas::where('activo', true)->count();
        $vehiculosDisponibles = Vehiculos::where('estatus', VehiculoStatusEnum::DISPONIBLE)->count();
        $vehiculosTaller = Vehiculos::where('estatus', VehiculoStatusEnum::ENTALLER)->count();
        $vehiculosCorralon = Vehiculos::where('estatus', VehiculoStatusEnum::CORRALON)->count();

        return response()->json([
            'ok' => true,
            'activeUsers' => $activeUsers,
            'activeComisionistas' => $activeComisionistas,
            'vehiculosDisponibles' => $vehiculosDisponibles,
            'vehiculosTaller' => $vehiculosTaller,
            'vehiculosCorralon' => $vehiculosCorralon
        ], JsonResponse::OK);
    }
}
