<?php

/*
 * File        : FormRequestMiddleware.php
 * Description : Middleware de formatação e filtro de dados dos formulários
*/

namespace App\Middlewares\Request;

use App\Util\Filter;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Extensions\Support\Date;
use App\Extensions\Support\Math;
use App\Middlewares\Middleware;

class FormMiddleware extends Middleware {

    //campos formatados
    protected $params    = [];
    protected $fields    = [];

    //formatos de datas
    protected $dates = [
        "d/m/Y", "Y/m/d"
    ];


    /*
    |--------------------------------------------------------------------------
    | Callable do Slim
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //formata os parâmetros GET
        foreach($request->getQueryParams() as $key => $param){

            //recupera o valor filtrado
            $value = Filter::value($param);

            //converte o valor para um formato válido de data
            $value = $this->dates($value);

            //converte o valor para um formato monetário do db
            $value = $this->monetaries($value);

            //salva o valor
            $this->params[$key] = $value;

        }

        //salva os dados de formulário
        $this->fields = $request->all();

        //formata os parâmetros POST
        array_walk_recursive($this->fields, function(&$value){

            //recupera o valor filtrado
            $value = Filter::value($value);

            //converte o valor para um formato válido de data
            $value = $this->dates($value);

            //converte o valor para um formato monetário do db
            $value = $this->monetaries($value);

        });

        //refatora o request
        $request = $request->withParsedBody($this->fields)->withQueryParams($this->params);

        //retorna um request filtrado com os mesmos atributos
        return $response = $next($request, $response);

    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o valor é uma data e converte para o padrão do DB
    |--------------------------------------------------------------------------
    */
    protected function dates($value){

        //testa os formatos de datas
        foreach ($this->dates as $format){

            //testa o valor com o formato de data
            if(
                Date::equalsFormat($value, $format)
            ){
                return Date::createFromFormat($format, $value)->format('Y-m-d');
            }

        }

        return $value;

    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se o valor monetário converte para o padrão do DB
    |--------------------------------------------------------------------------
    */
    protected function monetaries($value){
        return (is_float($value) or Math::equalsFormat($value)) ? Math::parse($value) : $value;
    }

}