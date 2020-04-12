<?php

/*
 * File        : Crypto.php
 * Description : Arquivo de configuração de criptografia da aplicação
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

/*
|--------------------------------------------------------------------------
| Tipo de criptografia
|--------------------------------------------------------------------------
|
| Determina o tipo de serviço de criptografia em uso pelo sistema.
|
*/
define('APP_CHIPER', PASSWORD_BCRYPT);

/*
|--------------------------------------------------------------------------
| Mascara de criptografia
|--------------------------------------------------------------------------
|
| Determina a mascara de concatenação criptográfica em uso pelo sistema.
| Ao salvar os dados do usuário o caractére @ é substituido pelo texto
| plano da senha e depois posteriormente encryptado conforme o cipher
| configurado.
|
*/
define('APP_CRYPTO_MASK', 'hALG*3?[&B^P{@]DOCihh{.J*;gz0');

/*
|--------------------------------------------------------------------------
| Token da API
|--------------------------------------------------------------------------
|
| Enviar o token de autenticação em todas as requisições em /api.
|
*/
define('API_TOKEN', 'Nxr{c:hh5)f;G?F?Kt>q(C{OqTbub[');