<?php

/*
 * File        : API.php
 * Description : Rotas da API Rest
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

use App\Middlewares\Auth\TokenMiddleware;

//rotas autenticadas
$this->group('/api', function(){

    //

})->validate(TokenMiddleware::class);
