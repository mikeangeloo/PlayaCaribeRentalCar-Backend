<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Helpers\CommonHelper;
use App\Models\TarifasApollo;
use App\Models\TarifasApolloConf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarifasApolloConfController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tarifasQ = TarifasApolloConf::where('activo', true)->orderBy('id', 'ASC');
        if ($request->has('model')) {
            $tarifasQ->where('modelo', $request->model);
        }

        $tarifas = $tarifasQ->get();


        return response()->json([
            'ok' => true,
            'data' => $tarifas
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
        $validateData = TarifasApolloConf::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $tarifa = new TarifasApolloConf();
        $tarifa->modelo = $request->modelo;
        $tarifa->frecuencia = $request->frecuencia;
        $tarifa->frecuencia_ref = $request->frecuencia_ref;
        $tarifa->ap_descuento = $request->ap_descuento;
        $tarifa->valor_descuento = $request->valor_descuento;
        $tarifa->activo = 1;
        $tarifa->precio_final_editable = $request->precio_final_editable;
        $tarifa->required = $request->required;

        if ($tarifa->save()) {
            if ($request->has('restartAll')) {
                $res = CommonHelper::syncWithTarifasApollo($request->modelo, $tarifa, $request->restartAll);
            } else {
                $res = CommonHelper::syncWithTarifasApollo($request->modelo, $tarifa);
            }

            if ($res !== true) {
                return response()->json([
                    'ok' => false,
                    'errors' => ['Algo salio mal, intente nuevamente']
                ], JsonResponse::BAD_REQUEST);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Dato registrado correctamente'
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
        $tarifa = TarifasApolloConf::where('id', $id)->first();

        if (!$tarifa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'data' => $tarifa
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
        $validateData = TarifasApolloConf::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $tarifa = TarifasApolloConf::where('id', $id)->first();
        if (!$tarifa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $tarifa->modelo = $request->modelo;
        $tarifa->frecuencia = $request->frecuencia;
        $tarifa->frecuencia_ref = $request->frecuencia_ref;
        $tarifa->ap_descuento = $request->ap_descuento;
        $tarifa->valor_descuento = $request->valor_descuento;
        $tarifa->precio_final_editable = $request->precio_final_editable;
        $tarifa->required = $request->required;



        if ($tarifa->save()) {
            if ($request->has('restartAll')) {
                $res = CommonHelper::syncWithTarifasApollo($request->modelo, $tarifa, $request->restartAll);
            } else {
                $res = CommonHelper::syncWithTarifasApollo($request->modelo, $tarifa);
            }


            if ($res !== true) {
                return response()->json([
                    'ok' => false,
                    'errors' => ['Algo salio mal, intente nuevamente']
                ], JsonResponse::BAD_REQUEST);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Información actualizada correctamente'
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

        $tarifa = TarifasApolloConf::where('id', $id)->first();

        if (!$tarifa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $tarifa->activo = false;

        if ($tarifa->save()) {
            // colocamos como inactivo todas las tarifas apollo encontradas
            TarifasApollo::where('tarifa_apollo_conf_id', $tarifa->id)->update(['activo' => false]);
            return response()->json([
                'ok' => true,
                'message' => 'Configuración dada de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $tarifas = TarifasApolloConf::orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'data' => $tarifas
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $data = TarifasApolloConf::where('id', $id)->first();
        if (!$data) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($data->activo === 1 || $data->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $data->activo = true;

        if ($data->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
