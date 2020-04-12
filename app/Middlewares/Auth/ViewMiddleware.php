<?php

/*
 * File        : ViewMiddleware.php
 * Description : Middleware de configuração de view
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Auth;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Extensions\Support\Arr;
use App\Extensions\Support\Date;
use App\Extensions\Support\Env;
use App\Extensions\Support\Filesystem;
use App\Extensions\Support\Hash;
use App\Extensions\Support\Math;
use App\Extensions\Support\Obj;
use App\Extensions\Support\Perm;
use App\Extensions\Support\Route;
use App\Extensions\Support\Server;
use App\Extensions\Support\Str;
use App\Extensions\Twig\Utils;
use App\Middlewares\Middleware;

class ViewMiddleware extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Limpa os erros de validação da sessão
    |--------------------------------------------------------------------------
    */
    public function clear(){
        unset($_SESSION['errors']);
    }

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //adiciona os tokens csrf
        $this->container->view->getEnvironment()->addGlobal('tokens', [
            'name'  => $this->container->csrf->getTokenName(),
            'value' => $this->container->csrf->getTokenValue()
        ]);

        //adiciona os campos csrf a view
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                 <input type="hidden" name="'.$this->container->csrf->getTokenNameKey().'" value="'.$this->container->csrf->getTokenName().'">
                 <input type="hidden" name="'.$this->container->csrf->getTokenValueKey().'" value="'.$this->container->csrf->getTokenValue().'">
            ',
        ]);

        //extensões personalizadas
        $this->container->view->addExtension(new Utils($this->container->router, $this->container['request']->getUri()));

        //globais
        $this->container->view->getEnvironment()->addGlobal('auth',  $this->container->auth);
        $this->container->view->getEnvironment()->addGlobal('flash', $this->container->flash);
        $this->container->view->getEnvironment()->addGlobal('old',   $this->container->old);
        $this->container->view->getEnvironment()->addGlobal('now',   Date::now());

        //adiciona os erros de validação
        $this->container->view->getEnvironment()->addGlobal('errors', Arr::get($_SESSION, 'errors'));

        //adiciona os wrappers
        $this->container->view->getEnvironment()->addGlobal('array', new Arr());
        $this->container->view->getEnvironment()->addGlobal('date', new Date());
        $this->container->view->getEnvironment()->addGlobal('env', new Env());
        $this->container->view->getEnvironment()->addGlobal('filesystem', new Filesystem());
        $this->container->view->getEnvironment()->addGlobal('hash', new Hash());
        $this->container->view->getEnvironment()->addGlobal('math', new Math());
        $this->container->view->getEnvironment()->addGlobal('obj', new Obj());
        $this->container->view->getEnvironment()->addGlobal('perm', new Perm());
        $this->container->view->getEnvironment()->addGlobal('route', new Route($request));
        $this->container->view->getEnvironment()->addGlobal('server', new Server());
        $this->container->view->getEnvironment()->addGlobal('str', new Str());

        //adiciona os dados do formulário
        $_SESSION['old'] = $request->all();

        $this->clear();

        //continua o stack
        return $response = $next($request, $response);

    }

}