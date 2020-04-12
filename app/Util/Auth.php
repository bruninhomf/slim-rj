<?php

/*
 * File        : Auth.php
 * Description : Wrapper de acesso a sessão
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Util;

use App\Models\User;
use App\Models\Client;
use App\Extensions\Support\Hash;

class Auth {

    //tabelas de autenticação
    protected static $drivers = [
        'admin'  => User::class,
        'client' => Client::class
    ];

    /*
    |--------------------------------------------------------------------------
    | Retorna o campo da sessão por meio de acesso a uma propriedade
    |--------------------------------------------------------------------------
    */
    public function __get($name){
        return self::field($name);
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o campo da sessão por meio de acesso a método estátivo
    |--------------------------------------------------------------------------
    */
    public static function __callStatic($name, $args){
        return self::field($name);
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o campo da sessão por meio de acesso a método comum
    |--------------------------------------------------------------------------
    */
    public function __call($name, $arguments){
        return self::field($name);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se exite uma sessão auténticada
    |--------------------------------------------------------------------------
    */
    public static function logged(){
        return !empty($_SESSION['app']['user']);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a senha informada confere com a da sessão
    |--------------------------------------------------------------------------
    */
    public static function check($password){
        return (
            Hash::verify($password, self::field('password'))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o objeto da sessão
    |--------------------------------------------------------------------------
    */
    public static function session(){
        return unserialize($_SESSION['app']['user']);
    }

    /*
    |--------------------------------------------------------------------------
    | Session Field Retrieve
    |--------------------------------------------------------------------------
    */
    protected static function field($name){
        return unserialize($_SESSION['app']['user'])->{$name};
    }

}