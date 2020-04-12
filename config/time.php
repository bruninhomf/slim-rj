<?php

/*
 * File        : Time.php
 * Description : Arquivo com constântes de data e hora
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
|
| Especifica o timezone em uso para as funções de data do PHP além de
| definir a zona para a classe time.
|
*/
define('APP_TIMEZONE', 'America/Sao_Paulo');

/*
|--------------------------------------------------------------------------
| Timestamps
|--------------------------------------------------------------------------
|
| Array com constantes de data e hora comumente usados no sistema
| ***constante
*/
define('CURRENT_DATE'      , date('Y-m-d'));
define('CURRENT_DATE_TIME' , date('Y-m-d H:i:s'));
define('CURRENT_DAY'       , date('d'));
define('CURRENT_MONTH'     , date('m'));
define('CURRENT_YEAR'      , date('Y'));
define('CURRENT_HOUR'      , date('H'));
define('CURRENT_WEEKLY_DAY', date("w", mktime(0,0,0,CURRENT_MONTH,CURRENT_DAY,CURRENT_YEAR))); // dia da semana 0 = dom / 6 = sab
define('CURRENT_YEAR_MONTH', date('Y-m'));