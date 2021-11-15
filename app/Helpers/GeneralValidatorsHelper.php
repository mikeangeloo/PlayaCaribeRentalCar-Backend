<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class GeneralValidatorsHelper
{
    // Método para validar valores recibidos por request
    public static function validateBeforeLogin($request) {
        $validateData = Validator::make($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validateData->fails()) {
            return $validateData->errors()->all();
        } else {
            return true;
        }
    }
}
