<?php

/*
 * File        : Hash.php
 * Description : Extensão do manipulador de hashs
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

class Hash {

    /*
    |--------------------------------------------------------------------------
    | Retorna o hash de uma senha usando a mascara configurada
    |--------------------------------------------------------------------------
    */
    public static function encrypt($password){
        return password_hash(str_replace('@', $password, APP_CRYPTO_MASK), PASSWORD_BCRYPT);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica o hash de uma senha encriptada com a mascara configurada
    |--------------------------------------------------------------------------
    */
    public static function verify($password, $hash){
        return password_verify(str_replace('@', $password, APP_CRYPTO_MASK), $hash);
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma hash randômica baseada em dicionário
    |--------------------------------------------------------------------------
    */
    public static function randomize($length = 16, $salt = "abcdefghijklmnopqrstuvwxyz0123456789") {
        $len = strlen($salt);
        $hash = '';
        mt_srand(10000000 * (double) microtime());
        for ($i = 0; $i < $length; $i++) {
            $hash .= $salt[mt_rand(0, $len - 1)];
        }
        return $hash;
    }

}