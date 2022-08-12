<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Cobranza extends Model
{
    use HasFactory;
    protected $table = 'cobranza';

    public function tarjeta() {
        return $this->hasOne(Tarjetas::class, 'id', 'tarjeta_id')->select('id', 'c_type', 'c_charge_method', 'c_cn1', 'c_cn4', 'c_month', 'c_year');
    }

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'contrato_id' => 'required|exists:contratos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tarjeta_id' => 'nullable|exists:tarjetas,id',
            'fecha_cargo' => 'nullable',
            'monto' => 'nullable|numeric',
            'moneda' => 'required|string',
            'tipo' => 'required|numeric',
            'cobranza_seccion'  => 'required|string',
            'cod_banco' => 'nullable',
            'fecha_procesado' => 'nullable'
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
