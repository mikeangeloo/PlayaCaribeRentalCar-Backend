<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Helpers\DocsManagmentHelper;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function storeFiles(Request $request) {
        $store = DocsManagmentHelper::save($request);
        if ($store->ok === false) {
            return response()->json($store, JsonResponse::BAD_REQUEST);
        } else {
            return response()->json($store, JsonResponse::OK);
        }
    }

    public function getActiveFiles(Request $request) {
        $files = DocsManagmentHelper::getAllDocs($request);
        //return $files;
        if ($files->ok === false) {
            return response()->json($files, JsonResponse::BAD_REQUEST);
        } else {
            return response()->json($files, JsonResponse::OK);
        }
    }
}
