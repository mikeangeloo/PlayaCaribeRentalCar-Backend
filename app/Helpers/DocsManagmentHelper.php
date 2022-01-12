<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocsTypeEnum
{
    const LICENCE = 'licencia_conducir';
    const INE = 'ine';
}

class DocsValidParams
{
    const VALIDMODELS = ['clientes_docs'];
    const VALIDMODELIDS = ['cliente_id'];
    const VALIDTIPODOCS = ['licencia_conducir', 'ine'];
}

class DocsStatusEnum
{
    const ACTIVO = 1;
    const INACTIVO = 0;
    const BORRADO = -1;
}

class DocsManagmentHelper
{
    public static function save(Request $request) {

        $getFiles = $request->allFiles();
        if (!$getFiles) {
            return (object) ['ok' => false, 'errors' => ['Debe existir un archivo para procesar']];
        }
        if ($getFiles && count($getFiles) === 0) {
            return (object) ['ok' => false, 'errors' => ['Debe existir un archivo para procesar']];
        }

        if (count($getFiles['files']) > 1) {
            $validate = self::validateFiles($request);

            if ($validate->ok === false) {
                return $validate;
            }

            return self::storeFiles($request);
        }
    }

    public static function getAllDocs(Request $request) {

        $validate = self::validateBeforeGet($request);

        if ($validate->ok === false) {
            return $validate;
        }
        $response = [];
        //dd($validate);
        if ($request->has('id') && $request->id > 0 && $validate->data->id) {
            $dirFile = $request->model_id_value.'/'.$request->tipo.'/'.$validate->data->nombre_archivo;

            if (Storage::disk($request->model)->exists($dirFile) === false) {
                return (object) ['ok' => false, 'errors' => ['El archivo ya no esta disponible']];
            }

            $fileData = Storage::disk($request->model)->get($dirFile);
            $encodedFile = base64_encode($fileData);
            $mimeType = Storage::disk($request->model)->mimeType($dirFile);

            array_push($response, [
                'mime_type' => $mimeType,
                'file' => $encodedFile
            ]);

            return (object) ['ok' => true, 'data' => $response];

        } else {
            $dir = $request->model_id_value.'/'.$request->tipo;
        }

        $files = Storage::disk($request->model)->files($dir);


        if (!$files) {
            return (object) ['ok' => false, 'errors' => ['El directorio esta vacio']];
        }

        if ($files && count($files) > 0) {

            for ($i = 0; $i < count($files); $i++) {
                $fileData = Storage::disk($request->model)->get($files[$i]);
                $encodedFile = base64_encode($fileData);
                $mimeType = Storage::disk($request->model)->mimeType($files[$i]);

                array_push($response, [
                    'mime_type' => $mimeType,
                    'file' => $encodedFile
                ]);
            }
        }

        return (object) ['ok' => true, 'total' => count($files), 'data' => $response];
    }

    public function replaceFile(Request $request) {
        $oldFile = null;
        $id = null;

        if ($request->has('id')) {
            $data = DB::table($request->model)->where('id', '=', $request->id)->first();
            $id = $request->id;

            if ($data) {
                $oldFile = $data->nombre_archivo;
            }
        }

        if (isset($oldFile)) {
            Storage::disk($request->model)->delete($dir.'/'.$oldFile);
        }
    }


    //#region PRIVATE FUNCTIONS

    private static function validateFiles(Request $request) {
        $validateData = Validator::make($request->all(), [
            'tipo' => 'required|string',
            'model' => 'required|string',
            'model_id' => 'required|string',
            'model_id_value' => 'required|numeric',
            'files.*' => 'required|mimes:png,jpg,jpeg,pdf|max:4096',
        ]);

        if ($validateData->fails()) {
            return (object) ['ok' => false, 'errors' => $validateData->errors()->all()];
        }

        $validModels = DocsValidParams::VALIDMODELS;
        $validModelIds = DocsValidParams::VALIDMODELIDS;
        $validTipoDocs = DocsValidParams::VALIDTIPODOCS;

        if (in_array($request->model, $validModels) === false) {
            return (object) ['ok' => false, 'errors' => ['El modelo:'. $request->model. ' es invalido']];
        }

        if (in_array($request->model_id, $validModelIds) === false) {
            return (object) ['ok' => false, 'errors' => ['El modelo id: '. $request->model_id. ' es invalido']];
        }

        if (in_array($request->tipo, $validTipoDocs) === false) {
            return (object) ['ok' => false, 'errors' => ['El tipo de documento es invalido', ['Tipos válidos' => $validTipoDocs]]];
        }

        return (object) ['ok' => true];

    }

    private static function validateBeforeGet(Request $request) {
        $validateData = Validator::make($request->all(), [
            'tipo' => 'required|string',
            'model' => 'required|string',
            'model_id' => 'required|string',
            'model_id_value' => 'required|numeric',
            'id' => 'nullable|numeric'
        ]);

        if ($validateData->fails()) {
            return (object) ['ok' => false, 'errors' => $validateData->errors()->all()];
        }

        $validModels = DocsValidParams::VALIDMODELS;
        $validModelIds = DocsValidParams::VALIDMODELIDS;
        $validTipoDocs = DocsValidParams::VALIDTIPODOCS;

        if (in_array($request->model, $validModels) === false) {
            return (object) ['ok' => false, 'errors' => ['El modelo:'. $request->model. ' es invalido']];
        }

        if (in_array($request->model_id, $validModelIds) === false) {
            return (object) ['ok' => false, 'errors' => ['El modelo id: '. $request->model_id. ' es invalido']];
        }

        if (in_array($request->tipo, $validTipoDocs) === false) {
            return (object) ['ok' => false, 'errors' => ['El tipo de documento es invalido', ['Tipos válidos' => $validTipoDocs]]];
        }

        if ($request->has('id') && $request->id > 0) {
            $data =  DB::table($request->model)
            ->where($request->model_id, '=', $request->model_id_value)
            ->where('estatus', '=', DocsStatusEnum::ACTIVO)
            ->where('id', '=', $request->id)
            ->first();

            if (!$data) {
                return (object) ['ok' => false, 'errors' => ['No se encontro información para mostrar']];
            }
            return (object) ['ok' => true, 'data' => $data];
        }

        $validInDB = DB::table($request->model)
                    ->where($request->model_id, '=', $request->model_id_value)
                    ->where('estatus', '=', DocsStatusEnum::ACTIVO)
                    ->get();

        if ($validInDB && count($validInDB) === 0) {
            return (object) ['ok' => false, 'errors' => ['No existe información para mostrar']];
        } else if (!$validInDB) {
            return (object) ['ok' => false, 'errors' => ['No existe información para mostrar']];
        }

        return (object) ['ok' => true];
    }

    private static function storeFiles(Request $request) {
        $dir = null;
        $savedStorage = null;
        $fileName = null;

        $errorsDisk = 0;
        $errorsDB = 0;
        $_response = [];

        //dd(($request->file('files')));

        // Guardamos archivo
        for ($i = 0; $i < count($request->file('files')); $i++) {

            $dir = $request->model_id_value.'/'.$request->tipo;
            $rand = rand(2, 100);

            $validImageMimeTypes = ['image/png','image/jpg','image/jpeg'];

            if (in_array($request->file('files')[$i]->getClientMimeType(), $validImageMimeTypes)) {
                $fileName = Carbon::now()->unix().$rand.'.png';
                $img = \Image::make($request->file('files')[$i])->resize(900, null, function ($constraint) { $constraint->aspectRatio(); } );
                $savedStorage = Storage::disk($request->model)->put($dir.'/'.$fileName, (string) $img->encode('png'));
            } else {
                $fileName = Carbon::now()->unix().$rand.'.'.$request->file('files')[$i]->getClientOriginalExtension();
                $savedStorage = Storage::disk($request->model)->putFileAs($dir, $request->file('files')[$i], $fileName);
            }

            if ($savedStorage === false ) {
                $errorsDisk ++;
            }

            DB::beginTransaction();
            $payload = [
                'tipo' => $request->tipo,
                'nombre_archivo' => $fileName,
                'estatus' => DocsStatusEnuM::ACTIVO,
                $request->model_id => $request->model_id_value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $savedId = $saved = DB::table($request['model'])->insertGetId(
                $payload
            );

            if ($savedId > 0) {
                DB::commit();
                array_push($_response, [
                    'position' => $i,
                    'error' => false,
                    'file_id' => $savedId,
                    'tipo' => $request->tipo,
                    'model' => $request->model,
                    'model_id' => $request->model_id,
                    'model_id_value' => $request->model_id_value
                ]);
            } else {
                DB::rollBack();
                $errorsDB ++;
                array_push($_response, [
                    'position' => $i,
                    'error' => true,
                    'file_id' => $savedId,
                    'model' => $request->model,
                    'model_id' => $request->model_id,
                    'model_id_value' => $request->model_id_value
                ]);
            }
        }

        if ($errorsDisk > 0) {
            return (object) ['ok' => true, 'errors' => ['Algo salio mal al guardar alguno de los archivos en disco.', 'Ocurrieron '. $errorsDisk.' errores'], 'payload' => $_response];
        } else if ($errorsDB > 0) {
            return (object) ['ok' => true, 'errors' =>  ['Algo salio mal al guardar alguno de los archivos en la base de datos.', 'Ocurrieron '. $errorsDB.' errores'], 'payload' => $_response];
        }

        return (object) ['ok' => true, 'message' => 'Archivos almacenados correctamente', 'payload' => $_response];

    }

    //#endregion
}
