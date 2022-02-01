<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Contrato extends Model
{
    use HasFactory;
    protected $table = 'contratos';
    protected $primaryKey = 'id';

    protected $casts = [
        'etapas_guardadas' => 'array'
    ];

    public function cliente() {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    public function vehiculo() {
        return $this->hasOne(Vehiculos::class, 'id', 'vehiculo_id');
    }

    public static function validateBeforeSaveProgress($request) {
        $validateData = Validator::make($request, [
            'seccion' => 'required|string',
            'num_contrato' => 'nullable|exists:contratos,num_contrato'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function validateDatosGeneralesBeforeSave($request) {
        $validateData = Validator::make($request, [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'tipo_tarifa_id' => 'required|exists:tipos_tarifas,id',
            'tipo_tarifa' => 'required|string',
            'precio_unitario_inicial' => 'required|numeric',
            'precio_unitario_final' => 'required|numeric',
            'rango_fechas' => 'required',
            'rango_fechas.fecha_salida' => 'required',
            'rango_fechas.fecha_retorno' => 'required',
            'total_dias' => 'required|numeric',
            'ub_salida_id' => 'required',
            'ub_retorno_id' => 'required',
            'hora_elaboracion' => 'required',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function validateDatosVehiculo($request) {
        $validateData = Validator::make($request, [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'km_salida' => 'nullable|numeric',
            'km_llegada' => 'nullable|numeric',
            'km_recorrido' => 'nullable|numeric',
            'gas_salida' => 'nullable|string|max:100',
            'gas_llegada' => 'nullable|string|max:100'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function setEtapasGuardadas($num_contrato) {
        $contract = Contrato::where('num_contrato', $num_contrato)->first();

        if (!$contract) {
            return (object) ['ok' => false, 'errors' => ['No se encontro la información solicitada']];
        }
        $etapa = [];

        $datosGeneralesColumns = [
            'vehiculo_id',
            'tipo_tarifa_id',
            'tipo_tarifa',
            'precio_unitario_inicial',
            'precio_unitario_final',
            'total_dias',
            'ub_salida_id',
            'ub_retorno_id',
            'hora_elaboracion',
            'fecha_salida',
            'fecha_retorno'
        ];
        for ($i = 0; $i < count($datosGeneralesColumns); $i ++) {
            if (!is_null($contract->{$datosGeneralesColumns[$i]})) {
                array_push($etapa, 'datos_generales');
                break;
            }
        }

        if ($contract->cliente_id) {
            array_push($etapa, 'datos_cliente');
        }

        $vehiculoVerifyColumns = [
            'vehiculo_id', 'km_salida', 'km_llegada', 'km_recorrido', 'gas_salida', 'gas_llegada'
        ];
        for ($i = 0; $i < count($vehiculoVerifyColumns); $i ++) {
            if (!is_null($contract->{$vehiculoVerifyColumns[$i]})) {
                array_push($etapa, 'datos_vehiculo');
                break;
            }
        }

        $contract->etapas_guardadas = $etapa;
        $contract->save();

        $contract->load('cliente');
        $contract->load('vehiculo.marca');
        //$contract->load('vehiculo.tarifas');
        $contract->vehiculo->tarifas = TarifasApollo::where('modelo', 'vehiculos')->where('modelo_id', $contract->vehiculo->id)->latest()->orderBy('id', 'ASC')->limit(4)->get();

        return (object) ['ok' => true, 'data' => $contract];
    }
}
