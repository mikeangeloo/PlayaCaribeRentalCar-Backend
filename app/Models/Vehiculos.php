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
        return $this->belongsTo(MarcasVehiculos::class, 'marca_id', 'id')->select('id', 'marca');
    }

    public function categoria() {
        return $this->belongsTo(CategoriasVehiculos::class, 'categoria_vehiculo_id', 'id')->select('id', 'categoria');
    }

    public static function validateBeforeSave($request, $isUpdate = null) {
        $validate = Validator::make($request, [
            'modelo' => 'required|string|max:100',
            'modelo_ano' => 'required|numeric',
            'marca_id' => 'required|exists:marcas_vehiculos,id',
            'estatus' => 'nullable|string',
            'placas' => 'required|string|max:100',
            'num_poliza_seg' => 'required|string|max:100',
            'km_recorridos' => 'required|numeric',
            'prox_servicio' => 'nullable',
            'categoria_vehiculo_id' => 'required|exists:categorias_vehiculos,id',
            'cant_combustible' => 'nullable|string',
            'color' => 'required|string|max:100',
            'cap_tanque' => 'nullable|string|max:100',
            'version' => 'required|numeric',
            'precio_renta' => 'nullable|numeric',
            'codigo' => 'nullable|string|max:200',
            'num_serie' => 'required|string|max:100'
        ]);



        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        if (is_null($isUpdate)) {
            $vehiculo = Vehiculos::where('placas', $request['placas'])->first();
            if ($vehiculo) {
                return ['Este número de placa ya fue registrado previamente.'];
            }
        }

        return true;
    }
}
