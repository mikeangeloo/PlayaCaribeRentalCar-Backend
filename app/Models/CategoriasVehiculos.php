<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CategoriasVehiculos extends Model
{
    use HasFactory;
    protected $table = 'categorias_vehiculos';
    protected $primaryKey = 'id';

    public function categoria_docs() {
        return $this->hasOne(ModelosDocs::class, 'modelo_id', 'id')->where('modelo', 'categorias_vehiculos');
    }


    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request['categoria'], [
            'categoria' => 'required|string|max:100',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }
}
