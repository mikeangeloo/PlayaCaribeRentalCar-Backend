<?php

namespace App\Http\Controllers;

use App\Enums\CobranzaStatusEnum;
use App\Enums\JsonResponse;
use App\Models\Cobranza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CobranzaController extends Controller
{
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
