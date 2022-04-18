<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\CheckList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckListController extends Controller
{
    public function saveUpdate(Request $request) {
        $validate = CheckList::validateBeforeSave($request->all());

        if ($validate !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validate
            ], JsonResponse::BAD_REQUEST);
        }

        $user = $request->user;

        DB::beginTransaction();

        $message = 'Información almacenada correctamente';

        try {
            for($i = 0; $i < count($request->payload); $i++) {
                $checkList = null;
                if (isset($request->payload[$i]['id'])) {
                    $checkList = CheckList::where('id', $request->payload[$i]['id'])->first();
                    $message = 'Información actualizada correctamente';
                }

                if (!$checkList) {
                    $checkList = new CheckList();
                }

                $checkList->contrato_id = $request->payload[$i]['contrato_id'];
                $checkList->tipo = $request->payload[$i]['tipo'];
                $checkList->width = $request->payload[$i]['width'];
                $checkList->height = $request->payload[$i]['height'];
                $checkList->containerPost = $request->payload[$i]['containerPost'];
                $checkList->boxPosition = $request->payload[$i]['boxPosition'];
                $checkList->objId = $request->payload[$i]['objId'];
                $checkList->top = $request->payload[$i]['top'];
                $checkList->left = $request->payload[$i]['left'];
                $checkList->action = $request->payload[$i]['action'];
                $checkList->levelColor = $request->payload[$i]['levelColor'];
                $checkList->levelTxt = $request->payload[$i]['levelTxt'];
                $checkList->indicatorIcon = $request->payload[$i]['indicatorIcon'];
                $checkList->indicatorTitle = $request->payload[$i]['indicatorTitle'];
                if ($request->has('enable')) {
                    $checkList->enable = $request->payload[$i]['enable'];
                }
                $checkList->saved = true;
                $checkList->agente_id = $user->id;
                $checkList->agente = $user->nombre;

                if ($checkList->save() === false) {
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Algo salio mal al guardar la inforamción, intente de nuevo']
                    ], JsonResponse::BAD_REQUEST);
                }

                DB::commit();
            }
        } catch(\Throwable $e) {
            DB::rollBack();
            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al guardar la inforamción, intente de nuevo']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'message' => $message
        ], JsonResponse::OK);
    }

    public function show($id) {
        $checkList = CheckList::where('activo', 1)->where('id', $id)->first();

        if(!$checkList) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información buscada o ya no existe']
            ], JsonResponse::BAD_REQUEST);
        }

        $checkList->load('notas');

        return response()->json([
            'ok' => true,
            'data' => $checkList
        ], JsonResponse::OK);
    }

    public function remove($id) {
        $checkList = CheckList::where('id', $id)->first();

        if(!$checkList) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información buscada o ya no existe']
            ], JsonResponse::BAD_REQUEST);
        }

        $checkList->activo = 0;

        $checkList->save();

        return response()->json([
            'ok' => true,
            'message' => 'Checkpoint eliminado'
        ], JsonResponse::OK);
    }
}
