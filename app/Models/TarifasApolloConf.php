<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TarifasApolloConf extends Model
{
    use HasFactory;
    protected $table = 'tarifas_apollo_conf';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'modelo' => 'required|string',
            'frecuencia' => 'required|string',
            'frecuencia_ref' => 'required|string',
            'ap_descuento' => 'required|boolean',
            'valor_descuento' => 'required|numeric',
            'activo' => 'nullable|boolean',
            'precio_final_editable' => 'nullable|boolean',
            'required' => 'required|boolean'
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        } else {
            return true;
        }
    }
}
