<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresas::where('activo', true)->orderBy('id', 'ASC')->get();
        $empresas->load('comisionistas');

        return response()->json([
            'ok' => true,
            'empresas' => $empresas
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
        $validateData = Empresas::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $empresa = new Empresas();
        $empresa->nombre = $request->nombre;
        $empresa->rfc = $request->rfc;
        $empresa->direccion = $request->direccion;
        $empresa->tel_contacto = $request->tel_contacto;
        $empresa->activo = true;
        $empresa->paga_cupon = $request->paga_cupon;

        if ($empresa->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Empresa registrada correctamente'
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
        $empresa = Empresas::where('id', $id)->first();
        $empresa->load('comisionistas');

        if (!$empresa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'empresa' => $empresa
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
        $validateData = Empresas::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $empresa = Empresas::where('id', $id)->first();
        if (!$empresa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $empresa->nombre = $request->nombre;
        $empresa->rfc = $request->rfc;
        $empresa->direccion = $request->direccion;
        $empresa->tel_contacto = $request->tel_contacto;
        $empresa->activo = true;
        $empresa->paga_cupon = $request->paga_cupon;

        if ($empresa->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Empresa actualizada correctamente'
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

        $empresa = Empresas::where('id', $id)->first();

        if (!$empresa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $empresa->activo = false;

        if ($empresa->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Empresa dada de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $empresas = Empresas::orderBy('id', 'ASC')->get();
        $empresas->load('comisionistas');

        return response()->json([
            'ok' => true,
            'empresas' => $empresas
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $empresa = Empresas::where('id', $id)->first();
        if (!$empresa) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($empresa->activo === 1 || $empresa->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $empresa->activo = true;

        if ($empresa->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
