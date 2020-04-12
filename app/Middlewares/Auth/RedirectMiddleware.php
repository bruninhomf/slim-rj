<?php

/*
 * File        : RedirectIfLoggedMiddleware.php
 * Description : Middleware de redirecionamento para no caso do usuário já autenticado
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Auth;

use App\Util\Auth;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;

class RedirectMiddleware extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        return (!Auth::logged()) ? $response = $next($request, $response) : $response->withRedirect(
            $this->container->router->pathFor('home.index')
        );

    }

}