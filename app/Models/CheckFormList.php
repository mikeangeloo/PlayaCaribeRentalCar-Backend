<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CheckFormList extends Model
{
    use HasFactory;
    protected $table = 'check_form_list';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'contrato_id' => 'required|exists:contratos,id|numeric',
            'tarjeta_circulacion' => 'string',
            'tapetes' => 'string',
            'silla_bebes' => 'string',
            'espejos' => 'string',
            'tapones_rueda' => 'string',
            'tapon_gas' => 'string',
            'senalamientos' => 'string',
            'gato' => 'string',
            'llave_rueda' => 'string',
            'limpiadores' => 'string',
            'antena' => 'string',
            'navegador' => 'string',
            'placas' => 'string',
            'radio' => 'string',
            'llantas' => 'string',
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }
}
