<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class Clientes extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'nombre' => 'required|string|max:200',
            //'apellidos' => 'required|string|max:70',
            'telefono' => 'required|string',
            'email' => 'required|email',
            'num_licencia' => 'required|string',
            'licencia_mes' => 'required',
            'licencia_ano' => 'required',
            'direccion' => 'nullable|string|max:100'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }

    public function tarjetas() {
        return $this->hasMany(Tarjetas::class, 'cliente_id', 'id')->select('id', 'cliente_id', 'c_type', 'c_cn4', 'c_month', 'c_year', 'c_charge_method');
    }
}
