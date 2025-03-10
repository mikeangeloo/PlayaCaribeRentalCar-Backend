<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CargosRetornoExtras extends Model
{
    use HasFactory;
    protected $table = 'cargos_retorno_extras';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'nombre' => 'required|string',
            'tipo' => 'required|string',
            'precio' => 'required|numeric'
        ]);
        if ($validate->fails()) {
            return $validate->errors()->all();
        } else {
            return true;
        }
    }
}
