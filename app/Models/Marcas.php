<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Marcas extends Model
{
    use HasFactory;
    protected $table = 'marcas';
    protected $primaryKey = 'id';



    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'marca' => 'required|string|max:100',
            'tipo' => 'required|numeric'
        ]);

        $validTipo = [0, 1, 2];

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        if (in_array($request['tipo'], $validTipo) === false) {
            return ['Invalid tipo value'];
        }

        return true;
    }
}
