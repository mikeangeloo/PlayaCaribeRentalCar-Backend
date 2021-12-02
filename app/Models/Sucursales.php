<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Sucursales extends Model
{
    use HasFactory;
    protected $table = 'sucursales';
    protected $primaryKey = 'id';


    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'nombre' => 'required|string',
            'direccion' => 'required|string',
            'codigo' => 'required|string|max:50',
            'cp' => 'required'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }
}
