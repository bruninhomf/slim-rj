<?php

/*
 * File        : PermissionMiddleware.php
 * Description : Middleware de verificação de permissão de acesso a rota
*/

namespace App\Middlewares\Auth;

use App\Extensions\Support\Perm;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;

class PermissionMiddleware extends Middleware {

    protected $module;
    protected $level;

    /*
    |--------------------------------------------------------------------------
    | Construtor
    |--------------------------------------------------------------------------
    */
    public function __construct(&$container, $module, $level){

        //obtem o nome da permissão requisitada
        $this->module = $module;
        $this->level  = $level;

        //constroi o middleware
        parent::__construct($container);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se tem o nível nescessário
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){
        if(Perm::check($this->module, $this->level)){
            return $response = $next($request, $response);
        }else{
            return ($request->isGet()) ? $response->withRedirect($this->container->router->pathFor('home.index')) : $response->withStatus(401)->withJson([
                'result' => false,
                'error'  => "Ação não autorizada."
            ]);
        }
    }
}