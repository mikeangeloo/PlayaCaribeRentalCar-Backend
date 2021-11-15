<?php

namespace App\Helpers;

class Encrypt
{
    private $algorithm = 'AES-128-CBC'; // Algoritmo de operación AES
    private $key; // variable para almacenar la llave
    private $iv; // variable para el vector de incialización


   public function __construct($secret)
   {
       // Verificamos que exista la clave secreta y que sea de tipo string
       if (isset($secret) && !is_string($secret)) {
           throw new InvalidArgumentException(
               'Cryptr: it is mandatory to attach a value that works as a key or password to encrypt'
           );
       }

       $this->key = base64_decode($secret); // asigamos clave para encriptar y desencriptar
       $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->algorithm)); // asignamos el tamaño del vector dependiendo del algoritmo
   }

     // Método para encriptar
     public function encrypt($data) {
        // Generar texto cifrado actual a partir de datos utilizando la clave y iv. Devolviendolo en formato binario.
        $ciphertext_raw = openssl_encrypt($data, $this->algorithm, $this->key, OPENSSL_RAW_DATA, $this->iv);
        // retornamos en base64
        return base64_encode($this->iv.$ciphertext_raw);
    }

    public function decrypt($data) {
        // decodificamos datos recibidos en base64
        $string = base64_decode($data);

        // obtenemos el tamaño del vector
        $sizeText =  strlen($this->iv);

        // desencriptamos texto y guardamos en variable
        $decryptText = openssl_decrypt($string, $this->algorithm, $this->key, OPENSSL_RAW_DATA, $this->iv);
        // retornamos texto desencriptado ignorando los caracteres que le corresponden al tamaño del vector IV
        return substr($decryptText, $sizeText);
    }
}
