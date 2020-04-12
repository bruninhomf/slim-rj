<?php

/*
 * File        : UnrestrictedMiddleware.php
 * Description : Middleware de verificação para rotas não restritas
 * Author      : Mining Capital Coin <contact@miningcapitalcoin.com>
*/

namespace App\Middlewares\Auth;

use App\Util\Auth;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;
use App\Extensions\Support\Route;

class UnrestrictedMiddleware extends Middleware {

    //rotas ignoradas
    protected $ignore = [
        'auth.logout'
    ];

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //instância o wrapper de rotas
        $router = new Route($request);

        //verifica se a rota é liberada
        return (!Auth::logged() or $router->equals($this->ignore)) ? $next($request, $response) : $response->withRedirect(
            $this->container->router->pathFor('home')
        );

    }

}