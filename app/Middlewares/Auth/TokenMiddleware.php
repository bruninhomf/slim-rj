<?php

/*
 * File        : APITokenMiddleware.php
 * Description : Middleware de autenticação das rotas da API
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Auth;

use App\Models\Token;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Extensions\Support\Arr;
use App\Extensions\Support\Date;
use App\Middlewares\Middleware;

class TokenMiddleware extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //recupera a key
        $hash  = Arr::first($request->getHeader('HTTP_OAUTH_TOKEN'));

        //localiza o token
        $token = Token::where(['hash' => $hash])->first();

        //verifica se o token não exipirou
        if($token and $token->expires->gt(Date::now())){

            //autenticado
            return $response = $next($request, $response);

        }else{

            //retorna o erro para a api
            return $response->withStatus(400)->withJson([
                'result' => false,
                'error'  => "Por favor faça login novamente."
            ]);

        }

    }

}
















