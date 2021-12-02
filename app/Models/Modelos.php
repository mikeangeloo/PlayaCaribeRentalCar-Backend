<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Modelos extends Model
{
    use HasFactory;
    protected $table = 'modelos';
    protected $primaryKey = 'id';

    public function marca() {
        return $this->belongsTo(Marcas::class, 'marca_id', 'id')->select('id', 'marca');
    }

    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'modelo' => 'required|string|max:100',
            'marca_id' => 'required|exists:marcas,id'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }
}
