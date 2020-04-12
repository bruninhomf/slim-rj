<?php

/*
 * File        : Guard.php
 * Description : Extensão de proteção CSRF
*/

namespace App\Extensions\Slim;

use App\Extensions\Support\Arr;
use App\Extensions\Support\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Guard extends \Slim\Csrf\Guard {

    //rotas sem proteção csrf
    protected $patterns = ['/api'];

    /*
    |------------------------------------------------------------------------------
    | Slim Callable
    |------------------------------------------------------------------------------
    */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next){

        //instância o wrapper de rotas
        $router = new Route($request);

        if($router->group($this->patterns)) {
            return $response = $next($request, $response);
        }else{

            $this->validateStorage();

            //verifica se é um método de requisição protegida
            if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {

                //obtem os headers
                $headers = $request->getHeaders();

                //obtem os tokens
                $name  = Arr::get((Arr::get($headers, 'HTTP_X_CSRF_NAME')), 0, $request->field('csrf_name'));
                $value = Arr::get((Arr::get($headers, 'HTTP_X_CSRF_TOKEN')),0, $request->field('csrf_value'));

                if (!$name || !$value || !$this->validateToken($name, $value)) {

                    // Need to regenerate a new token, as the validateToken removed the current one.
                    $request = $this->generateNewToken($request);

                    $failureCallable = $this->getFailureCallable();
                    return $failureCallable($request, $response, $next);
                }

            }

            // Generate new CSRF token if persistentTokenMode is false, or if a valid keyPair has not yet been stored
            if (!$this->persistentTokenMode || !$this->loadLastKeyPair()) {
                $request = $this->generateNewToken($request);
            } elseif ($this->persistentTokenMode) {
                $pair = $this->loadLastKeyPair() ? $this->keyPair : $this->generateToken();
                $request = $this->attachRequestAttributes($request, $pair);
            }

            // Enforce the storage limit
            $this->enforceStorageLimit();

            return $next($request, $response);

        }

    }
}