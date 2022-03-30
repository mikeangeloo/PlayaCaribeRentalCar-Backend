<?php

namespace App\Helpers;

use App\Enums\JsonResponse;
use App\Models\TarifasApollo;
use App\Models\TarifasApolloConf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommonHelper
{
    /**
     *
     */
    public static function syncWithTarifasApollo($model, $tarifa = null, $syncRestart = false) {
        // preparamos para insertar en tarifas_apollo
        $modelData = DB::table($model)->get();
        if (!$modelData) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay: '. $model. 'registrados']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($syncRestart === true) {
            TarifasApollo::where('modelo', $model)->delete();
        }

        $tarifasFrec= TarifasApolloConf::where('modelo', $model)->where('activo', true)->get();
        for ($i = 0; $i < count($tarifasFrec); $i++) {
            $result = self::insertTarifasApollo($tarifasFrec[$i], $modelData);
            if ($result !== true) {
                return $result;
                break;
            }
        }
        return true;
    }

    private static function insertTarifasApollo($tarifasFrec, $modelData) {

        DB::beginTransaction();
        try {

            for ($i = 0; $i < count($modelData); $i++) {
               //dd($modelData[$i]->id);
               $_valorDesc = (float) round(($tarifasFrec->valor_descuento / 100), 4);
               //dd($_valorDesc);
                DB::table('tarifas_apollo')->updateOrInsert
                (
                    [
                        'tarifa_apollo_conf_id' => $tarifasFrec->id,
                        'modelo_id' => $modelData[$i]->id,
                    ],
                    [
                       'tarifa_apollo_conf_id' => $tarifasFrec->id,
                       'modelo' => $tarifasFrec->modelo,
                       'modelo_id' => $modelData[$i]->id,
                       'frecuencia' => $tarifasFrec->frecuencia,
                       'frecuencia_ref' => $tarifasFrec->frecuencia_ref,
                       'precio_base' => $modelData[$i]->precio_renta,
                       'ap_descuento' => $tarifasFrec->ap_descuento,
                       'valor_descuento' => $tarifasFrec->valor_descuento,
                       'descuento' => (float) round($modelData[$i]->precio_renta * $_valorDesc, 4),
                       'precio_final' => ($tarifasFrec->frecuencia_ref == 'hours') ? round($modelData[$i]->precio_renta / 24, 4) : round($modelData[$i]->precio_renta - (float) round($modelData[$i]->precio_renta * $_valorDesc, 4), 4),
                       'activo' => $tarifasFrec->activo,
                       'precio_final_editable' => $tarifasFrec->precio_final_editable,
                       'required' => $tarifasFrec->required,
                    ]
               );
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::debug($e);
            return false;
        }

        DB::commit();
        return true;
   }
}
