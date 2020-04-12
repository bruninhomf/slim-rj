<?php

/*
 * File        : LoginValidation.php
 * Description : Middleware de validação de autenticação
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Validation;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;
use Respect\Validation\Validator as Mask;

class LoginValidation extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Validação de campos
    |--------------------------------------------------------------------------
    */
    public function fields(Request $request){

        //formulário
        $this->form  = [
            'cpf'      => Mask::cpf()->setName('CPF'),
            'password' => Mask::notEmpty()->setName('Password')
        ];

        return $this->validate($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        if($request->isPost()){

            return ($this->fields($request)) ? $response = $next($request, $response) : $response->withRedirect(
                $this->container->router->pathFor('auth.login')
            );

        }else{
            return $response = $next($request, $response);
        }
    }

}
