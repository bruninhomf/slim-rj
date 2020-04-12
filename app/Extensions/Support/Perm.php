<?php

/*
 * File        : Perm.php
 * Description : Extensão do manipulador de permissões
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

use App\Util\Auth;
use App\Models\PermissionUser;

class Perm {

    //níveis de permissão
    protected static $levels = [
        'create',
        'read',
        'update',
        'delete',
        'additional'
    ];

    /*
    |--------------------------------------------------------------------------
    | Verifica se o usuário possui determinada permissão
    |--------------------------------------------------------------------------
    */
    public static function check($name, $level, $owner = false){

        //resolve o id do proprietário da permissão
        $id = (!$owner) ? Auth::id() : $owner;

        //localiza a permissão no db
        return PermissionUser::join('permissions', 'permissions.id', 'permission_users.permission_id')->where([
            "permissions.initials"      => $name,
            "permission_users.user_id"  => $id,
            "permission_users.{$level}" => 'Y',
        ])->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o usuário tem alguma permissão válida dentro de uma lista de
    | modulos.
    |--------------------------------------------------------------------------
    */
    public static function modules($modules){

        foreach ((array) $modules as $module){

            foreach (self::$levels as $level) {

                if(self::check($module, $level)) return !0;

            }
        }

        return !1;

    }

}