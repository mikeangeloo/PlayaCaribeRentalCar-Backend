<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Clientes;
use App\Models\Cobranza;
use App\Models\Contrato;
use App\Models\Vehiculos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
{
    public function saveProcess(Request $request) {

        $validateInit = Contrato::validateBeforeSaveProgress($request->all());
        if ($validateInit !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateInit
            ], JsonResponse::BAD_REQUEST);
        }

        $contractInitials = 'AP';
        //dd($contractInitials.sprintf('%03d', '33333'));
        $message = 'Avance guardado correctamente';
        $user = $request->user;


        $contrato = new Contrato();

        if ($request->has('num_contrato') && isset($request->num_contrato)) {
            $message = 'Avance actualizado correctamente';
            $contrato = Contrato::where('num_contrato', $request->num_contrato)->first();
        }

        DB::beginTransaction();
        switch ($request->seccion) {
            case 'datos_generales':
                $validate = Contrato::validateDatosGeneralesBeforeSave($request->all());

                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }
                $contrato->vehiculo_id = $request->vehiculo_id;
                $contrato->tipo_tarifa_id = $request->tipo_tarifa_id;
                $contrato->tipo_tarifa = $request->tipo_tarifa;
                $contrato->modelo_id = $request->modelo_id;
                $contrato->modelo = $request->modelo;

                $contrato->tarifa_modelo = $request->tarifa_modelo;
                $contrato->tarifa_modelo_id = $request->tarifa_modelo_id;
                $contrato->tarifa_apollo_id = $request->tarifa_apollo_id;
                $contrato->tarifa_modelo_label = $request->tarifa_modelo_label;
                $contrato->tarifa_modelo_precio = $request->tarifa_modelo_precio;
                $contrato->tarifa_modelo_obj = $request->tarifa_modelo_obj;

                $contrato->vehiculo_clase_id = $request->vehiculo_clase_id;
                $contrato->vehiculo_clase = $request->vehiculo_clase;
                $contrato->vehiculo_clase_precio = $request->vehiculo_clase_precio;

                $contrato->precio_unitario_inicial = $request->precio_unitario_inicial;
                $contrato->comision = $request->comision;
                $contrato->precio_unitario_final = $request->precio_unitario_final;

                $contrato->fecha_salida = $request->rango_fechas['fecha_salida'];
                $contrato->fecha_retorno = $request->rango_fechas['fecha_retorno'];

                $contrato->cobros_extras_ids = $request->cobros_extras_ids;
                $contrato->cobros_extras = $request->cobros_extras;

                $contrato->subtotal = $request->subtotal;
                $contrato->con_descuento = $request->con_descuento;
                $contrato->descuento = $request->descuento;
                $contrato->con_iva = $request->con_iva;
                $contrato->iva = $request->iva;
                $contrato->iva_monto = $request->iva_monto;
                $contrato->total = $request->total;

                $contrato->folio_cupon = $request->folio_cupon;
                $contrato->valor_cupon = $request->valor_cupon; //TODO: ya no se usara

                $contrato->cobranza_calc = $request->cobranza_calc;

                $contrato->total_dias = $request->total_dias;
                $contrato->ub_salida_id = $request->ub_salida_id;
                $contrato->ub_retorno_id = $request->ub_retorno_id;

                $contrato->user_create_id = $user->id;
                break;
            case 'datos_cliente':
                $validate = Clientes::validateBeforeSave($request->all());
                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }

                $cliente = new Clientes();
                if ($request->has('cliente_id') && isset($request->cliente_id)) {
                    $cliente = Clientes::where('id', $request->cliente_id)->first();
                }
                $cliente->nombre = $request->nombre;
                //$cliente->apellidos = $request->apellidos;
                $cliente->telefono = $request->telefono;
                $cliente->email = $request->email;
                $cliente->num_licencia = $request->num_licencia;
                $cliente->licencia_mes = $request->licencia_mes;
                $cliente->licencia_ano = $request->licencia_ano;
                $cliente->direccion = (isset($request->direccion)) ? $request->direccion : null;
                $cliente->activo = true;

                if ($cliente->save() === false) {
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Hubo un error al guardar la información, intenta de nuevo']
                    ], JsonResponse::BAD_REQUEST);
                }

                $contrato->cliente_id = $cliente->id;
                break;
            case 'datos_vehiculo':
                $validateVehiculo = Contrato::validateDatosVehiculo($request->all());
                if ($validateVehiculo !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validateVehiculo
                    ], JsonResponse::BAD_REQUEST);
                }
                $contrato->vehiculo_id = $request->vehiculo_id;
                if ($request->has('km_inicial')) {
                    $contrato->km_inicial = $request->km_inicial;
                }
                if ($request->has('km_final')) {
                    $contrato->km_final = $request->km_final;
                }
                if ($request->has('km_anterior')) {
                    $contrato->km_anterior = $request->km_anterior;
                }
                if ($request->has('cant_combustible_salida')) {
                    $contrato->cant_combustible_salida = $request->cant_combustible_salida;
                }
                if ($request->has('cant_combustible_retorno')) {
                    $contrato->cant_combustible_retorno = $request->cant_combustible_retorno;
                }
                break;
            case 'cobranza':
                $validate = Cobranza::validateBeforeSave($request->all());
                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }
                $cobranza = new Cobranza();
                if ($request->has('cobranza_id') && isset($request->cobranza_id)) {
                    $cobranza = Cobranza::where('id', $request->cobranza_id)->first();
                }

                $cobranza->contrato_id = $request->contrato_id;
                $cobranza->tarjeta_id = $request->tarjeta_id;
                $cobranza->cliente_id = $request->cliente_id;

                if(!$cobranza->fecha_cargo) {
                    $cobranza->fecha_cargo =  Carbon::now(); //TODO: por el momento en duro
                }

                $cobranza->monto = $request->monto;
                $cobranza->moneda = $request->moneda;
                $cobranza->tipo = $request->tipo;
                $cobranza->estatus = CobranzaStatusEnum::COBRADO;
                if (!$cobranza->fecha_procesado) {
                    $cobranza->fecha_procesado = Carbon::now(); //TODO: por el momento en duro
                }

                $cobranza->cod_banco = $request->cod_banco;
                $cobranza->res_banco = null; //TODO: agregar catálogo de respuestas

                if (!$cobranza->fecha_reg) {
                    $cobranza->fecha_reg = Carbon::now();
                }

                if ($cobranza->save() === false) {
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Hubo un error al guardar la información, intenta de nuevo']
                    ], JsonResponse::BAD_REQUEST);
                }
                break;
            case 'check_in_salida':
                $checkInCtrl = new CheckListController();
                $response = $checkInCtrl->saveUpdate($request);
                //dd($response);
                if($response->original['ok'] !== true) {
                    return $response;
                }
                break;
        }


        $contrato->estatus = ContratoStatusEnum::BORRADOR;
        if (!$contrato->hora_elaboracion) {
            $contrato->hora_elaboracion = Carbon::now()->toTimeString();
        }

        if (!$contrato->hora_salida) {
            $contrato->hora_salida = Carbon::now()->toTimeString();
        }

        if (!$contrato->hora_retorno) {
            $contrato->hora_retorno = Carbon::now()->toTimeString();
        }

        if ($contrato->save()) {
            DB::commit();


            if (!$contrato->num_contrato) {
                $contrato->num_contrato = $contractInitials.sprintf('%03d', $contrato->id);
                $contrato->save();
            }

            Contrato::setEtapasGuardadas($contrato->num_contrato);

            return response()->json([
                'ok' => true,
                'message' => $message,
                'id' => $contrato->id,
                'contract_number' => $contrato->num_contrato,
                'id' => $contrato->id,
            ], JsonResponse::OK);
        } else {
            DB::rollBack();
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al guardar la información, intenta de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getContract(Request $request, $num_contrato) {

        $getData = Contrato::setEtapasGuardadas($num_contrato);

        if ($getData->ok === false) {
            return response()->json($getData, JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'data' => $getData->data
        ], JsonResponse::OK);
    }
}
