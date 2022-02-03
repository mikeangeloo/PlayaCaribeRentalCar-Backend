<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ClasesVehiculos extends Model
{
    use HasFactory;
    protected $table = 'clases_vehiculo';
    protected $primaryKey = 'id';

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'clase' => 'required|string|max:100'
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }
        return true;
    }
}
