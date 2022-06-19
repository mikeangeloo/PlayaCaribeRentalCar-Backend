<?php

namespace App\Models;

use App\Enums\CobranzaStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Contrato extends Model
{
    use HasFactory;
    protected $table = 'contratos';
    protected $primaryKey = 'id';

    protected $casts = [
        'etapas_guardadas' => 'array',
        'cobros_extras_ids' => 'array',
        'cobros_extras' => 'array',
        'cobranza_calc' => 'array',
        'tarifa_modelo_obj' => 'array',
        'cargos_retorno_extras_ids' => 'array',
        'cargos_retorno_extras' => 'array',
        'cobranza_calc_retorno' => 'array',
    ];

    public function cliente() {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    public function usuario() {
        return $this->hasOne(User::class, 'id', 'user_create_id');
    }


    public function vehiculo() {
        return $this->hasOne(Vehiculos::class, 'id', 'vehiculo_id');
    }

    public function salida() {
        return $this->hasOne(Ubicaciones::class, 'id', 'ub_salida_id');
    }

    public function retorno() {
        return $this->hasOne(Ubicaciones::class, 'id', 'ub_retorno_id');
    }

    public function cobranza() {
        return $this->hasMany(Cobranza::class, 'contrato_id', 'id')->where('estatus', 2);;
    }

    public function check_list_salida() {
        return $this->hasMany(CheckList::class, 'contrato_id', 'id')->where('tipo', 0)->where('activo', 1);
    }

    public function check_form_list() {
        return $this->hasOne(CheckFormList::class, 'contrato_id', 'id');
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
            //'vehiculo_id' => 'required|exists:vehiculos,id',
            'tipo_tarifa_id' => 'required|exists:tipos_tarifas,id',
            'tipo_tarifa' => 'required|string',

            'modelo_id' => 'nullable|numeric',
            'modelo' => 'nullable|string',

            'tarifa_modelo' => 'required|string',
            'tarifa_modelo_id' => 'required|numeric',
            'tarifa_apollo_id' => 'nullable|numeric',
            'tarifa_modelo_label' => 'required|string',
            'tarifa_modelo_precio' => 'required|numeric',
            'tarifa_modelo_obj' => 'nullable',

            'vehiculo_clase_id' => 'nullable|numeric',
            'vehiculo_clase' => 'nullable|string',
            'vehiculo_clase_precio' => 'nullable|numeric',

            'precio_unitario_inicial' => 'nullable|numeric',
            'comision' => 'nullable|numeric',
            'precio_unitario_final' => 'required|numeric',

            'rango_fechas' => 'required',
            'rango_fechas.fecha_salida' => 'required',
            'rango_fechas.fecha_retorno' => 'required',
            'cobros_extras' => 'nullable',
            'cobros_extras_ids' => 'nullable',
            'subtotal' => 'required|numeric',
            'con_descuento' => 'nullable',
            'descuento' => 'nullable|numeric',
            'con_iva' => 'nullable',
            'iva' => 'nullable',
            'iva_monto' => 'nullable',
            'total' => 'required',

            'folio_cupon' => 'nullable|string',
            'valor_cupon' => 'nullable|numeric',

            'cobranza_calc' => 'required',
            'total_dias' => 'required|numeric',
            'ub_salida_id' => 'required',
            'ub_retorno_id' => 'required',
            'hora_elaboracion' => 'nullable',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function validateDatosReronoBeforeSave($request) {
        $validateData = Validator::make($request, [
            //'vehiculo_id' => 'required|exists:vehiculos,id',
            'cant_combustible_retorno' =>'required',
            'km_final' =>'required',
            'cargos_extras_retorno' => 'nullable',
            'cargos_extras_retorno_ids' => 'nullable',
            'subtotal_retorno' => 'required|numeric',
            'con_iva_retorno' => 'nullable',
            'iva_retorno' => 'nullable',
            'iva_monto_retorno' => 'nullable',
            'total_retorno' => 'required',
            'cobranza_calc_retorno' => 'required',
            'frecuencia_extra' => 'nullable',
            'cobranzaExtraPor' => 'nullable'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }


    /**
     *
     */
    public static function validateDatosVehiculo($request) {
        $validateData = Validator::make($request, [
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'km_inicial' => 'nullable|numeric',
            'km_final' => 'nullable|numeric',
            'km_anterior' => 'nullable|numeric',
            'cant_combustible_salida' => 'nullable|string',
            'cant_combustible_retorno' => 'nullable|string',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }

    public static function setEtapasGuardadas($num_contrato) {
        $contract = Contrato::where('num_contrato', $num_contrato)
                    ->with(
                        ['cobranza' => function($q) {
                            $validCobranzaEstatus = [CobranzaStatusEnum::PROGRAMADO, CobranzaStatusEnum::COBRADO];
                            $q->whereIn('estatus', $validCobranzaEstatus);
                        },
                        'cobranza.tarjeta'
                        ])
                    ->first();

        if (!$contract) {
            return (object) ['ok' => false, 'errors' => ['No se encontro la información solicitada']];
        }
        $etapa = [];

        if ($contract->cliente_id) {
            array_push($etapa, 'datos_cliente');
        }

        $datosGeneralesColumns = [
            //'vehiculo_id',
            'tipo_tarifa_id',
            'tipo_tarifa',
            'precio_unitario_inicial',
            'precio_unitario_final',
            'total_dias',
            'ub_salida_id',
            'ub_retorno_id',
            'fecha_salida',
            'fecha_retorno',
            'cobros_extras',
            'subtotal',
            'descuento',
            'con_iva',
            'iva',
            'iva_monto',
            'total',
            'cobranza_calc'
        ];
        for ($i = 0; $i < count($datosGeneralesColumns); $i ++) {
            if (!is_null($contract->{$datosGeneralesColumns[$i]})) {
                array_push($etapa, 'datos_generales');
                break;
            }
        }

        $datosVehiculoColums = [
            'vehiculo_id',
            'vehiculo_id',
            'km_inicial',
            'km_final',
            'km_anterior',
            'cant_combustible_salida',
            'cant_combustible_retorno'
        ];
        for ($i = 0; $i < count($datosVehiculoColums); $i ++) {
            if (!is_null($contract->{$datosVehiculoColums[$i]})) {
                array_push($etapa, 'datos_vehiculo');
                break;
            }
        }

        //buscamos si hay información en cobranza
        $validCobranzaEstatus = [CobranzaStatusEnum::COBRADO, CobranzaStatusEnum::PROGRAMADO];
        $totalCobranza = Cobranza::where('contrato_id', $contract->id)->where('cobranza_seccion', 'salida')->whereIn('estatus', $validCobranzaEstatus)->count();
        if ($totalCobranza > 0) {
            array_push($etapa, 'cobranza');
        }

        $contract->etapas_guardadas = $etapa;
        $contract->save();

        $contract->load('cliente');
        $contract->load('vehiculo.marca', 'vehiculo.clase');
        $contract->load('vehiculo.categoria');
        if (isset($contract->vehiculo) && isset($contract->vehiculo->tarifas)) {
            $contract->vehiculo->tarifas = TarifasApollo::where('modelo', 'vehiculos')->where('modelo_id', $contract->vehiculo->id)->latest()->orderBy('id', 'ASC')->limit(4)->get();
        }

        $contract->load('check_list_salida', 'check_list_salida.notas');

        if (isset($contract->check_list_salida) && count($contract->check_list_salida) > 0) {
            array_push($etapa, 'check_list_salida');
            $contract->etapas_guardadas = $etapa;
            $contract->save();
        }


        if ($contract->check_form_list_id) {
            $contract->load('check_form_list');
            array_push($etapa, 'check_form_list');
            $contract->etapas_guardadas = $etapa;
            $contract->save();
        }


        if ($contract->firma_cliente) {
            array_push($etapa, 'firma');
            $contract->etapas_guardadas = $etapa;
            $contract->save();
        }

        $datosRetornoColumns = [
            //'vehiculo_id',
            'cant_combustible_retorno',
            'km_final',
            'cargos_retorno_extras',
            'subtotal_retorno',
            'con_iva_retorno',
            'iva_retorno',
            'iva_monto_retorno',
            'total_retorno',
            'cobranza_calc_retorno'
        ];
        for ($i = 0; $i < count($datosRetornoColumns); $i ++) {
            if (!is_null($contract->{$datosRetornoColumns[$i]})) {
                array_push($etapa, 'retorno');
                break;
            }
        }
        $contract->etapas_guardadas = $etapa;
        $contract->save();

        //buscamos si hay información en cobranza retorno
        $validCobranzaEstatus = [CobranzaStatusEnum::COBRADO, CobranzaStatusEnum::PROGRAMADO];
        $totalCobranzaRetorno = Cobranza::where('contrato_id', $contract->id)->where('cobranza_seccion', 'retorno')->whereIn('estatus', $validCobranzaEstatus)->count();
        if ($totalCobranzaRetorno > 0) {
            array_push($etapa, 'cobranza_retorno');
        }

        $contract->etapas_guardadas = $etapa;
        $contract->save();


        return (object) ['ok' => true, 'data' => $contract];
    }
}
