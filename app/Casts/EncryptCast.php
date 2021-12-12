<?php

namespace App\Casts;

use App\Helpers\Encrypt as HelpersEncrypt;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EncryptCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        $crypter = new HelpersEncrypt(config('app.crypt-key'));

        return ($crypter->decrypt($value) == false) ? null : $crypter->decrypt($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        $crypter = new HelpersEncrypt(config('app.crypt-key'));
        return ($crypter->encrypt($value) == false) ? null : $crypter->encrypt($value);
    }
}
