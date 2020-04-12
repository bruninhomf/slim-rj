<?php

/*
 * File        : Utils.php
 * Description : Extensão de integração de funções PHP nativas com o Twig
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Twig;

use Slim\Views\TwigExtension;
use Illuminate\Database\Capsule\Manager as SQL;
use Twig\TwigFunction;

class Utils extends TwigExtension {

    //funções nativas do php
    protected $functions = [
        'print_r'
    ];

    protected $export   = [];

    /*
    |------------------------------------------------------------------------------
    | Twig Extension
    |------------------------------------------------------------------------------
    */
    public function getName(){
        return 'utils';
    }

    /*
    |------------------------------------------------------------------------------
    | Callbacks
    |------------------------------------------------------------------------------
    */
    public function getFunctions(){

        foreach ($this->functions as $function) {
            $this->export[] = new TwigFunction($function, $function);
        }

        return array_merge($this->export, [
            new TwigFunction('options', [$this, 'options']),
            new TwigFunction('asset', [$this, 'asset']),
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o caminho para um arquivo da pasta assets
    |--------------------------------------------------------------------------
    */
    public static function asset($path, $local = false){
        return (!$local) ? APP_URL . "/assets/{$path}" : "file://" . APP_DIR . "public/assets/{$path}";
    }

    /*
    |--------------------------------------------------------------------------
    | Gerador de options para select
    |--------------------------------------------------------------------------
    */
    public function options($table, $where, $value, $content, $selected = null, $apend = null, $withDefault = true){
        $default = '<option value="">Selecione um item</option>'; $export = '';
        $options = SQL::select("SELECT * FROM {$table} {$where}");
        if ($options) {
            foreach ($options as $option){
                $match   = ($selected == $option->$value) ? 'selected="selected"' : '';
                $export .= "<option value=\"{$option->$value}\" {$match}>";
                if(is_array($content)){
                    foreach ($content as $k){
                        $export .= (!empty($option->{$k})) ? $option->{$k} : $k;
                    }
                }else{
                    $export .= $option->{$content};
                }
                $export .= " {$apend}</option>";
            }
        }
        echo ($withDefault) ? $default.$export : $export;
    }


}


