<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Enums\VehiculoStatusEnum;
use App\Models\Clientes;
use App\Models\Cobranza;
use App\Models\Contrato;
use App\Models\Vehiculos;
use App\Models\CheckFormList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Helpers\GenerateUniqueAlphCodesHelper;
use Illuminate\Support\Facades\Log;

class ContratoController extends Controller
{
    public function saveProcess(Request $request) {

        //dd($request);

        $validateInit = Contrato::validateBeforeSaveProgress($request->all());
        if ($validateInit !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateInit
            ], JsonResponse::BAD_REQUEST);
        }

        $contractInitials = ($request->reserva) ? 'RS': 'AP';
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
                $cliente->num_cliente = GenerateUniqueAlphCodesHelper::random_strings(6);

                if ($cliente->save() === false) {
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Hubo un error al guardar la información, intenta de nuevo']
                    ], JsonResponse::BAD_REQUEST);
                }

                $contrato->cliente_id = $cliente->id;
                $contrato->estatus = ContratoStatusEnum::BORRADOR;
                break;
            case 'datos_generales':
                $validate = Contrato::validateDatosGeneralesBeforeSave($request->all());

                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }
                //$contrato->vehiculo_id = $request->vehiculo_id;
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
                $cobranza->cobranza_seccion = $request->cobranza_seccion;
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

                // si viene reserva: true se cambia status a reserva
                if ($request->reserva) {
                    $contrato->estatus = 4;
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
            case 'check_form_list':
                $validate = CheckFormList::validateBeforeSave($request->all());

                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }

                $checkFormList = new CheckFormList();

                if ($request->has('check_form_list_id') && isset($request->check_form_list_id)) {
                    $checkFormList = CheckFormList::where('id', $request->check_form_list_id)->first();
                }

                $checkFormList->contrato_id = $request->contrato_id;
                $checkFormList->tarjeta_circulacion  = $request->tarjeta_circulacion;
                $checkFormList->tapetes  = $request->tapetes;
                $checkFormList->silla_bebes = $request->silla_bebes;
                $checkFormList->espejos = $request->espejos;
                $checkFormList->tapones_rueda = $request->tapones_rueda;
                $checkFormList->tapon_gas = $request->tapon_gas;
                $checkFormList->senalamientos = $request->senalamientos;
                $checkFormList->gato = $request->gato;
                $checkFormList->llave_rueda = $request->llave_rueda;
                $checkFormList->limpiadores = $request->limpiadores;
                $checkFormList->antena = $request->antena;
                $checkFormList->navegador = $request->navegador;
                $checkFormList->placas = $request->placas;
                $checkFormList->radio = $request->radio;
                $checkFormList->llantas = $request->llantas;
                $checkFormList->observaciones = $request->observaciones;
                $checkFormList->check_list_img = $request->check_list_img;


                if ($checkFormList->save() === false) {
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Algo salio mal al guardar la inforamción, intente de nuevo']
                    ], JsonResponse::BAD_REQUEST);
                }

                $contrato->check_form_list_id = $checkFormList->id;

                break;
            case 'firma':
                $contrato->firma_cliente = $request->signature_img;
                $contrato->firma_matrix = json_encode($request->signature_matrix);
                $contrato->estatus = ContratoStatusEnum::RENTADO;
                $contrato->vehiculo()->update(['estatus' => VehiculoStatusEnum::RENTADO]);
                break;
            case 'retorno':
                $validate = Contrato::validateDatosReronoBeforeSave($request->all());

                if ($validate !== true) {
                    return response()->json([
                        'ok' => false,
                        'errors' => $validate
                    ], JsonResponse::BAD_REQUEST);
                }

                $contrato->km_final = $request->km_final;
                $contrato->cant_combustible_retorno = $request->cant_combustible_retorno;
                $contrato->cargos_retorno_extras_ids = $request->cargos_extras_retorno_ids;
                $contrato->cargos_retorno_extras = $request->cargos_extras_retorno;

                $contrato->frecuencia_extra = $request->frecuencia_extra;
                $contrato->cobranzaExtraPor = $request->cobranzaExtraPor;

                $contrato->subtotal_retorno = $request->subtotal_retorno;
                $contrato->con_iva_retorno = $request->con_iva_retorno;
                $contrato->iva_retorno = $request->iva_retorno;
                $contrato->iva_monto_retorno = $request->iva_monto_retorno;
                $contrato->total_retorno = $request->total_retorno;
                $contrato->cobranza_calc_retorno = $request->cobranza_calc_retorno;
                break;
            case 'cobranza_retorno':
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
                $cobranza->cobranza_seccion = $request->cobranza_seccion;
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
                $contrato->estatus = ContratoStatusEnum::RETORNO;
             break;
        }



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
                $contrato->confirmacion = GenerateUniqueAlphCodesHelper::random_strings(13);
                $contrato->res_local = GenerateUniqueAlphCodesHelper::random_strings(6);
                $contrato->save();
            }

            //Revisamos si es una reserva que ya esta en estatus rentado
           if ($contractInitials === 'AP' && $contrato->estatus === ContratoStatusEnum::RENTADO) {
                $contrato->num_reserva = $contrato->num_contrato;
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

    public function getContractPDF(Request $request, $id) {

        try {
            $getContract = Contrato::with(
            'cliente'
            ,'cliente.cliente_docs'
            ,'vehiculo'
            ,'vehiculo.tarifas'
            ,'vehiculo.marca'
            ,'vehiculo.categoria'
            ,'vehiculo.clase'
            ,'vehiculo.tarifa_categoria'
            ,'salida'
            ,'retorno'
            ,'cobranza_reserva'
            ,'cobranza_salida'
            ,'cobranza_salida.tarjeta'
            ,'cobranza_retorno'
            ,'cobranza_retorno.tarjeta'
            ,'usuario',
            'check_form_list'
            )->where('id', $id)->first();

            // return response()->json([
            //     'ok' => true,
            //     'data' => $getContract
            // ], JsonResponse::OK);
            // dd($getContract );
            $data = [
                'contrato'=>  $getContract
            ];
            $pdf = PDF::loadView('pdfs.contract-pdf', $data)->setPaper('a4','portrait');


            $sendMail = Mail::send('mails.mail-pdf',$data, function ($mail) use ($pdf, $getContract) {
                $mail->from('apolloDev@mail.mx','Apollo');
                $mail->subject('Contrato de arrendamiento');
                $mail->to('danywolfslife@gmail.com');
                $mail->attachData($pdf->output(), 'APOLLO_Contrato_'.$getContract->num_contrato.'.pdf');
            });
        } catch(\Throwable $e) {
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al generar el pdf del contrato, intenta de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }
        // dd($sendMail);
        return $pdf->download();
    }

    public function getReservaPDF(Request $request, $id) {

        try {
            $getContract = Contrato::with(
                'cliente'
                ,'cliente.cliente_docs'
                ,'salida'
                ,'retorno'
                ,'cobranza_reserva'
                ,'cobranza_reserva.tarjeta'
                ,'usuario',
                )->where('id', $id)->first();

            // return response()->json([
            //     'ok' => true,
            //     'data' => $getContract
            // ], JsonResponse::OK);
            // dd($getContract );
            $data = [
                'contrato'=>  $getContract
            ];
            $pdf = PDF::loadView('pdfs.reserva-pdf', $data)->setPaper('a4','portrait');


            $sendMail = Mail::send('mails.mail-pdf',$data, function ($mail) use ($pdf, $getContract) {
                $mail->from('apolloDev@mail.mx','Apollo');
                $mail->subject('Reserva de arrendamiento');
                $mail->to('danywolfslife@gmail.com');
                $mail->attachData($pdf->output(), 'APOLLO_Reserva_'.$getContract->num_contrato.'.pdf');
            });
        } catch(\Throwable $e) {
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al generar el pdf del contrato, intenta de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }
        // dd($sendMail);
        return $pdf->download();
    }

    public function getReservas(Request $request) {
        $reservas = Contrato::where('estatus', 4)->orderBy('id', 'ASC')->get();
        $reservas->load('cliente'
        ,'cliente.cliente_docs'
        ,'salida'
        ,'retorno'
        ,'cobranza_reserva'
        ,'cobranza_reserva.tarjeta'
        ,'usuario');

        return response()->json([
            'ok' => true,
            'reservas' => $reservas
        ], JsonResponse::OK);
    }

    public function viewPDF(Request $request, $id) {

        try {
            $getContract = Contrato::with(
            'cliente'
            ,'cliente.cliente_docs'
            ,'vehiculo'
            ,'vehiculo.tarifas'
            ,'vehiculo.marca'
            ,'vehiculo.categoria'
            ,'vehiculo.clase'
            ,'vehiculo.tarifa_categoria'
            ,'salida'
            ,'retorno'
            ,'cobranza_salida'
            ,'cobranza_salida.tarjeta'
            ,'cobranza_retorno'
            ,'cobranza_retorno.tarjeta'
            ,'usuario',
            'check_form_list'
            )->where('id', $id)->first();

            // return response()->json([
            //     'ok' => true,
            //     'data' => $getContract
            // ], JsonResponse::OK);
            // dd($getContract );
            $data = [
                'contrato'=>  $getContract
            ];
            $pdf = PDF::loadView('pdfs.contract-pdf', $data)->setPaper('a4','portrait');

        } catch(\Throwable $e) {
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al generar el pdf del contrato, intenta de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }
        // dd($sendMail);
        return $pdf->download();
    }

    public function viewReservaPDF(Request $request, $id) {

        try {
            $getContract = Contrato::with(
            'cliente'
            ,'cliente.cliente_docs'
            ,'salida'
            ,'retorno'
            ,'cobranza_reserva'
            ,'cobranza_reserva.tarjeta'
            ,'usuario',
            )->where('id', $id)->first();

            // return response()->json([
            //     'ok' => true,
            //     'data' => $getContract
            // ], JsonResponse::OK);
            // dd($getContract );
            $data = [
                'contrato'=>  $getContract
            ];
            $pdf = PDF::loadView('pdfs.reserva-pdf', $data)->setPaper('a4','portrait');

        } catch(\Throwable $e) {
            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al generar el pdf del contrato, intenta de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }
        // dd($sendMail);
        return $pdf->download();
    }

    public function cancelContract(Request $request, $id) {
        //$validStatus = [ContratoStatusEnum::BORRADOR];
        $getContract = Contrato::where('id', $id)->first();
        $msg = 'Contrato';

        if(!$getContract) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        if (substr($getContract->num_contrato, 0 ,2) === 'RS') {
            $msg = 'Reserva';
        }
        try {
            if($getContract->cobranza() != null){
                $getContract->cobranza()->update(['estatus' => CobranzaStatusEnum::CANCELADO]);
            }
            if($getContract->vehiculo() != null){
                $getContract->vehiculo()->update(['estatus'=> VehiculoStatusEnum::DISPONIBLE]);
            }
            if($getContract->check_list_salida() != null){
                $getContract->check_list_salida()->update(['activo' => false]);
            }

            $getContract->estatus = ContratoStatusEnum::CANCELADO;
            if ($getContract->save()) {
                return response()->json([
                    'ok' => true,
                    'message' => ''.$msg.' cancelado correctamente'
                ], JsonResponse::OK);
            }
        } catch (\Throwable $e) {
            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Este '.$msg.' no puede ser cancelado']
            ], JsonResponse::BAD_REQUEST);
        }

    }
}
