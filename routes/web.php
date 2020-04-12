<?php

/*
 * File        : AppRoutes.php
 * Description : Rotas base da aplicação
*/

use App\Middlewares\Validation\LoginValidation;
use App\Middlewares\Validation\RecoveryValidation;
use App\Middlewares\Auth\AuthenticatedMiddleware as Authenticated;


//autenticação

$this->any('/Inicio', 'AuthController:inicio')->unrestricted()->setName('auth.inicio');
$this->any('/Sobre', 'AuthController:sobre')->unrestricted()->setName('auth.sobre');
$this->any('/Servicos', 'AuthController:servicos')->unrestricted()->setName('auth.servicos');
$this->any('/Orcamento', 'AuthController:orcamento')->unrestricted()->setName('auth.orcamento');
$this->any('/Blog', 'AuthController:blog')->unrestricted()->setName('auth.blog');
$this->any('/Trabalheconosco', 'AuthController:trabalheConosco')->unrestricted()->setName('auth.trabalheConosco');
$this->any('/Contato', 'AuthController:contato')->unrestricted()->setName('auth.contato');

//rotas base
$this->group('/app', function(){

    //painel
    $this->any('', 'HomeController:index')->setName('home.index');

})->add(Authenticated::class);

