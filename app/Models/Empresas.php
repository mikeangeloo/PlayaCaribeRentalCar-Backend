<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Empresas extends Model
{
    use HasFactory;
    protected $table = 'empresas';
    protected $primaryKey = 'id';

    // public function sucursales() {
    //     return $this->hasMany(Sucursales::class, 'empresas_id', 'id');
    // }

    public static function validateBeforeSave($request, $isUpdate = null) {
        $validateData = Validator::make($request, [
            'nombre' => 'required|string',
            'rfc' => 'required',
            'direccion' => 'required|string',
            'tel_contacto' => 'required|string'
        ]);

        if (is_null($isUpdate)) {
            $empresa = Empresas::where('rfc', $request['rfc'])->first();
            if ($empresa) {
                return ['The rfc must be unique'];
            }
        }

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }

}

