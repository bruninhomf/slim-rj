<?php

/*
 * File        : AuthController.php
 * Description : Controller de autenticação de usuários
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Controllers;

use App\Extensions\Support\Date;
use App\Extensions\Support\Str;
use App\Util\Mailer;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller {

    //usuário autenticado
    protected $user;

    //erros de autenticação
    protected $errors;

    /*
    |--------------------------------------------------------------------------
    | Inicio
    |--------------------------------------------------------------------------
    */
    public function inicio(Request $request, Response $response){

        if($request->isPost()){
            //usuário não existe
            $this->flash('error', 'Incorrect username or password.');

            return $response->withRedirect(
                $this->route('auth.inicio')
            );

        }else{
            
            //tenta o login por cookie
            return $this->view('auth', 'inicio');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Sobre
    |--------------------------------------------------------------------------
    */
    public function sobre(Request $request, Response $response){

        if($request->isPost()){

            //usuário não existe
            $this->flash('error', 'The data reported does not constitute an indication of users.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.sobre')
            );

        }else{
            return $this->view('auth', 'sobre');
        }

    }
    

    /*
    |--------------------------------------------------------------------------
    | Serviços
    |--------------------------------------------------------------------------
    */
    public function servicos(Request $request, Response $response){

        if($request->isPost()){

            //usuário não existe
            $this->flash('error', 'The data reported does not constitute an indication of users.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.servicos')
            );

        }else{
            return $this->view('auth', 'servicos');
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Blog
    |--------------------------------------------------------------------------
    */
    public function blog(Request $request, Response $response){

        if($request->isPost()){

            //usuário não existe
            $this->flash('error', 'The data reported does not constitute an indication of users.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.blog')
            );

        }else{
            return $this->view('auth', 'blog');
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Orçamento
    |--------------------------------------------------------------------------
    */
    public function orcamento(Request $request, Response $response){

        if($request->isPost()){

            Mailer::to(["comercial@rjotaconservadora.com.br"])
                ->subject('Teste')
                ->template('mail.send.send_contato',$request->all())
                ->send();

            //usuário não existe
            $this->flash('error', 'The data reported does not constitute an indication of users.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.orcamento')
            );

        }else{
            return $this->view('auth', 'orcamento');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Trabalhe Conosco
    |--------------------------------------------------------------------------
    */
    public function trabalheConosco(Request $request, Response $response){

        if($request->isPost()){

            Mailer::to(["rh@rjotaconservadora.com.br"])
                ->subject('Teste')
                ->attachment('file')
                ->template('mail.send.send_contato',$request->all())
                ->send();

            //usuário não existe
            $this->flash('error', 'The data reported does not constitute an indication of users.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.trabalheConosco')
            );

        }else{
            return $this->view('auth', 'trabalheConosco');
        }

    }



    /*
    |--------------------------------------------------------------------------
    | Contato
    |--------------------------------------------------------------------------
    */
    public function contato(Request $request, Response $response){
        if($request->isPost()){
            $setor = $request->get('setor');
            $email = null;

            if ($setor == 'comercial'){
                $email = 'bruninhomf@msn.com';
            }elseif ($setor == 'financeiro'){
                $email = 'bruninhomf@yahoo.com.br';
            }elseif ($setor == 'administrativo'){
                $email = 'adm@rjotaconservadora.com.br';
            }else {
                    var_dump('alert');
            }


            Mailer::to(["$email"])
                ->subject('Teste')
                ->template('mail.send.send_contato',$request->all())
                ->send();

            //usuário não existe
            $this->flash('error', 'The data provided is invalid.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.contato')
            );

        }else{
            return $this->view('auth', 'contato');
        }

    }

}