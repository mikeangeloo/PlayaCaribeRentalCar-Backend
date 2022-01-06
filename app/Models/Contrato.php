<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Contrato extends Model
{
    use HasFactory;
    protected $table = 'contratos';
    protected $primaryKey = 'id';

    protected $casts = [
        'etapas_guardadas' => 'array'
    ];

    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'renta_of_id' => 'required|exists:sucursales,id',
            'renta_of_codigo' => 'required|string',
            'renta_of_dir' => 'required|string',
            'renta_of_fecha' => 'required|date',
            'renta_of_hora' => 'required',

            'retorno_of_id' => 'required|exists:sucursales,id',
            'retorno_of_codigo' => 'required|string',
            'retorno_of_dir' => 'required|string',
            'retorno_of_fecha' => 'required|date',
            'retorno_of_hora' => 'required',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function setEtapasGuardadas($num_contrato) {
        $contract = Contrato::where('num_contrato', $num_contrato)->first();

        if (!$contract) {
            return (object) ['ok' => false, 'errors' => ['No se encontro la información solicitada']];
        }
        $etapa = [];

        $datosGeneralesColumns = [
            'renta_of_id','renta_of_codigo','renta_of_dir','renta_of_fecha',
            'renta_of_hora','retorno_of_id','retorno_of_codigo','retorno_of_dir',
            'retorno_of_fecha','retorno_of_hora'
        ];
        for ($i = 0; $i < count($datosGeneralesColumns); $i ++) {
            if (!is_null($contract->{$datosGeneralesColumns[$i]})) {
                array_push($etapa, 'datos_generales');
                break;
            }
        }


        $contract->etapas_guardadas = $etapa;
        $contract->save();

        return (object) ['ok' => true, 'data' => $contract];
    }
}
