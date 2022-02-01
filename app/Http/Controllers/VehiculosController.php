<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Enums\VehiculoStatusEnum;
use App\Models\TarifasApollo;
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
        $vehiculo->num_poliza_seg = $request->num_poliza_seg;
        $vehiculo->km_recorridos = $request->km_recorridos;
        $vehiculo->categoria_vehiculo_id = $request->categoria_vehiculo_id;
        $vehiculo->color = $request->color;
        $vehiculo->version = $request->version;
        $vehiculo->activo = 1;
        $vehiculo->estatus = VehiculoStatusEnum::DISPONIBLE;
        $vehiculo->clase_id = $request->clase_id;


        if ($request->has('prox_servicio')) {
            $vehiculo->prox_servicio = $request->prox_servicio;
        }
        if ($request->has('cant_combustible')) {
            $vehiculo->cant_combustible = $request->cant_combustible;
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
             DB::beginTransaction();
             for ($i = 0; $i < count($request->tarifas_apollo); $i++) {
                 try {
                     $tarifa = new TarifasApollo();
                     if (isset($request->tarifas_apollo[$i]->id) && $request->tarifas_apollo[$i]->id > 0) {
                         $tarifa = TarifasApollo::where('id', $request->tarifas_apollo[$i]->id)->first();
                     }

                     $tarifa->frecuencia = $request->tarifas_apollo[$i]['frecuencia'];
                     $tarifa->frecuencia_ref = $request->tarifas_apollo[$i]['frecuencia_ref'];
                     $tarifa->activo = $request->tarifas_apollo[$i]['activo'];
                     $tarifa->modelo = $request->tarifas_apollo[$i]['modelo'];
                     $tarifa->modelo_id = isset($request->tarifas_apollo[$i]['modelo_id']) ? $request->tarifas_apollo[$i]['modelo_id'] : $vehiculo->id;
                     $tarifa->precio_base = $request->tarifas_apollo[$i]['precio_base'];
                     $tarifa->precio_final_editable = $request->tarifas_apollo[$i]['precio_final_editable'];
                     $tarifa->ap_descuento = $request->tarifas_apollo[$i]['ap_descuento'];
                     $tarifa->valor_descuento = $request->tarifas_apollo[$i]['valor_descuento'];
                     $tarifa->descuento = $request->tarifas_apollo[$i]['descuento'];
                     $tarifa->precio_final = $request->tarifas_apollo[$i]['precio_final'];
                     $tarifa->required = $request->tarifas_apollo[$i]['required'];
                     if ($tarifa->save()) {
                         DB::commit();
                     } else {
                         DB::rollBack();
                     }
                 } catch (\Exception $e) {
                     Log::debug($e);
                     DB::rollBack();
                 }

             }
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
        $vehiculo->load('tarifas');

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
        $vehiculo->num_poliza_seg = $request->num_poliza_seg;
        $vehiculo->km_recorridos = $request->km_recorridos;
        $vehiculo->categoria_vehiculo_id = $request->categoria_vehiculo_id;
        $vehiculo->color = $request->color;
        $vehiculo->version = $request->version;
        $vehiculo->clase_id = $request->clase_id;

        if ($request->has('prox_servicio')) {
            $vehiculo->prox_servicio = $request->prox_servicio;
        }
        if ($request->has('cant_combustible')) {
            $vehiculo->cant_combustible = $request->cant_combustible;
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
            DB::beginTransaction();
            for ($i = 0; $i < count($request->tarifas_apollo); $i++) {
                try {
                    $tarifa = new TarifasApollo();
                    if (isset($request->tarifas_apollo[$i]->id) && $request->tarifas_apollo[$i]->id > 0) {
                        $tarifa = TarifasApollo::where('id', $request->tarifas_apollo[$i]->id)->first();
                    }

                    $tarifa->frecuencia = $request->tarifas_apollo[$i]['frecuencia'];
                    $tarifa->frecuencia_ref = $request->tarifas_apollo[$i]['frecuencia_ref'];
                    $tarifa->activo = $request->tarifas_apollo[$i]['activo'];
                    $tarifa->modelo = $request->tarifas_apollo[$i]['modelo'];
                    $tarifa->modelo_id = isset($request->tarifas_apollo[$i]['modelo_id']) ? $request->tarifas_apollo[$i]['modelo_id'] : $vehiculo->id;
                    $tarifa->precio_base = $request->tarifas_apollo[$i]['precio_base'];
                    $tarifa->precio_final_editable = $request->tarifas_apollo[$i]['precio_final_editable'];
                    $tarifa->ap_descuento = $request->tarifas_apollo[$i]['ap_descuento'];
                    $tarifa->valor_descuento = $request->tarifas_apollo[$i]['valor_descuento'];
                    $tarifa->descuento = $request->tarifas_apollo[$i]['descuento'];
                    $tarifa->precio_final = $request->tarifas_apollo[$i]['precio_final'];
                    $tarifa->required = $request->tarifas_apollo[$i]['required'];
                    if ($tarifa->save()) {
                        DB::commit();
                    } else {
                        DB::rollBack();
                    }
                } catch (\Exception $e) {
                    Log::debug($e);
                    DB::rollBack();
                }

            }
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
        $vehiculos->load('marca', 'categoria');

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
        $vehiculos = Vehiculos::orderBy('id', 'ASC')->where('activo', true)
        ->with(['tarifas' => function($q) {
            $q->orderBy('id', 'DESC')->limit(4);
         }])
        ->get();
        $vehiculos->load('marca', 'categoria');


        $_vehiculos = [];

        // for ($i = 0; $i < count($vehiculos); $i++) {
        //     for ($j = 0; $j < count($vehiculos[$i]['tarifas']); $j++) {
        //         if ($j > 3) {
        //             $vehiculos[$i]['tarifas'][$j] = null;
        //             continue;
        //         }
        //         $vehiculos[$i]['tarifas'][$j] = $vehiculos[$i]['tarifas'][$j];

        //     }
        // }

        for ($i = 0; $i < count($vehiculos); $i++) {
            array_push($_vehiculos, [
                'id' => $vehiculos[$i]->id,
                'codigo' => $vehiculos[$i]->codigo,
                'modelo' => $vehiculos[$i]->modelo,
                'modelo_ano' => $vehiculos[$i]->modelo_ano,
                'marca' => $vehiculos[$i]->marca->marca,
                'color' => $vehiculos[$i]->color,
                'placas' => $vehiculos[$i]->placas,
                'num_serie' => $vehiculos[$i]->num_serie
            ]);
        }

        return response()->json([
            'ok' => true,
            'data' => $_vehiculos,
            'fullData' => $vehiculos
        ], JsonResponse::OK);
    }
}
