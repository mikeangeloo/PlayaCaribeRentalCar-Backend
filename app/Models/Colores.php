<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Colores extends Model
{
    use HasFactory;
    protected $table = 'colores';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'color' => 'required|string|max:100'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }
}
