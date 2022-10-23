<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Cobranza;
use App\Models\Divisas;
use App\Models\TiposCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversionMonedaController extends Controller
{

    public function getAllTiposCambio() {
        $tiposCambio = TiposCambio::whereRaw('created_at IN (select MAX(created_at) FROM tipos_cambio GROUP BY divisa_base_id)')->get();

        return response()->json([
            'ok' => true,
            'data' => $tiposCambio
        ], JsonResponse::OK);
    }

    public function getAllHistory() {
        $tiposCambio = TiposCambio::orderBy('id', 'DESC')->get();

        return response()->json([
            'ok' => true,
            'data' => $tiposCambio
        ], JsonResponse::OK);
    }

    public function getTipoCambio(Request $request) {
        $validate = Validator::make($request->all(), [
            'divisa_base' => 'nullable|string',
            'divisa_conversion' => 'nullable|string',
            'id' => 'nullable|numeric'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validate->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }

        $tipoCambioQ = TiposCambio::whereRaw('created_at IN (select MAX(created_at) FROM tipos_cambio GROUP BY divisa_base_id)');

        if ($request->has('id')) {
            $tipoCambioQ->where('id', $request->id);
        }

        if ($request->has('divisa_base') && $request->has('divisa_conversion')) {
            $tipoCambioQ->where('divisa_base', $request->divisa_base)->where('divisa_conversion', $request->divisa_conversion);
        }

        $tipoCambio = $tipoCambioQ->first();

        return response()->json([
            'ok' => true,
            'data' => $tipoCambio
        ], JsonResponse::OK);
    }

    public function save(Request $request) {
        $user = $request->user;

        $validate = Validator::make($request->all(), [
            'tipo_cambio' => 'required|numeric',
            'divisa_base_id' => 'required|numeric|exists:divisas,id',
            'divisa_base' => 'required|string',
            'divisa_conversion_id' => 'required|numeric|exists:divisas,id',
            'divisa_conversion' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validate->errors()->all()
            ], JsonResponse::BAD_REQUEST);
        }
        $message = '';

        $tipoCambio = new TiposCambio();
        $message = 'Tipo de cambio guardado correctamente';


        $tipoCambio->tipo_cambio = $request->tipo_cambio;
        $tipoCambio->divisa_base_id = $request->divisa_base_id;
        $tipoCambio->divisa_base = $request->divisa_base;
        $tipoCambio->divisa_conversion_id = $request->divisa_conversion_id;
        $tipoCambio->divisa_conversion = $request->divisa_conversion;
        $tipoCambio->created_id = $user->id;
        $tipoCambio->created_by = $user->username;

        if ($tipoCambio->save()) {
            return response()->json([
                'ok' => true,
                'data' => $tipoCambio,
                'message' => $message
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al realizar el cambio de tipo de cambio']
            ], JsonResponse::BAD_REQUEST);
        }

    }

    public function getDivisas() {
        $divisas = Divisas::all();

        return response()->json([
            'ok' => true,
            'data' => $divisas,
        ], JsonResponse::OK);
    }

    public function deleteTipoCambio($id) {
        $tipoCambio = TiposCambio::where('id', $id)->first();
        $cobranza = Cobranza::where('tipo_cambio_id', $id)->first();

        if ($cobranza) {
            return response()->json([
                'ok' => false,
                'errors' => ['Existen contratos relacionados a este tipo de cambio, no es posible borrar la configuración']
            ], JsonResponse::BAD_REQUEST);
        }

        if($tipoCambio->delete()) {
            return response()->json([
                'ok' => true,
                'message' => 'Información eliminada correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al momento de eliminar la información']
            ], JsonResponse::BAD_REQUEST);
        }
    }
}
