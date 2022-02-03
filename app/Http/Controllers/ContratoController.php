<?php

namespace App\Http\Controllers;

use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Clientes;
use App\Models\Contrato;
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

                $contrato->tarifa_modelo_id = $request->tarifa_modelo_id;
                $contrato->tarifa_modelo = $request->tarifa_modelo;
                $contrato->vehiculo_clase_id = $request->vehiculo_clase_id;
                $contrato->vehiculo_clase = $request->vehiculo_clase;
                $contrato->vehiculo_clase_precio = $request->vehiculo_clase_precio;
                $contrato->comision = $request->comision;

                $contrato->precio_unitario_inicial = $request->precio_unitario_inicial;
                $contrato->precio_unitario_final = $request->precio_unitario_final;
                $contrato->total_dias = $request->total_dias;
                $contrato->ub_salida_id = $request->ub_salida_id;
                $contrato->ub_retorno_id = $request->ub_retorno_id;
                $contrato->hora_elaboracion = $request->hora_elaboracion;

                $contrato->fecha_salida = $request->rango_fechas['fecha_salida'];
                $contrato->fecha_retorno = $request->rango_fechas['fecha_retorno'];

                $contrato->cobros_extras = $request->cobros_extras;
                $contrato->cobros_extras_ids = $request->cobros_extras_ids;
                $contrato->subtotal = $request->subtotal;
                $contrato->descuento = $request->descuento;
                $contrato->con_iva = $request->con_iva;
                $contrato->iva = $request->iva;
                $contrato->iva_monto = $request->iva_monto;
                $contrato->total = $request->total;

                $contrato->folio_cupon = $request->folio_cupon;
                $contrato->valor_cupon = $request->valor_cupon;

                $contrato->cobranza_calc = $request->cobranza_calc;

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
                $cliente->apellidos = $request->apellidos;
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

        }

        $contrato->estatus = ContratoStatusEnum::BORRADOR;

        if ($contrato->save()) {
            DB::commit();


            $contrato->num_contrato = $contractInitials.sprintf('%03d', $contrato->id);
            $contrato->save();

            Contrato::setEtapasGuardadas($contrato->num_contrato);

            return response()->json([
                'ok' => true,
                'message' => $message,
                'contract_number' => $contrato->num_contrato,
                'id' => $contrato->id
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
