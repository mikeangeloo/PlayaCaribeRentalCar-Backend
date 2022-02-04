<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Ubicaciones extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'pais' => 'required|string',
            'estado' => 'required|string',
            'municipio' => 'required|string',
            'colonia' => 'required|string',
            'direccion' => 'required|string',
            'cp' => 'required|numeric',
            'alias' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }
        return true;
    }
}
