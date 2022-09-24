<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Cobranza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CobranzaController extends Controller
{

    public function getCobranza(Request $request) {
        $validate = Validator::make($request->all(), [
            'contrato_id' => 'required|exists:contratos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|numeric',
            'estatus' => 'required|numeric'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validate->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        $cobroQuery = Cobranza::with(['tarjeta'])
                    ->where('contrato_id', $request->contrato_id)
                    ->where('cliente_id', $request->cliente_id)
                    ->where('tipo', 1)
                    ->where('estatus', $request->estatus);

        if ($request->has('cobranza_seccion')) {
            $cobroQuery->where('cobranza_seccion', $request->cobranza_seccion);
        }

        $cobro = $cobroQuery->get();

        $total = 0;
        for($i = 0; $i < count($cobro); $i++) {
            $total = $total + $cobro[$i]->monto;
        }


        return response()->json([
            'ok' => true,
            'data' => $cobro,
            'total' => $total
        ], JsonResponse::OK);
    }

    public function cancel(Request $request) {
        $validate = Validator::make($request->all(), [
            'cobranza_id' => 'required|exists:cobranza,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validate->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        $cobranza = Cobranza::where('id', $request->cobranza_id)->first();

        $cobranza->estatus = CobranzaStatusEnum::CANCELADO;

        if ($cobranza->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cambio aplicado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }
}
