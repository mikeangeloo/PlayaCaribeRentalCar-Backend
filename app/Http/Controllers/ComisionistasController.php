<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Comisionistas;
use Illuminate\Http\Request;

class ComisionistasController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comisionistas = Comisionistas::where('activo', true)->orderBy('id', 'ASC')->get();
        $comisionistas->load('comisionista');

        return response()->json([
            'ok' => true,
            'comisionistas' => $comisionistas
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
        $validateData = Comisionistas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $comisionista = new Comisionistas();
        $comisionista->nombre = $request->nombre;
        $comisionista->apellidos = $request->apellidos;
        $comisionista->nombre_empresa = $request->nombre_empresa;
        $comisionista->empresa_id = $request->empresa_id;
        $comisionista->tel_contacto = $request->tel_contacto;
        $comisionista->email_contacto = $request->email_contacto;
        $comisionista->activo = true;
        $comisionista->comisiones_pactadas = $request->comisiones_pactadas;

        if ($comisionista->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Comisionista registrado correctamente'
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
        $comisionista = Comisionistas::where('id', $id)->first();
        $comisionista->load('empresa');

        if (!$comisionista) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'comisionista' => $comisionista
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
        $validateData = Comisionistas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $comisionista = Comisionistas::where('id', $id)->first();
        if (!$comisionista) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $comisionista->nombre = $request->nombre;
        $comisionista->apellidos = $request->apellidos;
        $comisionista->nombre_empresa = $request->nombre_empresa;
        $comisionista->empresa_id = $request->empresa_id;
        $comisionista->tel_contacto = $request->tel_contacto;
        $comisionista->email_contacto = $request->email_contacto;
        //$comisionista->activo = true;
        $comisionista->comisiones_pactadas = $request->comisiones_pactadas;

        if ($comisionista->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Comisionista actualizado correctamente'
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

        $comisionista = Comisionistas::where('id', $id)->first();

        if (!$comisionista) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $comisionista->activo = false;

        if ($comisionista->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Comisionista dado de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $comisionistas = Comisionistas::orderBy('id', 'ASC')->get();
        $comisionistas->load('empresa');

        return response()->json([
            'ok' => true,
            'comisionistas' => $comisionistas
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $comisionista = Comisionistas::where('id', $id)->first();
        if (!$comisionista) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($comisionista->activo === 1 || $comisionista->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $comisionista->activo = true;

        if ($comisionista->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
