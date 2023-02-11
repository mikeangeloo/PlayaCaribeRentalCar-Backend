<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Hoteles extends Model
{
    use HasFactory;
    protected $table = 'hoteles';
    protected $primaryKey = 'id';


    public static function validateBeforeSave($request, $isUpdate = null) {
        $validateData = Validator::make($request, [
            'nombre' => 'required|string',
            'rfc' => 'required',
            'direccion' => 'required|string',
            'tel_contacto' => 'required|string',
            'paga_cupon' => 'required',
            'tarifas_hotel.*.hotel_id' => 'required',
            'tarifas_hotel.*.activo' => 'required',
            'tarifas_hotel.*.clase_id' => 'required',
            'tarifas_hotel.*.clase' => 'required',
            'tarifas_hotel.*.precio_renta' => 'required',
            'tarifas_hotel.*.hotel_id' => 'required',
            'tarifas_hotel.*.hotel_id' => 'nullable',
        ]);

        if (is_null($isUpdate)) {
            $empresa = Hoteles::where('rfc', $request['rfc'])->first();
            if ($empresa) {
                return ['Este RFC ya fue registrado previamente.'];
            }
        }

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }

}

