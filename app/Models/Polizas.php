<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Polizas extends Model
{
    use HasFactory;
    protected $table = 'polizas';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request, $update = false) {
        $validate = Validator::make($request, [
            'aseguradora' => 'required|string|max:100',
            'no_poliza' => 'required|string|max:100',
            'tipo_poliza' => 'required|string|max:5',
            'tel_contacto' => 'required|numeric',
            'titular' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        if($update === false) {
            $poliza = Polizas::where('no_poliza', $request['no_poliza'])->first();
            if($poliza) {
                return ['Este número de poliza ya esta tomado, intente con otro.'];
            }
        }

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }
}
