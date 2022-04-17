<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CheckList extends Model
{
    use HasFactory;
    protected $table = 'check_list';
    protected $primaryKey = 'id';

    protected $casts = [
        'containerPost' => 'array',
        'boxPosition' => 'array',
    ];

    public static function validateBeforeSave($request) {
        $validate = Validator::make($request, [
            'payload' => 'required|array',
            'payload.*.contrato_id' => 'required|exists:contratos,id|numeric',
            'payload.*.tipo' => 'required|numeric',
            'payload.*.width' => 'required|numeric',
            'payload.*.height' => 'required|numeric',
            'payload.*.containerPost' => 'nullable',
            'payload.*.boxPosition' => 'nullable',
            'payload.*.objId' => 'required',
            'payload.*.top' => 'required',
            'payload.*.left' => 'required',
            'payload.*.action' => 'required',
            'payload.*.levelcolor' => 'requiered',
            'payload.*.levelTxt' => 'required',
            'payload.*.indicatorIcon' => 'required',
            'payload.*.indicatorTitle' => 'required',
        ]);

        if ($validate->fails()) {
            return $validate->errors()->all();
        }

        return true;
    }

    public function notas() {
        return $this->hasMany(Notas::class, 'modelo_id', 'id');
    }
}
