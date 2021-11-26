<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Vehiculos;
use Illuminate\Http\Request;

class VehiculosController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehiculos = Vehiculos::where('activo', true)->orderBy('id', 'DESC')->get();

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
        $vehiculo->marca_id = $request->marca_id;
        $vehiculo->modelo_id = $request->modelo_id;
        $vehiculo->color_id = $request->color_id;
        $vehiculo->no_placas = $request->no_placas;
        $vehiculo->activo = true;
        $vehiculo->nombre = $request->nombre;
        $vehiculo->version = $request->version;
        $vehiculo->precio_venta = $request->precio_venta;
        $vehiculo->cap_tanque = $request->cap_tanque;

        if ($vehiculo->save()) {
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
        $vehiculo->marca_id = $request->marca_id;
        $vehiculo->modelo_id = $request->modelo_id;
        $vehiculo->color_id = $request->color_id;
        $vehiculo->no_placas = $request->no_placas;
        $vehiculo->activo = true;
        $vehiculo->nombre = $request->nombre;
        $vehiculo->version = $request->version;
        $vehiculo->precio_venta = $request->precio_venta;
        $vehiculo->cap_tanque = $request->cap_tanque;

        if ($vehiculo->save()) {
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
        $vehiculos = Vehiculos::orderBy('id', 'DESC')->get();
        $vehiculos->load('modelo', 'marca', 'color');

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
}
