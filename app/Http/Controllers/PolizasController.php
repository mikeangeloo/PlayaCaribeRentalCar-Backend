<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Polizas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolizasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $polizas = Polizas::where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'data' => $polizas
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
        $validateData = Polizas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        //dd($request->all());

        $poliza = new Polizas();
        $poliza->aseguradora = $request->aseguradora;
        $poliza->no_poliza = $request->no_poliza;
        $poliza->tipo_poliza = $request->tipo_poliza;
        $poliza->tel_contacto = $request->tel_contacto;
        $poliza->titular = $request->titular;
        $poliza->fecha_inicio = $request->fecha_inicio;
        $poliza->fecha_fin = $request->fecha_fin;

        DB::beginTransaction();
        try {
            if ($poliza->save()) {
                DB::commit();
                return response()->json([
                    'ok' => true,
                    'data' => [
                        'poliza_id' => $poliza->id,
                        'no_poliza' => $poliza->no_poliza
                    ]
                ], JsonResponse::OK);
            }
        } catch(\Throwable $e) {
            DB::rollBack();
            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al guardar la información de la poliza.']
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
        $poliza = Polizas::where('id', $id)->first();

        if (!$poliza) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'data' => $poliza
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
        $validateData = Polizas::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $poliza = Polizas::where('id', $id)->first();
        if (!$poliza) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $poliza->aseguradora = $request->aseguradora;
        $poliza->no_poliza = $request->no_poliza;
        $poliza->tipo_poliza = $request->tipo_poliza;
        $poliza->tel_contacto = $request->tel_contacto;
        $poliza->titular = $request->titular;
        $poliza->fecha_inicio = $request->fecha_inicio;
        $poliza->fecha_fin = $request->fecha_fin;
        $poliza->activo = true;

        DB::beginTransaction();
        try {
            if ($poliza->save()) {
                DB::commit();
                return response()->json([
                    'ok' => true,
                    'message' => 'Información actualizada correctamente.',
                    'data' => [
                        'poliza_id' => $poliza->id,
                        'no_poliza' => $poliza->no_poliza
                    ]
                ], JsonResponse::OK);
            }
        } catch(\Throwable $e) {
            DB::rollBack();
            Log::debug($e);
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al guardar la información de la poliza.']
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

        $poliza = Polizas::where('id', $id)->first();

        if (!$poliza) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $poliza->activo = false;

        if ($poliza->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Poliza dada de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $polizas = Polizas::orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'data' => $polizas
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $data = Polizas::where('id', $id)->first();
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
