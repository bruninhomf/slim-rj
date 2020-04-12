<?php

/*
 * File        : SessionMiddlewareare.php
 * Description : Middleware de atualização da sessão do usuário
*/

namespace App\Middlewares\Auth;

use App\Util\Auth;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;
use App\Extensions\Support\Route;

class SessionMiddleware extends Middleware {

    //rotas sem atualização de sessão
    protected $routes = ['auth.inicio', 'auth.sobre', 'auth.servicos', 'auth.orcamento', 'auth.blog', 'auth.trabalheConosco', 'auth.contato'];
    protected $groups = ['/api'];

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //instância o wrapper de rotas
        $router = new Route($request);

        if(
            $router->equals($this->routes) or $router->group($this->groups)
        ){
            return $response = $next($request, $response);
        }else{

            //verifica se está autenticado
            if(Auth::logged()){

                //refresh user
                $this->refresh();

                //verify user
                if(!$this->blocked()){
                    return $response = $next($request, $response);
                }else{

                    //adiciona a mensagem de bloqueio
                    $this->container->flash->addMessage('error', 'Usuário temporáriamente inativo.');

                }

            }

        }

        //limpa a sessão expirada
        unset($_SESSION['app']['user']);

        //exibe a mensagem de login expirado
        $this->container->flash->addMessage('error', 'Por favor faça login novamente.');

        //redireciona para o login
        return $response->withRedirect(
            $this->container->router->pathFor('auth.inicio')
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Atualiza a sessão do usuário
    |--------------------------------------------------------------------------
    */
    protected function refresh(){

        //recupera os dados do usuário
        $user = User::find($this->auth->id);

        //atualiza a sessão
        $_SESSION['app']['user'] = serialize($user);

    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o acesso foi bloqueado
    |--------------------------------------------------------------------------
    */
    protected function blocked(){
        return $this->auth->blocked == 'Y';
    }

}