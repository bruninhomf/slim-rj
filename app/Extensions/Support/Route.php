<?php

/*
 * File        : Routing.php
 * Description : Extensão do manipulador de rotas
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

use Slim\Route as Routable;
use Psr\Http\Message\ServerRequestInterface;

class Route {

    //rota
    protected $route;
    protected $groups;

    //atributos de rota
    protected $name;
    protected $level;
    protected $module;
    protected $args = [];

    /*
    |--------------------------------------------------------------------------
    | Builder
    |--------------------------------------------------------------------------
    */
    public function __construct(ServerRequestInterface $request){

        //recupera a rota atual
        $route = $request->getAttribute('route');

        //verifica se é uma rota válida
        if($route and $route instanceof Routable){

            //salva a rota
            $this->route  = $route;

            //salva o nome
            $this->name   = $route->getName();

            //recupera os atributos
            $attrs = explode('.', $route->getName());
            $this->level  = Arr::last($attrs);
            $this->module = Arr::first($attrs);

            //recupera os argumentos
            $this->args = $route->getArguments();

            //salva os grupos
            $this->groups = collect($route->getGroups())->map(function($group){
                return $group->getPattern();
            });

        }else{
            $this->groups = collect([]);
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o nome da rota
    |--------------------------------------------------------------------------
    */
    public function name(){
        return $this->name;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o nível dentro do módulo
    |--------------------------------------------------------------------------
    */
    public function level(){
        return $this->level;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna os argumentos
    |--------------------------------------------------------------------------
    */
    public function args(){
        return $this->args;
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a rota esta em uma lista de módulos
    |--------------------------------------------------------------------------
    */
    public function module($matches){
        return in_array($this->module, (array) $matches);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a rota esta em uma lista de rotas ou grupos
    |--------------------------------------------------------------------------
    */
    public function equals($matches){
        return in_array($this->name, (array) $matches);
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a rota esta em uma lista de grupos
    |--------------------------------------------------------------------------
    */
    public function group($matches){

        foreach ((array) $matches as $match) {
            if($this->groups->contains($match)) return !0;
        }

        return !1;

    }

}