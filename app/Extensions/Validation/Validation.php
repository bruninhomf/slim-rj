<?php

/*
 * File        : Validator.php
 * Description : Wrapper de validação de formulários
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validation {

    protected $errors;
    protected $lang = [

        '{{name}} must be valid' => 'Um dos campos de {{name}} contém um valor inválido.',
        'These rules must pass for {{name}}'  => 'Essas reqras devem passar por "{{name}}".',
        '{{name}} must have a length lower than {{maxValue}}'  => 'O campo "{{name}}" deve ter no máximo {{maxValue}} caracteres.',
        '{{name}} must be a float number'     => 'O campo "{{name}}" tem que ser um valor monetário.',
        '{{name}} must not be blank'  => 'O campo "{{name}}" é obrigatório.',
        '{{name}} must be positive'  => 'O campo "{{name}}" tem que ser um número positivo.',
        '{{name}} must be an integer number' => 'O campo "{{name}}" deve ser um número inteiro.',
        '{{name}} must be a string' => 'O campo "{{name}}" deve ser um texto.',
        '{{name}} must have a length between {{minValue}} and {{maxValue}}' => 'O campo "{{name}}" deve conter um valor entre {{minValue}} e {{maxValue}} caracteres.',
        '{{name}} must be a valid date. Sample format: {{format}}' => 'O campo "{{name}}" deve conter uma data válida.',
        '{{name}} must be less than or equal to {{interval}}' => 'O campo "{{name}}" deve conter um valor menor ou igual a {{interval}}.',
        '{{name}} must be lower than {{maxValue}}' => 'O campo "{{name}}" deve ser menor que {{maxValue}}.',
        'All of the required rules must pass for {{name}}' => 'O campo "{{name}}" deve obedecer as regras abaixo:',
        '{{name}} must be numeric' => 'O campo "{{name}}" deve conter somente números.',
        '{{name}} must be an array' => 'Você deve selecionar ao menos um {{name}}.',
        '{{name}} must be a valid CPF number' => 'O campo "{{name}}" deve conter um CPF válido.',
        '{{name}} must be in {{haystack}}' => 'A opção selecionada no campo "{{name}}" é inválida.',
        '{{name}} must be a valid CNPJ number' => 'O campo "{{name}}" deve conter um CNPJ válido.',
        '{{name}} must have {{mimetype}} mimetype' => 'O campo "{{name}}" não contém um tipo de arquivo permitido.',
        '{{name}} must be lower than {{maxSize}}'  => 'O campo "{{name}}" não pode conter um arquivo maior que {{maxSize}}.',
        '{{name}} must have {{extension}} extension' => 'O campo "{{name}}" deve conter um arquivo com a extensão "{{extension}}".',
        '{{name}} must be valid email' => 'O campo "{{name}}" deve conter um email válido.',
        '{{name}} must not start with ({{startValue}})' => 'O campo "{{name}}" não pode começar com o valor {{startValue}}.',
        '{{name}} must be an URL' => 'O campo "{{name}}" deve conter uma URL válida.',
        '{{name}} must have a length greater than {{minValue}}' => 'O campo "{{name}}" deve conter no mínimo {{minValue}} caracteres.',
        '{{name}} must be a valid telephone number' => 'O {{name}} inserido não e válido.',
        '{{name}} must be greater than or equal to {{interval}}' => 'O campo "{{name}}" deve conter uma data maior que {{interval}}.',
        '{{name}} must be of the type array' => 'Você deve selecionar ou informar ao menos um {{name}}.',

    ];

    /*
    |--------------------------------------------------------------------------
    | Field Validation
    |--------------------------------------------------------------------------
    */
    public function validate($request, array $rules){

        //field validation
        foreach ($rules as $field => $rule){
            try{
                $rule->assert($request->getParam($field));
            }catch (NestedValidationException $exception){

                //translator callback
                $exception->setParam('translator', function ($message){
                    return isset($this->lang[$message]) ? $this->lang[$message] : $message;
                });

                $this->errors[$field] = $exception->getMessages();

            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o resultado da validação
    |--------------------------------------------------------------------------
    */
    public function success(){
        return empty($this->errors) ? true : false;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna os erros de validação
    |--------------------------------------------------------------------------
    */
    public function getErrors(){
        return $this->errors;
    }

}