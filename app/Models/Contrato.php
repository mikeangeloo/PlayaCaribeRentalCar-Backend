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
}
