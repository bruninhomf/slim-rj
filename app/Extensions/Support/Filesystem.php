<?php

/*
 * File        : Filesystem.php
 * Description : Extensão do manipulador de sistema de arquivos
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Support;

class Filesystem {

    /*
    |--------------------------------------------------------------------------
    | Retorna o tamanho da pasta em MB
    |--------------------------------------------------------------------------
    */
    public static function pathSize($path) {
        $size = 0;
        foreach (glob(rtrim($path, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : self::pathSize($each);
        }
        return $size;
    }

    /*
    |--------------------------------------------------------------------------
    | Formata o total de bytes do arquivo em KB, MB ou GB.
    |--------------------------------------------------------------------------
    */
    public static function fileSize($path){

        //pega o tamanho do arquivo em bytes
        $bytes  = filesize($path);

        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' Bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' Byte';
        } else {
            $bytes = '0 Bytes';
        }

        return $bytes;
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve um caminho do sistema de arquivos
    |--------------------------------------------------------------------------
    */
    public static function directory($path, $make = false){
        $path = str_replace(['~/', '\\', '/'], [APP_DIR, DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
        if ($make && !file_exists($path) && !is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o conteúdo do arquivo
    |--------------------------------------------------------------------------
    */
    public static function read($path){
        return file_get_contents(self::directory("~/{$path}"));
    }

    /*
    |--------------------------------------------------------------------------
    | Exclusão recursiva de diretório ou arquivo
    |--------------------------------------------------------------------------
    */
    public static function delete($path){

        //resolve o caminho do diretório
        $path = self::directory("~/storage/{$path}");

        if(is_file($path)){
            return @unlink($path);
        }elseif (is_dir($path)){
            return self::rmdir($path);
        }

        return false;

    }

    /*
    |--------------------------------------------------------------------------
    | Remove os arquivos do diretório e subdiretórios
    |--------------------------------------------------------------------------
    */
    protected static function rmdir($path){
        if (is_dir($path)) {
            $objects = scandir($path);
            foreach ($objects as $object) {
                if ($object != "." && $object !="..") {
                    if (filetype($path . DIRECTORY_SEPARATOR . $object) == "dir") {
                        self::rmdir($path . DIRECTORY_SEPARATOR . $object);
                    } else {
                        unlink($path . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            return rmdir($path);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Lê o conteúdo de uma pasta carregando as classes para o bootloader
    |--------------------------------------------------------------------------
    */
    public static function autoload($base, &$container){

        $dir = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, APP_DIR . $base);

        if(is_dir($dir) && $handle = opendir($dir)) {

            while (false !== ($file = readdir($handle))) {

                if(strpos($file,".php")){

                    $callable  = str_replace('.php', '', last(explode('/', $dir."/".$file)));
                    $namespace = ucfirst($base.$callable);

                    $container[$callable] = function ($container) use ($namespace){
                        return new $namespace($container);
                    };

                }

            }

            closedir($handle);

        }
    }

}