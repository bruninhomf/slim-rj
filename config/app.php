<?php

/*
 * File        : App.php
 * Description : Arquivo de configuração base da aplicação
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

/*
|--------------------------------------------------------------------------
| Nome da aplicação e empresa dona do plano
|--------------------------------------------------------------------------
|
| Nome da aplicação.
|
*/
define('APP_NAME', 'Inove Dados');

/*
|--------------------------------------------------------------------------
| Url da aplicação
|--------------------------------------------------------------------------
*/
define('APP_URL', 'http://localhost/projetos/slim/rj/public');

/*
|--------------------------------------------------------------------------
| Diretório Base
|--------------------------------------------------------------------------
|
| Configuração do caminho base para o diretório da aplicação com base no
| sistema operacinal.
|
*/
define('APP_DIR', (PHP_OS == 'Linux')
    ? '/var/www/rj.herteck.com.br' : 'C:\\xampp\\htdocs\\projetos\\slim\\rj\\'
);

/*
|--------------------------------------------------------------------------
| Modo de depuração
|--------------------------------------------------------------------------
|
| Quando ativado mostra tracebacks detalhados cada vez que uma excessão
| ou warning é lançado. Do contrário uma página genêrica de erro é
| exibida.
|
*/
define('APP_DEBUG', true);

/*
|--------------------------------------------------------------------------
| SSL
|--------------------------------------------------------------------------
|
| Força a aplicação a usar conexões SSL.
|
*/
define('APP_SSL' , false);

/*
|--------------------------------------------------------------------------
| Owners
|--------------------------------------------------------------------------
|
| Determina os e-mails que receberão o monitoramento de erros
|
*/
define('APP_OWNER', [
    'contato@inovedados.com.br'
]);
