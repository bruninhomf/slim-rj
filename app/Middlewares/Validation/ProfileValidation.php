<?php

/*
 * File        : RecoveryValidation.php
 * Description : Middleware de atualização de dados cadastrais
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Middlewares\Validation;

use App\Util\Auth;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;
use Respect\Validation\Validator as Mask;

class ProfileValidation extends Middleware {

    /*
    |--------------------------------------------------------------------------
    | Validação de campos
    |--------------------------------------------------------------------------
    */
    public function fields(Request $request){

        //formulário
        $this->form  = [
            'name'  => Mask::notBlank()->length(1, 100)->setName('Full Name'),
            'email' => Mask::email()->setName('Email')
        ];

        return $this->validate($request);

    }

    /*
    |--------------------------------------------------------------------------
    | Validação de alteração de email
    |--------------------------------------------------------------------------
    */
    public function email(Request $request){

        //localiza o email duplicado
        $user = User::where([
            ['email',  '=', $request->field('email')],
            ['idUser', '!=', Auth::idUser()]
        ])->first();

        if($user){
            $this->errors[] = ["The email you entered is already in use."]; return !1;
        }

        return !0;

    }

    /*
    |--------------------------------------------------------------------------
    | Validação de alteração de senha
    |--------------------------------------------------------------------------
    */
    public function passwords(Request $request){

        //pega as senhas
        $new = $request->field('new-password');
        $cnf = $request->field('cnf-password');

        if(
            !empty($new) and $new !== $cnf
        ){
            $this->errors[] = ["The password confirmation field is different from the chosen password."]; return !1;
        }

        return !0;

    }

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){
        if($request->isPost()){
            return (
                $this->fields($request) and
                $this->email($request) and
                $this->passwords($request)
            ) ? $response = $next($request, $response) : $this->error($response);
        }else{
            return $response = $next($request, $response);
        }
    }

}
