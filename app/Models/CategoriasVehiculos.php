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


    public static function validateBeforeSave($request, $edit = false) {
        $validateData = Validator::make($request['categoria'], [
            'categoria' => 'required|string|max:100',
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        if(!$edit) {
            //Validamos que no se repita
            if (self::categoriaExist($request['categoria']['categoria'])) {
                return ['Esta categoría ya se encuentra registrada'];
            }
        }

        if($edit && isset($request['layout']) === false) {
            if (self::categoriaExist($request['categoria']['categoria'])) {
                return ['Esta categoría ya se encuentra registrada'];
            }
        }

        return true;
    }


    private static function categoriaExist($categoria) {
        $foundCat = CategoriasVehiculos::where('categoria', $categoria)->first();
        if($foundCat) {
            return true;
        } else {
            return false;
        }
    }
}
