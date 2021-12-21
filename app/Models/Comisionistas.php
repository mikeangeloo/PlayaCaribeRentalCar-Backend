<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Comisionistas extends Model
{
    use HasFactory;
    protected $table = 'comisionistas';
    protected $primaryKey = 'id';

    protected $casts = [
        'comisiones_pactadas' => 'array'
    ];

    public function empresa() {
        return $this->belongsTo(Empresas::class, 'empresa_id', 'id');
    }

    public static function validateBeforeSave($request) {
        $validateData = Validator::make($request, [
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'nombre_empresa' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
            'tel_contacto' => 'required|string',
            'email_contacto' => 'required|email',
            'comisiones_pactadas' => 'required'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        }

        return true;
    }
}
