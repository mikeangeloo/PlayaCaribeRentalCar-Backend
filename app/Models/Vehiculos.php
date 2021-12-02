<?php

namespace App\Models;

use App\Http\Controllers\VehiculosController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Vehiculos extends Model
{
    use HasFactory;
    protected $table = 'vehiculos';
    protected $primaryKey = 'id';

    public function marca() {
        return $this->belongsTo(Marcas::class, 'marca_id', 'id')->select('id', 'marca');
    }

    public function modelo() {
        return $this->belongsTo(Modelos::class, 'modelo_id', 'id')->select('id', 'modelo');
    }

    public function color() {
        return $this->belongsTo(Colores::class, 'color_id', 'id')->select('id', 'color');
    }

    public static function validateBeforeSave($request, $isUpdate = null) {
        $validate = Validator::make($request, [
            'marca_id' => 'required|exists:marcas,id',
            'modelo_id' => 'required|exists:modelos,id',
            'color_id' => 'required|exists:colores,id',
            'no_placas' => 'required|string',
            'cap_tanque' => 'required|string',
            'nombre' => 'required|string',
            'version' => 'required|string',
            'precio_venta' => 'required|numeric'
        ]);

        if (is_null($isUpdate)) {
            $vehiculo = Vehiculos::where('no_placas', $request['no_placas'])->first();
            if ($vehiculo) {
                return ['Este número de placa ya fue registrado previamente.'];
            }
        }

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }
}
