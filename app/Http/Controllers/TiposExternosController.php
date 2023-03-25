<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Models\TipoExternos;
use Illuminate\Http\Request;

class TiposExternosController extends Controller
{
    public function index() {
        $tiposExternos = TipoExternos::where('activo', true)->get();

        return response()->json([
            'ok' => true,
            'data' => $tiposExternos
        ], JsonResponse::OK);
    }
}
