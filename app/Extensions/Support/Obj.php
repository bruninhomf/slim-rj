<?php

/*
 * File        : Obj.php
 * Description : Extensão do manipulador de objetos
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

class Obj {

    /*
    |------------------------------------------------------------------------------
    | Retorna uma propriedade do objeto
    |------------------------------------------------------------------------------
    */
    public static function get($obj, $key, $default = null){
        return (!empty($obj->{$key})) ? $obj->{$key} : $default;
    }

    /*
    |------------------------------------------------------------------------------
    | Converte o array para json
    |------------------------------------------------------------------------------
    */
    public static function toArray($obj) {

        //mapeia as propriedades do objeto
        $obj = (is_object($obj)) ? get_object_vars($obj) : $obj;
            
        return (is_array($obj)) ? array_map(__FUNCTION__, $obj) : $obj;

    }

}