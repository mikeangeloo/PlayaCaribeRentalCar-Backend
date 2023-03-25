<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Helpers\CommonHelper;
use App\Models\TarifasApollo;
use App\Models\TarifasApolloConf;
use App\Models\TarifasCategorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifasCategoriasController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarifasC = TarifasCategorias::where('activo', true)->orderBy('id', 'ASC')->get();
        $totalTarifasApolloConf = TarifasApolloConf::where('modelo', 'tarifas_categorias')->where('activo', true)->count();
        for ($i = 0; $i < count($tarifasC); $i++) {
           $tarifasC[$i]->tarifas_apollo = TarifasApollo::where('modelo', 'tarifas_categorias')->where('modelo_id', $tarifasC[$i]->id)->latest()->take($totalTarifasApolloConf)->orderBy('id', 'ASC')->get();
        }

        return response()->json([
            'ok' => true,
            'data' => $tarifasC
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
        $validateData = TarifasCategorias::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        DB::beginTransaction();

        $tarifa = new TarifasCategorias();
        $tarifa->categoria = $request->categoria;
        $tarifa->precio_renta = $request->precio_renta;
        $tarifa->activo = 1;

        if ($tarifa->save()) {
            if ($request->has('restartAll')) {
                $res = CommonHelper::syncWithTarifasApollo('tarifas_categorias', $tarifa, $request->restartAll);
            } else {
                $res =  CommonHelper::syncWithTarifasApollo('tarifas_categorias', $tarifa);
            }

            if ($res !== true) {
                DB::rollBack();
                return response()->json([
                    'ok' => false,
                    'errors' => ['Algo salio mal, intente nuevamente']
                ], JsonResponse::BAD_REQUEST);
            }
            DB::commit();
            return response()->json([
                'ok' => true,
                'message' => 'Dato registrado correctamente'
            ], JsonResponse::OK);
        } else {
            DB::rollBack();
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
        $tarifa = TarifasCategorias::where('id', $id)->first();
        $totalTarifasApolloConf = TarifasApolloConf::where('modelo', 'tarifas_categorias')->where('activo', true)->count();
        $tarifa->tarifas = TarifasApollo::where('modelo', 'tarifas_categorias')->where('modelo_id', $tarifa->id)->latest()->take($totalTarifasApolloConf)->orderBy('id', 'ASC')->get();

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
        $validateData = TarifasCategorias::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        DB::beginTransaction();

        $tarifa = TarifasCategorias::where('id', $id)->first();
        if (!$tarifa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $tarifa->categoria = $request->categoria;
        $tarifa->precio_renta = $request->precio_renta;

        if ($tarifa->save()) {

            if ($request->has('restartAll')) {
                $res = CommonHelper::syncWithTarifasApollo('tarifas_categorias', $tarifa, $request->restartAll);
            } else {
                $res =  CommonHelper::syncWithTarifasApollo('tarifas_categorias', $tarifa);
            }

            if ($res !== true) {
                DB::rollBack();
                return response()->json([
                    'ok' => false,
                    'errors' => ['Algo salio mal, intente nuevamente']
                ], JsonResponse::BAD_REQUEST);
            }

            DB::commit();
            return response()->json([
                'ok' => true,
                'message' => 'Información actualizada correctamente'
            ], JsonResponse::OK);
        } else {
            DB::rollBack();
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

        $tarifa = TarifasCategorias::where('id', $id)->first();

        if (!$tarifa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $tarifa->activo = false;

        if ($tarifa->save()) {
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
        $tarifasC = TarifasCategorias::orderBy('id', 'ASC')->get();
        $totalTarifasApolloConf = TarifasApolloConf::where('modelo', 'tarifas_categorias')->where('activo', true)->count();
        for ($i = 0; $i < count($tarifasC); $i++) {
           $tarifasC[$i]->tarifas = TarifasApollo::where('modelo', 'tarifas_categorias')->where('modelo_id', $tarifasC[$i]->id)->latest()->take($totalTarifasApolloConf)->orderBy('id', 'ASC')->get();
        }

        return response()->json([
            'ok' => true,
            'data' => $tarifasC
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $data = TarifasCategorias::where('id', $id)->first();
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

            $res =  CommonHelper::syncWithTarifasApollo('tarifas_categorias', $data);

            if ($res !== true) {
                return response()->json([
                    'ok' => false,
                    'errors' => ['Algo salio mal, intente nuevamente']
                ], JsonResponse::BAD_REQUEST);
            }
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }

}
