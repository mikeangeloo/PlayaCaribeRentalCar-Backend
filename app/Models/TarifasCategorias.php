<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TarifasCategorias extends Model
{
    use HasFactory;
    protected $table = 'tarifas_categorias';
    protected $primaryKey = 'id';


    public static function validateBeforeSave($request, $isUpdate = false) {
        $validate = Validator::make($request, [
            'categoria' => 'required|string|max:200',
            'precio_renta' => 'required|numeric',
        ]);

        if ($isUpdate === false) {
            $find = TarifasCategorias::where('categoria', $request['categoria'])->first();
            if($find) {
                return ['The categoria has already been taken.'];
            }
        }

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }
}
