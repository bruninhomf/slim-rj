<?php

/*
 * File        : Mail.php
 * Description : Arquivo de configuração do serviço de email
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

/*
|--------------------------------------------------------------------------
| Mail
|--------------------------------------------------------------------------
|
| Configurações do serviço de envio de email da aplicação.
|
*/
define('APP_MAIL', [

    'driver'      => 'smtp',
    'host'        => 'smtp.mailtrap.io',
    'user'        => '604f886691e670',
    'pass'        => 'af9578475b6603',
    'port'        =>  2525,
    'charset'     => 'utf-8',
    'encryption'  => 'SSL',
    'debug'       => false,
    'from'        => [
        'name'    => APP_NAME,
        'address' => 'site@rjotaconservadora.com.br',
    ],
    'smtp'        => [
        'ssl'     => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ]

]);