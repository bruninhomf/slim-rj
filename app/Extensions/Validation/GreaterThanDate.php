<?php

/*
 * File        : GreaterThanDate.php
 * Description : Extensão de verificação de data mínima
*/

namespace App\Extensions\Validation;

use App\Extensions\Support\Date;
use Respect\Validation\Rules\AbstractRule;
use App\Extensions\Validation\Exceptions\GeneralValidationException;

class GreaterThanDate extends AbstractRule {

    protected $date;
    protected $field;

    /*
    |--------------------------------------------------------------------------
    | Construtor
    |--------------------------------------------------------------------------
    */
    public function __construct(Date $date, $field){
        $this->date  = $date;
        $this->field = $field;
    }

    /*
    |--------------------------------------------------------------------------
    | Verifica se a data é maior que a data atual
    |--------------------------------------------------------------------------
    */
    public function validate($input){
        return $this->date->gte(Date::today());
    }

    /*
    |--------------------------------------------------------------------------
    | Build Exception
    |--------------------------------------------------------------------------
    */
    protected function createException(){
        return new GeneralValidationException("O campo \"{$this->field}\" deve ser maior ou igual a data atual.");
    }
}