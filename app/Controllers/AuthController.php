<?php

/*
 * File        : AuthController.php
 * Description : Controller de autenticação de usuários
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Controllers;

use App\Extensions\Support\Arr;
use App\Extensions\Support\Date;
use App\Extensions\Support\Str;
use App\Util\Mailer;
use App\Util\Storage;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

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

            Mailer::to(["bruninhomf1@gmail.com"])
                ->subject('Teste')
                ->template('mail.send.send_orcamento',$request->all())
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
                ->template('mail.send.send_trabalhe_conosco',$request->all())
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

            $attachment = Arr::get($request->getUploadedFiles(), 'attachment');

            $setor = $request->get('setor');
            $email = null;

            if ($setor == 'atendimento'){
                $email = 'atendimento@rjotaconservadora.com.br';
            }elseif ($setor == 'comercial'){
                $email = 'comercial@rjotaconservadora.com.br';
            }else {
                    var_dump('alert');
            }

            $mail = Mailer::to(["$email"])
                ->subject('Teste')
                ->template('mail.send.send_contato',$request->all());

            if ($attachment) {
                $mime = Arr::get(explode('/',$attachment->getClientMediaType()), 1);
                $mail->attachment(['path' => $attachment->file, 'name' => "anexo.{$mime}"]);
            }

            $mail->send();

            //usuário não existe
            $this->flash('success', 'Mensagem enviada com sucesso.');

            //redireciona para a recuperação
            return $response->withRedirect(
                $this->route('auth.contato')
            );

        }else{
            $this->flash('error', 'Error');
            return $this->view('auth', 'contato');
        }

    }

}