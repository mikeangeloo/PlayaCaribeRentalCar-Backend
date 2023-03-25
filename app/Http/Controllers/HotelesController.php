<?php

namespace App\Http\Controllers;

use App\Enums\JsonResponse;
use App\Helpers\CommonHelper;
use App\Models\Hoteles;
use App\Models\TarifasHoteles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class HotelesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hoteles = Hoteles::where('activo', true)->orderBy('id', 'ASC')->get();

        for ($i = 0; $i < count($hoteles); $i++) {
            $totalTariasPorHotel = TarifasHoteles::where('hotel_id', $hoteles[$i]->id)->where('activo', true)->count();
            $hoteles[$i]->tarifas = TarifasHoteles::where('hotel_id', $hoteles[$i]->id)->latest()->take($totalTariasPorHotel)->orderBy('id', 'ASC')->get();
            $hoteles[$i]->tarifas->load(['tarifas_apollo' => function($q) {
                $q->where('modelo', 'tarifas_hoteles')
                ->orderBy('id', 'ASC');
            }]);
         }

         $hoteles->load('tipo_externo');

        return response()->json([
            'ok' => true,
            'hoteles' => $hoteles
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
        $validateData = Hoteles::validateBeforeSave($request->all());

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $hotel = new Hoteles();
        $hotel->nombre = $request->nombre;
        $hotel->rfc = $request->rfc;
        $hotel->direccion = $request->direccion;
        $hotel->tel_contacto = $request->tel_contacto;
        $hotel->activo = true;
        $hotel->paga_cupon = $request->paga_cupon;
        $hotel->activar_descuentos = $request->activar_descuentos;
        $hotel->acceso_externo = $request->acceso_externo;
        $hotel->tipo_id = $request->tipo_id;

        if ($hotel->save()) {
            // Guardamos tarifas
            DB::beginTransaction();
            TarifasHoteles::where('hotel_id', $hotel->id)->update(['activo', false]);
            for ($i = 0; $i < count($request->tarifas_hotel); $i++) {
                try {
                    $tarifa = new TarifasHoteles();
                    if (isset($request->tarifas_hotel[$i]->id) && $request->tarifas_hotel[$i]->id > 0) {
                        $tarifa = TarifasHoteles::where('id', $request->tarifas_hotel[$i]->id)->first();
                    }

                    $tarifa->hotel_id = $request->tarifas_hotel[$i]['hotel_id'];
                    $tarifa->activo = $request->tarifas_hotel[$i]['activo'];
                    $tarifa->clase_id = $request->tarifas_hotel[$i]['clase_id'];
                    $tarifa->clase = $request->tarifas_hotel[$i]['clase'];
                    $tarifa->precio_renta = $request->tarifas_hotel[$i]['precio_renta'];

                    if ($tarifa->save()) {
                        DB::commit();
                    } else {
                        DB::rollBack();
                    }
                } catch (\Exception $e) {
                    Log::debug($e);
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Algo salio mal, intente nuevamente']
                    ], JsonResponse::BAD_REQUEST);
                }

            }

            return response()->json([
                'ok' => true,
                'message' => 'Hotel registrado correctamente'
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
        $totalTariasPorHotel = TarifasHoteles::where('hotel_id', $id)->where('activo', true)->count();

        $hotel = Hoteles::where('id', $id)->first();
        $hotel->tarifas = TarifasHoteles::where('hotel_id', $hotel->id)->latest()->take($totalTariasPorHotel)->orderBy('id', 'ASC')->get();
        $hotel->tarifas->load(['tarifas_apollo' => function($q) {
            $q->where('modelo', 'tarifas_hoteles')
            ->orderBy('id', 'ASC');
        }]);

        if (!$hotel) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $hotel->load('tipo_externo');

        return response()->json([
            'ok' => true,
            'hotel' => $hotel
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
        $validateData = Hoteles::validateBeforeSave($request->all(), true);

        if ($validateData !== true) {
            return response()->json([
                'ok' => false,
                'errors' => $validateData
            ], JsonResponse::BAD_REQUEST);
        }

        $hotel = Hoteles::where('id', $id)->first();
        if (!$hotel) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }
        $hotel->nombre = $request->nombre;
        $hotel->rfc = $request->rfc;
        $hotel->direccion = $request->direccion;
        $hotel->tel_contacto = $request->tel_contacto;
        $hotel->activo = true;
        $hotel->paga_cupon = $request->paga_cupon;
        $hotel->activar_descuentos = $request->activar_descuentos;
        $hotel->acceso_externo = $request->acceso_externo;
        $hotel->tipo_id = $request->tipo_id;

        if ($hotel->save()) {
            // Guardamos tarifas
            DB::beginTransaction();

            for ($i = 0; $i < count($request->tarifas_hotel); $i++) {
                try {
                    if (isset($request->tarifas_hotel[$i]['id']) && $request->tarifas_hotel[$i]['id'] > 0) {
                        $tarifa = TarifasHoteles::where('id', $request->tarifas_hotel[$i]['id'])->first();
                    } else {
                        $tarifa = new TarifasHoteles();
                    }

                    $tarifa->hotel_id = $request->tarifas_hotel[$i]['hotel_id'];
                    $tarifa->activo = $request->tarifas_hotel[$i]['activo'];
                    $tarifa->clase_id = $request->tarifas_hotel[$i]['clase_id'];
                    $tarifa->clase = $request->tarifas_hotel[$i]['clase'];
                    $tarifa->precio_renta = $request->tarifas_hotel[$i]['precio_renta'];

                    if ($tarifa->save()) {
                        // Sincronizamos con TarifasApollo
                        $res = CommonHelper::syncWithTarifasApollo('tarifas_hoteles', null, false, $hotel->activar_descuentos);
                        if ($res !== true) {
                            DB::rollBack();
                            return response()->json([
                                'ok' => false,
                                'errors' => ['Algo salio mal, intente nuevamente']
                            ], JsonResponse::BAD_REQUEST);
                        }
                        DB::commit();
                    } else {
                        DB::rollBack();
                    }
                } catch (\Exception $e) {
                    Log::debug($e);
                    DB::rollBack();
                    return response()->json([
                        'ok' => false,
                        'errors' => ['Algo salio mal, intente nuevamente']
                    ], JsonResponse::BAD_REQUEST);
                }

            }
            return response()->json([
                'ok' => true,
                'message' => 'Hotel actualizado correctamente'
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

        $hotel = Hoteles::where('id', $id)->first();

        if (!$hotel) {
            return response()->json([
                'ok' => false,
                'errors' => ['No se encontro la información solicitada']
            ], JsonResponse::BAD_REQUEST);
        }

        $hotel->activo = false;

        if ($hotel->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Hotel dado de baja correctamente'
            ], JsonResponse::OK);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => ['Algo salio mal, intente nuevamente']
            ], JsonResponse::BAD_REQUEST);
        }
    }

    public function getAll(Request $request) {
        $hoteles = Hoteles::orderBy('id', 'ASC')->get();

        for ($i = 0; $i < count($hoteles); $i++) {
            $totalTariasPorHotel = TarifasHoteles::where('hotel_id', $hoteles[$i]->id)->where('activo', true)->count();
            $hoteles[$i]->tarifas = TarifasHoteles::where('hotel_id', $hoteles[$i]->id)->latest()->take($totalTariasPorHotel)->orderBy('id', 'ASC')->get();
            $hoteles[$i]->tarifas->load(['tarifas_apollo' => function($q) {
                $q->where('modelo', 'tarifas_hoteles')
                ->orderBy('id', 'ASC');
            }]);
         }

        $hoteles->load('tipo_externo');

        return response()->json([
            'ok' => true,
            'hoteles' => $hoteles
        ], JsonResponse::OK);
    }

    public function enable($id) {
        $hotel = Hoteles::where('id', $id)->first();
        if (!$hotel) {
            return response()->json([
                'ok' => false,
                'errors' => ['No hay registros']
            ], JsonResponse::BAD_REQUEST);
        }

        if ($hotel->activo === 1 || $hotel->activo == true) {
            return response()->json([
                'ok' => false,
                'errors' => ['El registro ya fue activado']
            ], JsonResponse::BAD_REQUEST);
        }

        $hotel->activo = true;

        if ($hotel->save()) {
            return response()->json([
                'ok' => true,
                'message' => 'Registro habilitado correctamente'
            ], JsonResponse::OK);
        }
    }
}
