<?php

/*
 * File        : PasswordMiddleware.php
 * Description : User password protected route verification middleware
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Auth;

use App\Util\Auth;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;

class PasswordMiddleware extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){
        if($request->isPost()){
            if($request->field('password')){

                //check password and return redirect
                return (Auth::check($request->field('password'))) ? $response = $next($request, $response) : $response->withStatus(400)->withJson([
                    'result' => false,
                    'error'  => "A senha de confirmação está incorreta."
                ]);

            }else{

                //empty password
                return $response->withStatus(400)->withJson([
                    'result' => false,
                    'error'  => "A senha de confirmação é obrigatória."
                ]);

            }
        }else{
            return $response = $next($request, $response);
        }
    }

}