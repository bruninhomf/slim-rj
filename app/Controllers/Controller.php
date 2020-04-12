<?php

/*
 * File        : Controller.php
 * Description : Controller base da aplicação
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Controllers;

use App\Models\History;
use App\Util\Auth;
use App\Util\Storage;
use Illuminate\Support\Collection;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use App\Extensions\Support\Arr;
use App\Extensions\Support\Env;

abstract class Controller {

    //contâiner de dependências
    protected $container;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    public function __construct($container){

        //recupera o container de dependências
        $this->container = $container;

    }

    /*
    |--------------------------------------------------------------------------
    | Obtem uma propriedade ou objeto mapeado no container
    |--------------------------------------------------------------------------
    */
    public function __get($property){

        return $this->container->has($property) ? $this->container->{$property} : !1;

    }

    /*
    |--------------------------------------------------------------------------
    | Renderiza uma view dentro da pasta do scope ativo
    |--------------------------------------------------------------------------
    */
    public function view($folder, $file, $args = []){

        //resolve o caminho das pastas
        $folder = str_replace('.', DIRECTORY_SEPARATOR, $folder);
        $file   = str_replace('.', DIRECTORY_SEPARATOR, $file);

        //retorna um response com a view
        return $this->container->view->render((new Response())->withHeader('content-type','text/html; charset=UTF-8'),
            "{$folder}/{$file}.twig", $args
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Adiciona uma mensagem flash a sessão
    |--------------------------------------------------------------------------
    */
    public function flash($key, $message){
        $this->container->flash->addMessage($key, $message); return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma rota
    |--------------------------------------------------------------------------
    */
    public function route($router, $args = []){
        return $this->container->router->pathFor($router, $args);
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma resposta json de erro
    |--------------------------------------------------------------------------
    */
    public function error($error = false)
    {
        if($this->container->method == 'GET'){
            return $this->container->view->render((new Response())->withStatus(200)->withHeader('content-type','text/html; charset=UTF-8'),
                'layouts/error.twig', [
                    'error' => $error
                ]
            );
        }else{
            return (new Response())->withStatus(400)->withJson([
                'result' => false,
                'error'  => $error
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma resposta json de sucesso
    |--------------------------------------------------------------------------
    */
    public function success($id = null, $content = null, $log = false)
    {

        //envia o log se houver
        if(!empty($log)){

            History::create([
                'user_id' => $this->auth->id,
                'action'  => $log,
                'ip'      => Env::ip(),
            ]);

        }

        //retorna o json de sucesso
        return (new Response())->withStatus(200)->withJson([
            'id'      => $id,
            'result'  => true,
            'content' => $content
        ], 200);

    }

    /*
    |--------------------------------------------------------------------------
    | Retorna uma mensagem de erro
    |--------------------------------------------------------------------------
    */
    public function info($info)
    {
        return $this->container->view->render((new Response())->withStatus(200)->withHeader('content-type','text/html; charset=UTF-8'),
            'layouts/error.twig', ['error' => $info]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Retorno de dados para a API
    |--------------------------------------------------------------------------
    */
    public function json($data){
        return (new Response())->withStatus(200)->withJson($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtem um anexo enviado
    |--------------------------------------------------------------------------
    */
    protected function attachment(Request $request, $folder, $mime = "application/pdf", $key = 'attachment'){

        //recupera o anexo
        $attachment = Arr::get($request->getUploadedFiles(), $key);

        if($attachment instanceof UploadedFile and $attachment->getError() == UPLOAD_ERR_OK and $attachment->getClientMediaType() == $mime){
            return Storage::folder($folder)->upload($attachment);
        }else{
            return false;
        }

    }

}
