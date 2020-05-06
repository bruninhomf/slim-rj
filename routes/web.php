<?php

/*
 * File        : AppRoutes.php
 * Description : Rotas base da aplicação
*/

use App\Middlewares\Validation\LoginValidation;
use App\Middlewares\Validation\RecoveryValidation;
use App\Middlewares\Auth\AuthenticatedMiddleware as Authenticated;


//autenticação

$this->any('/', 'AuthController:inicio')->unrestricted()->setName('auth.inicio');
$this->any('/sobre', 'AuthController:sobre')->unrestricted()->setName('auth.sobre');
$this->any('/servicos', 'AuthController:servicos')->unrestricted()->setName('auth.servicos');
$this->any('/orcamento', 'AuthController:orcamento')->unrestricted()->setName('auth.orcamento');
$this->any('/blog', 'AuthController:blog')->unrestricted()->setName('auth.blog');
$this->any('/trabalheconosco', 'AuthController:trabalheConosco')->unrestricted()->setName('auth.trabalheConosco');
$this->any('/contato', 'AuthController:contato')->unrestricted()->setName('auth.contato');

//rotas base
$this->group('/app', function(){

    //painel
    $this->any('', 'HomeController:index')->setName('home.index');

})->add(Authenticated::class);

