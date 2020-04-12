<?php

/*
 * File        : Database.php
 * Description : Arquivo de configuração de banco de dados
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

/*
|--------------------------------------------------------------------------
| Database Connections
|--------------------------------------------------------------------------
|
| Configuração das conexões de banco de dados de teste e produção em uso
| pela aplicação
|
*/
define('APP_DATABASE', [

    'default' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'port'      => '3306',
        'database'  => 'bittech-wallet',
        'username'  => '',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'default'   => true,
        'options'   => [
            \PDO::ATTR_EMULATE_PREPARES => true
        ]
    ]

]);