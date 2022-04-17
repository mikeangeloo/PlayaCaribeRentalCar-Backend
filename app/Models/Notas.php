<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Notas extends Model
{
    use HasFactory;
    protected $table = 'notas';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'nota' => 'required|string',
            'modelo_id' => 'required|numeric',
            'modelo' => 'required|string',
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }


}
