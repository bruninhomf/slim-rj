<?php

/*
 * File        : Index.php
 * Description : Task Handler
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

ob_start(); session_start();

//carrega o autoload do composer
require "vendor/autoload.php";

use Slim\App;
use App\Framework\Kernel;
use App\Extensions\Support\Arr;
use App\Extensions\Support\Route;
use App\Controllers\TaskController;

//inicializa a aplicação
$app = new Kernel([
    'settings' => [
        'debug' => APP_DEBUG,
        'determineRouteBeforeAppMiddleware' => !1,
        'displayErrorDetails'    => APP_DEBUG,
        'addContentLengthHeader' => !1,
    ]
]);

//configura a aplicação
$app->config(!0);

//cron controller
$tasks   = new TaskController($app->getContainer());

//command line option
$option  = Arr::get(getopt("r:"), 'r');

switch ($option){

    default : print 'comando inválido';

}

print "success";

ob_end_flush();
