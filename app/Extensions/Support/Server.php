<?php

/*
 * File        : Server.php
 * Description : Extensão do manipulador de configurações do servidor
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

class Server {

    /*
    |--------------------------------------------------------------------------
    | Mostra todos os níveis de erros e alertas do PHP.
    |--------------------------------------------------------------------------
    */
    public static function display_errors() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    /*
    |--------------------------------------------------------------------------
    | Ativa o debug simples. Apenas erros fatais, parsing e warnings.
    |--------------------------------------------------------------------------
    */
    public static function hide_errors(){
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
    }

}