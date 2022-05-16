<?php

namespace App\Helpers;


class GenerateUniqueAlphCodesHelper {
    public static function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return strtoupper(substr(str_shuffle($str_result), 0, $length_of_string));
    }
}
