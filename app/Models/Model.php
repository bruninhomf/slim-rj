<?php

/*
 * File        : User.php
 * Description : Model base da aplicação
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Models;

use App\Extensions\Support\Arr;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent {

    //conexão usada pela model
    protected $connection = 'default';

    //campos protegidos
    protected $persistent = [];

    //campos instânciados via Carbon
    protected $dates      = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | Remoção de campos protegidos
    |--------------------------------------------------------------------------
    */
    public function update(array $attributes = [], array $options = []){
        return parent::update(
            Arr::except($attributes, $this->persistent), $options
        );
    }

}