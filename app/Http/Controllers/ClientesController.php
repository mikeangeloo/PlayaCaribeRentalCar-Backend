<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\Clientes;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Clientes::where('activo', true)->orderBy('id', 'ASC')->get();
        $clientes->load('tarjetas');

        return response()->json([
            'ok' => true,
            'clientes' => $clientes
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
        $validateData = Clientes::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $cliente = new Clientes();
        $cliente->nombre = $request->nombre;
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->num_licencia = $request->num_licencia;
        $cliente->licencia_mes = $request->licencia_mes;
        $cliente->licencia_ano = $request->licencia_ano;
        $cliente->activo = true;


        if ($cliente->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cliente registrado correctamente'
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
        $cliente = Clientes::where('id', $id)->first();


        if (!$cliente) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $cliente->load('tarjetas');
        return response()->json([
            'ok' => true,
            'cliente' => $cliente
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
        $validateData = Clientes::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $cliente = Clientes::where('id', $id)->first();
        if (!$cliente) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $cliente->nombre = $request->nombre;
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->num_licencia = $request->num_licencia;
        $cliente->licencia_mes = $request->licencia_mes;
        $cliente->licencia_ano = $request->licencia_ano;


        if ($cliente->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cliente actualizado correctamente'
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

        $cliente = Clientes::where('id', $id)->first();

        if (!$cliente) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $cliente->activo = false;

        if ($cliente->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cliente dado de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $clientes = Clientes::orderBy('id', 'ASC')->get();
        $clientes->load('tarjetas');

        return response()->json([
            'ok' => true,
            'clientes' => $clientes
        ], JsonResponse::OK);
    }

    public function getList(Request $request) {
        $clientes = Clientes::orderBy('id', 'ASC')->where('activo', true)->get();
        $clientes->makeHidden(['created_at', 'updated_at', 'activo']);
        return response()->json([
            'ok' => true,
            'data' => $clientes,
            'fullData' => $clientes
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $cliente = Clientes::where('id', $id)->first();
        if (!$cliente) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($cliente->activo === 1 || $cliente->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $cliente->activo = true;

        if ($cliente->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
