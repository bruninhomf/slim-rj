<?php

/*
 * File        : Recurrency.php
 * Description : Extensão do manipulador de recorrência
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

use When\When;

class Recurrency extends When {

    //desabilita a exception quando a primeira data da recorrência não é válida
    public $RFC5545_COMPLIANT = self::IGNORE;

}