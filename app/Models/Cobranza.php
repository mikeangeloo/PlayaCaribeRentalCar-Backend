<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Cobranza extends Model
{
    use HasFactory;
    protected $table = 'cobranza';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'contrato_id' => 'required|exists:contratos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tarjeta_id' => 'required|exists:tarjetas,id',
            'fecha_cargo' => 'required',
            'monto' => 'required|numeric',
            'moneda' => 'required|string',
            'tipo' => 'required|numeric',
            'cod_banco' => 'required',
            'fecha_procesado' => 'required'
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        } else {

            // validamos que la tarjeta le pertenezca al cliente
            $validTarjeta = Tarjetas::where('cliente_id', $request['cliente_id'])->first();
            if (!$validTarjeta) {
                return ['Esta tarjeta no le pertenece al cliente en curso'];
            }
            return true;
        }
    }
}
