<?php

namespace App\Http\Controllers;

use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\JsonMatches;

class ContratoController extends Controller
{
    public function saveProcess(Request $request) {
        $contractInitials = 'AP';
        //dd($contractInitials.sprintf('%03d', '33333'));
        $message = 'Avance guardado correctamente';
        $user = $request->user;
        $validate = Contrato::validateBeforeSave($request->all());

        if ($validate !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validate
            ], JsonResponse::BAD_REQUEST);
        }

        $contrato = new Contrato();

        if ($request->has('num_contrato')) {
            $message = 'Avance actualizado correctamente';
            $contrato = Contrato::where('num_contrato', $request->num_contrato)->first();
        }

        DB::beginTransaction();

        $contrato->renta_of_id = $request->renta_of_id;
        $contrato->renta_of_codigo = $request->renta_of_codigo;
        $contrato->renta_of_dir = $request->renta_of_dir;
        $contrato->renta_of_fecha = $request->renta_of_fecha;
        $contrato->renta_of_hora = $request->renta_of_hora;
        $contrato->retorno_of_id = $request->retorno_of_id;
        $contrato->retorno_of_codigo = $request->retorno_of_codigo;
        $contrato->retorno_of_dir = $request->retorno_of_dir;
        $contrato->retorno_of_fecha = $request->retorno_of_fecha;
        $contrato->retorno_of_hora = $request->retorno_of_hora;
        $contrato->user_create_id = $user->id;
        $contrato->estatus = ContratoStatusEnum::BORRADOR;

        if ($contrato->save()) {
            DB::commit();
            Contrato::setEtapasGuardadas($contrato->id);

            $contrato->num_contrato = $contractInitials.sprintf('%03d', $contrato->id);
            $contrato->save();

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
