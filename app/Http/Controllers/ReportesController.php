<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Contrato;
use App\Models\Vehiculos;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

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

}
