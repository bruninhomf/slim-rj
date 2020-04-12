<?php

/*
 * File        : NotExists.php
 * Description : Extensão de verificação de duplicidade
*/

namespace App\Extensions\Validation;

use App\Extensions\Support\Route;
use Slim\Http\Request;
use App\Extensions\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Respect\Validation\Rules\AbstractRule;
use App\Extensions\Validation\Exceptions\GeneralValidationException;

class NotExists extends AbstractRule {

    protected $model;
    protected $field;
    protected $input;
    protected $translate;
    protected $request;

    //edição
    protected $match;
    protected $update;

    /*
    |--------------------------------------------------------------------------
    | Construtor
    |--------------------------------------------------------------------------
    */
    public function __construct(Request $request, Model $model, $field, $translate, $arg = !1){

        //instância a rota
        $route = new Route($request);

        try {

            //reflection infos
            $reflection = new \ReflectionClass($model);

            //captura os indicadores de edição
            $this->update = $route->level() == 'update';

            if($this->update){

                //captura o registro atual para bypass
                $this->match  = collect($route->args())->get($arg ? $arg : Str::lower($reflection->getShortName()));

            }

        } catch (\ReflectionException $e) {}

        $this->model      = $model;
        $this->field      = $field;
        $this->translate  = $translate;
        $this->request    = $request;


    }

    /*
    |--------------------------------------------------------------------------
    | Verifica na tabela se o campo não existe
    |--------------------------------------------------------------------------
    */
    public function validate($input){

        if(!$this->update){
            return !$this->model->where($this->field, '=', $input)->withTrashed()->first();
        }else{
            return !$this->model->where($this->model->getKeyName(), '!=', $this->match)->where($this->field, '=', $input)->withTrashed()->first();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Build Exception
    |--------------------------------------------------------------------------
    */
    protected function createException(){
        return new GeneralValidationException("O {$this->translate} informado já está cadastrado.");
    }
}