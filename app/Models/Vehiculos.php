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

    public function tarifa_categoria() {
        return $this->belongsTo(TarifasCategorias::class, 'tarifa_categoria_id', 'id');
    }

    public function tarifas() {
        return $this->hasMany(TarifasApollo::class, 'modelo_id', 'id');
    }

    public function clase() {
        return $this->belongsTo(ClasesVehiculos::class, 'clase_id', 'id');
    }

    public function contrato() {
        return $this->hasOne(Contrato::class, 'vehiculo_id', 'id')->where('estatus','2')->with('cliente','usuario');
    }

    public function contratos() {
        return $this->hasMany(Contrato::class, 'vehiculo_id', 'id');
    }

    public function poliza() {
        return $this->belongsTo(Polizas::class, 'poliza_id','id');
    }

    public static function validateBeforeSave($request, $isUpdate = null) {
        $validate = Validator::make($request, [
            'modelo' => 'required|string|max:100',
            'modelo_ano' => 'required|numeric',
            'marca_id' => 'required|exists:marcas_vehiculos,id',
            'estatus' => 'nullable|string',
            'placas' => 'required|string|max:100',
            'poliza_id' => 'required|numeric',
            'km_recorridos' => 'required|numeric',
            'fecha_prox_servicio' => 'nullable',
            'prox_km_servicio' => 'nullable',
            'categoria_vehiculo_id' => 'required|exists:categorias_vehiculos,id',
            'cant_combustible_anterior' => 'nullable|string',
            'color' => 'required|string|max:100',
            'cap_tanque' => 'nullable|string|max:100',
            'version' => 'required|numeric',
            'precio_renta' => 'nullable|numeric',
            'codigo' => 'nullable|string|max:200',
            'num_serie' => 'required|string|max:100',
            'clase_id' => 'required|numeric',

            'tarifa_categoria_id' => 'required|numeric',
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
