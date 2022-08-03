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
            'tarjeta_circulacion' => 'numeric',
            'tapetes' => 'numeric',
            'silla_bebes' => 'numeric',
            'espejos' => 'numeric',
            'tapones_rueda' => 'numeric',
            'tapon_gas' => 'numeric',
            'senalamientos' => 'numeric',
            'gato' => 'numeric',
            'llave_rueda' => 'numeric',
            'limpiadores' => 'numeric',
            'antena' => 'numeric',
            'navegador' => 'numeric',
            'placas' => 'numeric',
            'radio' => 'numeric',
            'llantas' => 'numeric',
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }
}
