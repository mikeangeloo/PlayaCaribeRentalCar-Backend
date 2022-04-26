<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Notas;
use Illuminate\Http\Request;

class NotasController extends Controller
{
    public function saveUpdate(Request $request) {
        $validate = Notas::validateBeforeSave($request->all());

        if ($validate !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validate
            ], JsonResponse::BAD_REQUEST);
        }

        $user = $request->user;
        $message = 'Información guardada correctamente';
        $nota = null;

        if($request->has('id')) {
            $nota = Notas::where('id', $request->id)->first();
            $message = 'Información actualizada correctamente';
        }
        if(!$nota) {
            $nota = new Notas();
        }

        $nota->nota = $request->nota;
        $nota->modelo = $request->modelo;
        $nota->modelo_id = $request->modelo_id;
        $nota->agente_id = $user->id;
        $nota->agente = $user->nombre;
        $nota->activo = 1;


        if ($nota->save()) {
            return response()->json([
                'ok' => true,
                'message' => $message
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal intenta nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function remove($id) {

        $nota = Notas::where('id', $id)->first();

        $nota->activo = 0;

        if ($nota->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Nota eliminada correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal intenta nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }

    }
}
