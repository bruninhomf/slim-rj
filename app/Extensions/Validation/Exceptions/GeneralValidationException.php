<?php

/*
 * File        : cpfAvailableException.php
 * Description : Exception para cpf cadastrado
 * Date        : 04/10/2017 09:00
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Validation\Exceptions;

use Throwable;
use Respect\Validation\Exceptions\ValidationException;

class GeneralValidationException extends ValidationException {

    public static $defaultTemplates;

    //passar o nome do campo no primeiro parâmetro
    public function __construct($message = "", $code = 0, Throwable $previous = null){

        self::$defaultTemplates = [
            self::MODE_DEFAULT => [
                self::STANDARD => $message,
            ]
        ];

        parent::__construct($message, $code, $previous);

    }

}