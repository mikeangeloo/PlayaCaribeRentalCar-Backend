<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Tarjetas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TarjetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarjetas = Tarjetas::where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'tarjetas' => $tarjetas
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
        $validateData = Tarjetas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $card = Tarjetas::where('c_cn1', $request->c_cn1)
                ->where('c_cn2', $request->c_cn2)
                ->where('c_cn3', $request->c_cn3)
                ->where('c_cn4', $request->c_cn4)
                ->where('c_code', $request->c_code)
                ->where('cliente_id', $request->cliente_id)
                ->first();

        if (!$card) {
            $card = new Tarjetas();
        }

        $card->cliente_id = $request->cliente_id;
        $card->c_name = $request->c_name;
        $card->c_cn1 = $request->c_cn1;
        $card->c_cn2 = $request->c_cn2;
        $card->c_cn3 = $request->c_cn3;
        $card->c_cn4 = $request->c_cn4;
        $card->c_month = $request->c_month;
        $card->c_year = $request->c_year;
        $card->c_code = $request->c_code;
        $card->c_type = $request->c_type;
        $card->c_charge_method = $request->c_charge_method;
        $card->date_reg = Carbon::now();

        if ($card->save()) {
            return response()->json([
                'ok' => true,
                'card_id' => $card->id,
                'message' => 'La tarjeta fue registrada correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST); return response()->json([
                'ok' => false,
                'errors' => ['Hubo un error al momento de guardar el registro, intente nuevamente']
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
        $tarjeta = Tarjetas::where('id', $id)->first();


        if (!$tarjeta) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'tarjeta' => $tarjeta
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
        $validateData = Tarjetas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $card = Tarjetas::where('id', $id)->first();
        if (!$card) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $card->cliente_id = $request->cliente_id;
        $card->c_name = $request->c_name;
        $card->c_cn1 = $request->c_cn1;
        $card->c_cn2 = $request->c_cn2;
        $card->c_cn3 = $request->c_cn3;
        $card->c_cn4 = $request->c_cn4;
        $card->c_month = $request->c_month;
        $card->c_year = $request->c_year;
        $card->c_code = $request->c_code;
        $card->c_type = $request->c_type;
        $card->c_charge_method = $request->c_charge_method;


        if ($card->save()) {
            return response()->json([
                'ok' => true,
                'card_id' => $card->id,
                'message' => 'Tarjeta actualizada correctamente'
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

        $tarjeta = Tarjetas::where('id', $id)->first();

        if (!$tarjeta) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $tarjeta->activo = false;

        if ($tarjeta->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Tarjeta dada de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $tarjetas = Tarjetas::orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'tarjetas' => $tarjetas
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $tarjeta = Tarjetas::where('id', $id)->first();
        if (!$tarjeta) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($tarjeta->activo === 1 || $tarjeta->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $tarjeta->activo = true;

        if ($tarjeta->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
