<?php

namespace App\Http\Controllers;

use App\Enums\ContratoStatusEnum;
use App\Enums\JsonResponse;
use App\Enums\VehiculoStatusEnum;
use App\Models\CambioEstatusVehiculo;
use App\Models\Contrato;
use App\Models\TarifasApollo;
use App\Models\TarifasApolloConf;
use App\Models\Vehiculos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehiculosController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculos = Vehiculos::where('activo', true)->orderBy('id', 'ASC')->get();
        $vehiculos->load('clase');

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listWithContract()
    {
        $vehiculos = Vehiculos::orderBy('id', 'ASC')->get();
        $vehiculos->load('marca', 'categoria', 'tarifa_categoria', 'clase','contrato');

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'ok' => false,
            'errors' => ['Not available']
        ], JsonResponse::BAD_REQUEST);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = Vehiculos::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo = new Vehiculos();
        $vehiculo->modelo = $request->modelo;
        $vehiculo->modelo_ano = $request->modelo_ano;
        $vehiculo->marca_id = $request->marca_id;
        $vehiculo->placas = $request->placas;
        $vehiculo->poliza_id = $request->poliza_id;
        $vehiculo->km_recorridos = $request->km_recorridos;
        $vehiculo->categoria_vehiculo_id = $request->categoria_vehiculo_id;
        $vehiculo->color = $request->color;
        $vehiculo->version = $request->version;
        $vehiculo->activo = 1;
        $vehiculo->estatus = VehiculoStatusEnum::DISPONIBLE;
        $vehiculo->clase_id = $request->clase_id;
        $vehiculo->tarifa_categoria_id = $request->tarifa_categoria_id;

        if ($request->has('prox_km_servicio')) {
            $vehiculo->prox_km_servicio = $request->prox_km_servicio;
        }
        if ($request->has('fecha_prox_servicio')) {
            $vehiculo->fecha_prox_servicio = $request->fecha_prox_servicio;
        }
        if ($request->has('cant_combustible_anterior')) {
            $vehiculo->cant_combustible_anterior = $request->cant_combustible_anterior;
        }
        if($request->has('cap_tanque')) {
            $vehiculo->cap_tanque = $request->cap_tanque;
        }
        if ($request->has('precio_renta')) {
            $vehiculo->precio_renta = $request->precio_renta;
        }

        if ($request->has('codigo')) {
            $vehiculo->codigo = $request->codigo;
        }

        if ($request->has('num_serie')) {
            $vehiculo->num_serie = $request->num_serie;
        }

        if ($vehiculo->save()) {
             // Guardamos tarifas
             // TODO: revisar si hay que omitir
            //  DB::beginTransaction();
            //  for ($i = 0; $i < count($request->tarifas_apollo); $i++) {
            //      try {
            //          $tarifa = new TarifasApollo();
            //          if (isset($request->tarifas_apollo[$i]->id) && $request->tarifas_apollo[$i]->id > 0) {
            //              $tarifa = TarifasApollo::where('id', $request->tarifas_apollo[$i]->id)->first();
            //          }

            //          $tarifa->frecuencia = $request->tarifas_apollo[$i]['frecuencia'];
            //          $tarifa->frecuencia_ref = $request->tarifas_apollo[$i]['frecuencia_ref'];
            //          $tarifa->activo = $request->tarifas_apollo[$i]['activo'];
            //          $tarifa->modelo = $request->tarifas_apollo[$i]['modelo'];
            //          $tarifa->modelo_id = isset($request->tarifas_apollo[$i]['modelo_id']) ? $request->tarifas_apollo[$i]['modelo_id'] : $vehiculo->id;
            //          $tarifa->precio_base = $request->tarifas_apollo[$i]['precio_base'];
            //          $tarifa->precio_final_editable = $request->tarifas_apollo[$i]['precio_final_editable'];
            //          $tarifa->ap_descuento = $request->tarifas_apollo[$i]['ap_descuento'];
            //          $tarifa->valor_descuento = $request->tarifas_apollo[$i]['valor_descuento'];
            //          $tarifa->descuento = $request->tarifas_apollo[$i]['descuento'];
            //          $tarifa->precio_final = $request->tarifas_apollo[$i]['precio_final'];
            //          $tarifa->required = $request->tarifas_apollo[$i]['required'];
            //          if ($tarifa->save()) {
            //              DB::commit();
            //          } else {
            //              DB::rollBack();
            //          }
            //      } catch (\Exception $e) {
            //          Log::debug($e);
            //          DB::rollBack();
            //          return response()->json([
            //             'ok' => false,
            //             'errors' => ['Algo salio mal, intente nuevamente']
            //         ], JsonResponse::BAD_REQUEST);
            //      }

            //  }
            return response()->json([
                'ok' => true,
                'message' => 'Véhiculo registrado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehiculo = Vehiculos::where('id', $id)->first();
        $vehiculo->load('clase');
        //TODO: revisar si quitamos
        $totalTarifasApolloConf = TarifasApolloConf::where('activo', true)->count();
        $vehiculo->tarifas = TarifasApollo::where('modelo', 'vehiculos')->where('modelo_id', $vehiculo->id)->latest()->take($totalTarifasApolloConf)->orderBy('id', 'ASC')->get();

        if (!$vehiculo) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'vehiculo' => $vehiculo
        ], JsonResponse::OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'ok' => false,
            'errors' => ['Not available']
        ], JsonResponse::BAD_REQUEST);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = Vehiculos::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo = Vehiculos::where('id', $id)->first();
        if (!$vehiculo) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $vehiculo->modelo = $request->modelo;
        $vehiculo->modelo_ano = $request->modelo_ano;
        $vehiculo->marca_id = $request->marca_id;
        $vehiculo->placas = $request->placas;
        $vehiculo->poliza_id = $request->poliza_id;
        $vehiculo->km_recorridos = $request->km_recorridos;
        $vehiculo->categoria_vehiculo_id = $request->categoria_vehiculo_id;
        $vehiculo->color = $request->color;
        $vehiculo->version = $request->version;
        $vehiculo->clase_id = $request->clase_id;
        $vehiculo->tarifa_categoria_id = $request->tarifa_categoria_id;

        if ($request->has('prox_km_servicio')) {
            $vehiculo->prox_km_servicio = $request->prox_km_servicio;
        }
        if ($request->has('fecha_prox_servicio')) {
            $vehiculo->fecha_prox_servicio = $request->fecha_prox_servicio;
        }
        if ($request->has('cant_combustible_anterior')) {
            $vehiculo->cant_combustible_anterior = $request->cant_combustible_anterior;
        }
        if($request->has('cap_tanque')) {
            $vehiculo->cap_tanque = $request->cap_tanque;
        }
        if ($request->has('precio_renta')) {
            $vehiculo->precio_renta = $request->precio_renta;
        }

        if ($request->has('codigo')) {
            $vehiculo->codigo = $request->codigo;
        }

        if ($request->has('num_serie')) {
            $vehiculo->num_serie = $request->num_serie;
        }

        if ($vehiculo->save()) {
            // // Guardamos tarifas
            // DB::beginTransaction();
            // for ($i = 0; $i < count($request->tarifas_apollo); $i++) {
            //     try {
            //         $tarifa = new TarifasApollo();
            //         if (isset($request->tarifas_apollo[$i]['id']) && $request->tarifas_apollo[$i]['id'] > 0) {
            //             $tarifa = TarifasApollo::where('id', $request->tarifas_apollo[$i]['id'])->first();
            //         }

            //         $tarifa->frecuencia = $request->tarifas_apollo[$i]['frecuencia'];
            //         $tarifa->frecuencia_ref = $request->tarifas_apollo[$i]['frecuencia_ref'];
            //         $tarifa->activo = $request->tarifas_apollo[$i]['activo'];
            //         $tarifa->modelo = $request->tarifas_apollo[$i]['modelo'];
            //         $tarifa->modelo_id = isset($request->tarifas_apollo[$i]['modelo_id']) ? $request->tarifas_apollo[$i]['modelo_id'] : $vehiculo->id;
            //         $tarifa->precio_base = $request->tarifas_apollo[$i]['precio_base'];
            //         $tarifa->precio_final_editable = $request->tarifas_apollo[$i]['precio_final_editable'];
            //         $tarifa->ap_descuento = $request->tarifas_apollo[$i]['ap_descuento'];
            //         $tarifa->valor_descuento = $request->tarifas_apollo[$i]['valor_descuento'];
            //         $tarifa->descuento = $request->tarifas_apollo[$i]['descuento'];
            //         $tarifa->precio_final = $request->tarifas_apollo[$i]['precio_final'];
            //         $tarifa->required = $request->tarifas_apollo[$i]['required'];
            //         if ($tarifa->save()) {
            //             DB::commit();
            //         } else {
            //             DB::rollBack();
            //         }
            //     } catch (\Exception $e) {
            //         Log::debug($e);
            //         DB::rollBack();
            //         return response()->json([
            //             'ok' => false,
            //             'errors' => ['Algo salio mal, intente nuevamente']
            //         ], JsonResponse::BAD_REQUEST);
            //     }

            // }
            return response()->json([
                'ok' => true,
                'message' => 'Véhiculo actualizado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return response()->json([
                'ok' => false,
                'errors' => ['Proporcione un dato válido']
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo = Vehiculos::where('id', $id)->first();

        if (!$vehiculo) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $contratoEstatus = [ContratoStatusEnum::BORRADOR, ContratoStatusEnum::RENTADO];
        $hasContratoOn = Contrato::where('vehiculo_id', $vehiculo->id)->whereIn('estatus', $contratoEstatus)->first();

        if($hasContratoOn) {
            return response()->json([
                'ok' => false,
                'errors' => ['No es posibile deshabilitar este vehículo esta ligado al contrato # '. $hasContratoOn->num_contrato]
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo->activo = false;

        if ($vehiculo->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Véhiculo dado de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $vehiculos = Vehiculos::orderBy('id', 'ASC')->get();
        $vehiculos->load('marca', 'categoria', 'tarifa_categoria', 'clase');

        return response()->json([
            'ok' => true,
            'vehiculos' => $vehiculos
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $vehiculo = Vehiculos::where('id', $id)->first();
        if (!$vehiculo) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($vehiculo->activo === 1 || $vehiculo->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo->activo = true;

        if ($vehiculo->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }

    public function getList(Request $request) {
        $query = Vehiculos::select();

        if ($request->has('orderBy')) {
            if ($request->orderBy == 'estatus') {
                $query->orderBy('estatus', 'ASC');
            } else if ($request->orderBy == 'tarifa_categoria_id') {
                $query->orderByRaw("tarifa_categoria_id = $request->tarifa_categoria_id DESC");
                //$query->orderBy('id', 'ASC');
            }
        }

        if ($request->has('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        // if ($request->has('tarifa_categoria_id')) {
        //     $query->where('tarifa_categoria_id', '=', $request->tarifa_categoria_id);
        // }

        if($request->has('vehicle_id')) {
            $query->where('id', $request->vehicle_id);
        }

        $vehiculos = $query->get();
        $vehiculos->load('marca', 'categoria', 'clase', 'tarifa_categoria');


        $_vehiculos = [];
        //TODO: revisar si quitamos
        // $totalTarifasApolloConf = TarifasApolloConf::where('activo', true)->count();
        // for ($i = 0; $i < count($vehiculos); $i++) {
        //    $vehiculos[$i]->tarifas = TarifasApollo::where('modelo', 'vehiculos')->where('modelo_id', $vehiculos[$i]->id)->latest()->take($totalTarifasApolloConf)->orderBy('id', 'ASC')->get();
        // }

        for ($i = 0; $i < count($vehiculos); $i++) {
            array_push($_vehiculos, [
                'id' => $vehiculos[$i]->id,
                'estatus' => $vehiculos[$i]->estatus,
                'tarifa' => isset($vehiculos[$i]->tarifa_categoria) ? $vehiculos[$i]->tarifa_categoria->categoria. ' | ' .$vehiculos[$i]->tarifa_categoria->precio_renta : '--',
                'código' => $vehiculos[$i]->codigo,
                'categoría' => $vehiculos[$i]->categoria->categoria,
                'modelo' => $vehiculos[$i]->modelo,
                'modelo_año' => $vehiculos[$i]->modelo_ano,
                'marca' => $vehiculos[$i]->marca->marca,
                'color' => $vehiculos[$i]->color,
                'placas' => $vehiculos[$i]->placas
            ]);
        }

        return response()->json([
            'ok' => true,
            'data' => $_vehiculos,
            'fullData' => $vehiculos
        ], JsonResponse::OK);
    }

    public function updateStatus(Request $request, $id)
    {
        $validateData = CambioEstatusVehiculo::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $vehiculo = Vehiculos::where('id', $id)->first();
        if (!$vehiculo) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $vehiculo->estatus = $request->estatus;


        if ($vehiculo->save()) {

            $cambioEstatusVehiculo = new CambioEstatusVehiculo();

            $cambioEstatusVehiculo->vehiculo_id = $id;
            $cambioEstatusVehiculo->estatus = $request->estatus;
            $cambioEstatusVehiculo->observaciones = $request->observaciones;

            $cambioEstatusVehiculo->save();

            return response()->json([
                'ok' => true,
                'message' => 'Véhiculo actualizado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }
}


