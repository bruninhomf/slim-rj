<?php

/*
 * File        : Middleware.php
 * Description : Middleware base da aplicação
*/

namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class Middleware {

    //container de injeção de dependências
    protected $container;

    //campos do formulário de validação
    protected $form   = [];

    //erros de validação
    protected $errors = [];

    /*
    |--------------------------------------------------------------------------
    | Construtor
    |--------------------------------------------------------------------------
    */
    public function __construct(&$container){
        $this->container = $container;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtem uma propriedade ou objeto mapeado no container
    |--------------------------------------------------------------------------
    */
    public function __get($property){

        return $this->container->has($property) ? $this->container->{$property} : !1;

    }

    /*
    |--------------------------------------------------------------------------
    | Valida os campos do formulário
    |--------------------------------------------------------------------------
    */
    public function validate(Request $request){
        if($this->container->validator->validate($request, $this->form)->success()){
            return true;
        }else{
            $this->errors = $this->container->validator->getErrors();
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna erros de validação da API
    |--------------------------------------------------------------------------
    */
    public function error(Response $response){

        //monta a descrição do erro
        $error = collect($this->errors)->flatten()->first();

        return $response->withStatus(400)->withJson([
            'result' => false,
            'error'  => $error
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Adiciona uma mensagem de erro a stack e retorna false
    |--------------------------------------------------------------------------
    */
    protected function fails($message){
        $this->errors[] = [$message]; return !1;
    }

}