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

    public function tarifas() {
        return $this->hasMany(TarifasApollo::class, 'modelo_id', 'id')->latest()->limit(4)->orderBy('id', 'ASC');
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
            'num_serie' => 'required|string|max:100',
            'clase_id' => 'required|numeric',
            'tarifas_apollo' => 'required',
            'tarifas_apollo.*.frecuencia' => 'required|string',
            'tarifas_apollo.*.frecuencia_ref' => 'required|string',
            'tarifas_apollo.*.activo' => 'nullable|boolean',
            'tarifas_apollo.*.modelo' => 'required|string',
            'tarifas_apollo.*.modelo_id' => 'nullable|numeric',
            'tarifas_apollo.*.precio_base' => 'required|numeric',
            'tarifas_apollo.*.precio_final_editable' => 'required|boolean',
            'tarifas_apollo.*.ap_descuento' => 'required|boolean',
            'tarifas_apollo.*.valor_descuento' => 'nullable|numeric',
            'tarifas_apollo.*.descuento' => 'nullable|numeric',
            'tarifas_apollo.*.precio_final' => 'required|numeric',
            'tarifas_apollo.*.required' => 'required|boolean',
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
