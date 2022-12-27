<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\CategoriasVehiculos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
        $categoria->categoria = $request['categoria']['categoria'];
        $categoria->imagen_url = $request['layout']['fileName'];
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

        $getCategoriaDoc = self::getCategoriaDoc($categoria->imagen_url);

        $layout = null;

        if($getCategoriaDoc->ok == true && isset($getCategoriaDoc->data[0])) {
            $layout = $getCategoriaDoc->data[0];
        }

        return response()->json([
            'ok' => true,
            'categoria' => $categoria,
            'layout'=> $layout
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
        $categoria->categoria = $request['categoria']['categoria'];
        if($request->has('layout') && isset($request->layout)) {
            $categoria->imagen_url = $request['layout']['fileName'];
        }


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

    private static function getCategoriaDoc($nombre_archivo) {
        $response = [];

        if ($nombre_archivo) {
            $query =  DB::table('modelos_docs')
            ->where('modelo', 'categorias_vehiculos')
            ->where('nombre_archivo', '=', $nombre_archivo)
            ->where('estatus', '=', 1)
            ->orderBy('posicion', 'ASC');

            $validInDB = $query->get();

            $files = $validInDB;

            if ($files && count($files) > 0) {

                for ($i = 0; $i < count($files); $i++) {
                    $dirFile = $files[$i]->modelo_id.'/'.'layout'.'/'.$files[$i]->nombre_archivo;

                    if (Storage::disk("categorias_vehiculos")->exists($dirFile) === false) {
                        continue;
                    }
                    $fileData = Storage::disk("categorias_vehiculos")->get($dirFile);

                    $encodedFile = base64_encode($fileData);

                    $mimeType = Storage::disk("categorias_vehiculos")->mimeType($dirFile);

                    array_push($response, [
                        'etiqueta' => $files[$i]->etiqueta,
                        'position' => $files[$i]->posicion,
                        'success' => true,
                        'file_id' => $files[$i]->id,
                        'doc_type' => $files[$i]->tipo_archivo,
                        'model' => $files[$i]->modelo,
                        'model_id' => $files[$i]->modelo_id,
                        //'model_id_value' => $request->model_id_value,
                        'mime_type' => $mimeType,
                        'file' => 'data:'.$mimeType.';base64,'.$encodedFile
                    ]);
                }
            }



            return (object)  ['ok' => true, 'total' => count($response), 'data' => $response];
        } else  {
            return (object)  ['ok' => false, 'total' => 0, 'data' => null];
        }


    }
}
