<?php

namespace App\Models;
use App\Casts\EncryptCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Tarjetas extends Model
{
    use HasFactory;
    protected $table = 'tarjetas';
    protected $primaryKey = 'id';

    protected $casts  = [
        'c_name' => EncryptCast::class,
        'c_cn1' => EncryptCast::class,
        'c_cn2' => EncryptCast::class,
        'c_cn3' => EncryptCast::class,
        'c_cn4' => EncryptCast::class,
        'c_month' => EncryptCast::class,
        'c_year' => EncryptCast::class,
        'c_code' => EncryptCast::class,
        'c_type' => EncryptCast::class,
        'c_method' => EncryptCast::class,
    ];

     // mensajes personalizados
     private static $messages = [
        'c_name.required' => 'El titular de la tarjeta es requerido.',
        'c_name.string' => 'El titular de la tarjeta solo acepta texto.',
        'c_number.required' => 'El número de tarjeta es requerido.',
        'c_number.numeric' => 'El número de tarjeta debe ser numérico.',
        'c_number.digits_between' => 'El número de la tarjeta debe tener entre 16 y 19 dígitos.',
        'c_month.required' => 'El mes de la tarjeta es obligatorio.',
        'c_month.date_format' => 'El formato del mes de la tarjeta no es válido.',
        'c_year.required' => 'El año de la tarjeta es obligatorio.',
        'c_year.date_format' => 'El formato del año de la tarjeta no es válido.',
        'c_code.required' => 'El valor de verificación de la tarjeta es obligatorio.',
        'c_code.string' => 'El valor de verificación de la tarjeta debe ser texto.',
        'c_type.string' => 'El tipo de tarjeta debe ser string.',
        'c_type.required' => 'El tipo de tarjeta es obligatorio.',
        'cliente_id.required' => 'Se requiere el ID del cliente.',
        'cliente_id.exist' => 'El id del cliente no esta registrado.',
    ];

    public static function validateBeforeSave($request) {
        $validateCardData = Validator::make($request, [
            'cliente_id' => 'required|exists:clientes,id',
            'c_name' => 'required|string',
            'c_cn1' => 'required|numeric|digits_between:1,5',
            'c_cn2' => 'required|numeric|digits_between:1,5',
            'c_cn3' => 'required|numeric|digits_between:1,5',
            'c_cn4' => 'required|numeric|digits_between:1,5',
            'c_month' => 'required|date_format:m',
            'c_year' => 'required|date_format:Y',
            'c_code' => 'required|string',
            'c_type' => 'required|string',
            'c_method' => 'required|string'
        ], self::$messages);

        if ($validateCardData->fails()) {
            return $validateCardData->errors()->all();
        } else {
            // validamos expiración de la tarjeta
            $date =  $request['c_year'] . '/' . $request['c_month'];
            if ($date < Carbon::now()->format('Y/m')) {
                return ['La tarjeta está expirada ' . $date];
            }
            return true;
        }
    }
}
