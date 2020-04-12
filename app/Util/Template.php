<?php

/*
 * File        : Template.php
 * Description : Wrapper de gerênciamento de templates
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Util;

use Slim\Views\Twig;
use App\Extensions\Twig\Utils;
use App\Extensions\Support\Arr;
use App\Extensions\Support\Date;
use App\Extensions\Support\Env;
use App\Extensions\Support\Hash;
use App\Extensions\Support\Math;
use App\Extensions\Support\Obj;
use App\Extensions\Support\Perm;
use App\Extensions\Support\Route;
use App\Extensions\Support\Server;
use App\Extensions\Support\Str;
use App\Extensions\Support\Filesystem;

class Template {

    protected static $i;
    protected static $r;
    protected static $c = false;
    protected static $p = false;

    /*
    |--------------------------------------------------------------------------
    | Singleton construtor
    |--------------------------------------------------------------------------
    */
    static public function load($path, $args = []){

        if(!self::$i){

            //inicializa uma instância via singleton
            self::$i = new self();

            //inicializa uma instância do twig
            self::$r = new Twig(Filesystem::directory("~/resources/templates/"));

            //extensões personalizadas
            self::$r->addExtension(new Utils(null, null));

            //globais
            self::$r->getEnvironment()->addGlobal('auth', new Auth());
            self::$r->getEnvironment()->addGlobal('array', new Arr());
            self::$r->getEnvironment()->addGlobal('date', new Date());
            self::$r->getEnvironment()->addGlobal('env', new Env());
            self::$r->getEnvironment()->addGlobal('filesystem', new Filesystem());
            self::$r->getEnvironment()->addGlobal('hash', new Hash());
            self::$r->getEnvironment()->addGlobal('math', new Math());
            self::$r->getEnvironment()->addGlobal('obj', new Obj());
            self::$r->getEnvironment()->addGlobal('perm', new Perm());
            self::$r->getEnvironment()->addGlobal('server', new Server());
            self::$r->getEnvironment()->addGlobal('str', new Str());

        }

        //carrega o template e faz as alterações
        return self::$r->fetch(str_replace('.', '/', $path) . ".twig", array_merge($args, [
            'APP_URL'  => APP_URL,
            'APP_NAME' => APP_NAME,
        ]));

    }

}
