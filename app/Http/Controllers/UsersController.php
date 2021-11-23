<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::where('activo', true)->orderBy('id', 'DESC')->get();

        return response()->json([
            'ok' => true,
            'usuarios' => $usuarios
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
        $validateData = User::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $user = new User();

        $user->area_trabajo_id = $request->area_trabajo_id;
        $user->role_id = $request->role_id;
        $user->nombre = $request->nombre;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->password = Hash::make($request->password);
        $user->username = $request->username;
        $user->sucursal_id = $request->sucursal_id;
        //$user->empresa_id = $request->empresa_id;


        if ($user->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario registrado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al registrar el usuario, intente nuevamente']
            ], JsonResponse::OK);
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
        $usuario = User::where('id', $id)->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        return response()->json([
            'ok' => true,
            'usuario' => $usuario
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
        $validateData = User::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se pudo encontrar el usuario']
            ], JsonResponse::BAD_REQUEST);
        }

        $user->area_trabajo_id = $request->area_trabajo_id;
        $user->role_id = $request->role_id;
        $user->nombre = $request->nombre;
        $user->apellidos = $request->apellidos;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('username')) {
            $user->username = $request->username;
        }

        $user->sucursal_id = $request->sucursal_id;
        //$user->empresa_id = $request->empresa_id;


        if ($user->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario actualizado correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal al registrar el usuario, intente nuevamente']
            ], JsonResponse::OK);
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

        $usuario = User::where('id', $id)->first();

        if (!$usuario) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $usuario->activo = false;

        if ($usuario->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Usuario dado de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $user = $request->user;
        $users = User::orderBy('id', 'DESC')->where('id', '!=', $user->id)->get();
        $users->load('area_trabajo', 'rol', 'sucursal');

        return response()->json([
            'ok' => true,
            'usuarios' => $users
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $data = User::where('id', $id)->first();
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
