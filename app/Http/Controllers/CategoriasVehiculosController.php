<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\CategoriasVehiculos;
use Illuminate\Http\Request;

class CategoriasVehiculosController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = CategoriasVehiculos::where('activo', true)->orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'categorias' => $categorias
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
        $validateData = CategoriasVehiculos::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $categoria = new CategoriasVehiculos();
        $categoria->categoria = $request->categoria;
        $categoria->activo = true;

        if ($categoria->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Categoría registrada correctamente'
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
        $categoria = CategoriasVehiculos::where('id', $id)->first();

        if (!$categoria) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'categoria' => $categoria
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
        $validateData = CategoriasVehiculos::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $categoria = CategoriasVehiculos::where('id', $id)->first();
        if (!$categoria) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $categoria->categoria = $request->categoria;

        if ($categoria->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Categoría actualizada correctamente'
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

        $categoria = CategoriasVehiculos::where('id', $id)->first();

        if (!$categoria) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $categoria->activo = false;

        if ($categoria->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Categoría dada de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $categorias = CategoriasVehiculos::orderBy('id', 'ASC')->get();

        return response()->json([
            'ok' => true,
            'categorias' => $categorias
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $data = CategoriasVehiculos::where('id', $id)->first();
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
