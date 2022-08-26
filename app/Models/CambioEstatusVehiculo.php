<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CambioEstatusVehiculo extends Model
{
    use HasFactory;
    protected $table = 'cambio_estatus_vehiculo';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'estatus' => 'required|string',
            'observaciones' => 'required|string',
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }

}
