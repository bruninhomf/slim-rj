<?php

/*
 * File        : Storage.php
 * Description : Wrapper de gerênciamento de sistema de arquivos
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Util;

use Slim\Http\Response;
use Slim\Http\UploadedFile;
use App\Extensions\Support\Filesystem;

class Storage {

    protected static $i = false;
    protected static $f = false;
    protected static $p;
    protected static $r;
    public static $mimes = [

        //imagens
        'image/bmp'                                                                 => 'bmp',
        'image/x-bmp'                                                               => 'bmp',
        'image/x-bitmap'                                                            => 'bmp',
        'image/x-xbitmap'                                                           => 'bmp',
        'image/x-win-bitmap'                                                        => 'bmp',
        'image/x-windows-bmp'                                                       => 'bmp',
        'image/ms-bmp'                                                              => 'bmp',
        'image/x-ms-bmp'                                                            => 'bmp',
        'application/bmp'                                                           => 'bmp',
        'application/x-bmp'                                                         => 'bmp',
        'application/x-win-bitmap'                                                  => 'bmp',
        'application/cdr'                                                           => 'cdr',
        'application/coreldraw'                                                     => 'cdr',
        'application/x-cdr'                                                         => 'cdr',
        'application/x-coreldraw'                                                   => 'cdr',
        'image/cdr'                                                                 => 'cdr',
        'image/x-cdr'                                                               => 'cdr',
        'zz-application/zz-winassoc-cdr'                                            => 'cdr',
        'image/gif'                                                                 => 'gif',
        'image/jpx'                                                                 => 'jp2',
        'image/jpm'                                                                 => 'jp2',
        'image/jpeg'                                                                => 'jpeg',
        'image/pjpeg'                                                               => 'jpeg',
        'image/x-icon'                                                              => 'ico',
        'image/x-ico'                                                               => 'ico',
        'image/vnd.microsoft.icon'                                                  => 'ico',
        'text/calendar'                                                             => 'ics',
        'image/jp2'                                                                 => 'jp2',
        'image/png'                                                                 => 'png',
        'image/x-png'                                                               => 'png',
        'application/x-photoshop'                                                   => 'psd',
        'image/vnd.adobe.photoshop'                                                 => 'psd',
        'image/svg+xml'                                                             => 'svg',


        //videos
        'video/3gpp2'                                                               => '3g2',
        'video/3gp'                                                                 => '3gp',
        'video/3gpp'                                                                => '3gp',
        'video/x-msvideo'                                                           => 'avi',
        'video/msvideo'                                                             => 'avi',
        'video/avi'                                                                 => 'avi',
        'video/x-f4v'                                                               => 'f4v',
        'video/x-flv'                                                               => 'flv',
        'application/x-dvi'                                                         => 'dvi',
        'video/mj2'                                                                 => 'jp2',
        'application/x-troff-msvideo'                                               => 'avi',
        'video/mp4'                                                                 => 'mp4',
        'video/mpeg'                                                                => 'mpeg',
        'video/ogg'                                                                 => 'ogg',

        //audios
        'audio/x-acc'                                                               => 'aac',
        'audio/ac3'                                                                 => 'ac3',
        'audio/x-aiff'                                                              => 'aif',
        'audio/aiff'                                                                => 'aif',
        'audio/x-au'                                                                => 'au',
        'audio/x-flac'                                                              => 'flac',
        'audio/x-m4a'                                                               => 'm4a',
        'application/vnd.mpegurl'                                                   => 'm4u',
        'audio/midi'                                                                => 'mid',
        'audio/mpeg'                                                                => 'mp3',
        'audio/mpg'                                                                 => 'mp3',
        'audio/mpeg3'                                                               => 'mp3',
        'audio/mp3'                                                                 => 'mp3',
        'audio/ogg'                                                                 => 'ogg',
        'audio/wave'                                                                => 'wav',
        'audio/wav'                                                                 => 'wav',

        //arquivos compactados
        'application/x-compressed'                                                  => '7zip',
        'application/x-gtar'                                                        => 'gtar',
        'application/x-gzip'                                                        => 'gzip',
        'application/x-rar'                                                         => 'rar',
        'application/rar'                                                           => 'rar',
        'application/x-rar-compressed'                                              => 'rar',
        'application/x-tar'                                                         => 'tar',
        'application/x-gzip-compressed'                                             => 'tgz',
        'application/x-zip'                                                         => 'zip',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/s-compressed'                                                  => 'zip',
        'multipart/x-zip'                                                           => 'zip',

        //planilhas
        'text/x-comma-separated-values'                                             => 'csv',
        'text/comma-separated-values'                                               => 'csv',
        'application/vnd.msexcel'                                                   => 'csv',
        'application/excel'                                                         => 'xl',
        'application/msexcel'                                                       => 'xls',
        'application/x-msexcel'                                                     => 'xls',
        'application/x-ms-excel'                                                    => 'xls',
        'application/x-excel'                                                       => 'xls',
        'application/x-dos_ms_excel'                                                => 'xls',
        'application/xls'                                                           => 'xls',
        'application/x-xls'                                                         => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.ms-excel'                                                  => 'xlsx',

        //documentos
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/pdf'                                                           => 'pdf',
        'text/rtf'                                                                  => 'rtf',
        'text/richtext'                                                             => 'rtx',
        'text/plain'                                                                => 'txt',
        'text/x-vcard'                                                              => 'vcf',
        'application/xml'                                                           => 'xml',
        'text/xml'                                                                  => 'xml',
        'text/xsl'                                                                  => 'xsl',

        //apresentações
        'application/powerpoint'                                                    => 'ppt',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-office'                                                 => 'ppt',
        'application/msword'                                                        => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',

    ];

    //disp
    const DOWNLOAD = "download";
    const VIEW     = "view";

    /*
    |--------------------------------------------------------------------------
    | Singleton construtor
    |--------------------------------------------------------------------------
    */
    static public function folder($folder){

        //inicializa uma instância via singleton
        if (!self::$i) { self::$i = new self(); }

        //seta pasta de trabalho
        self::$f = Filesystem::directory("~/storage/{$folder}", true);

        //retorna a instância da classe
        return self::$i;
    }

    /*
    |--------------------------------------------------------------------------
    | Salva o arquivo enviado em disco
    |--------------------------------------------------------------------------
    */
    public function upload(UploadedFile $file){

        $filename = $this->moveUploadedFile(self::$f, $file);

        if ($filename) {

            //retorna um array com os dados do arquivo
            return [
                'name' => $file->getClientFilename(),
                'size' => $file->getSize(),
                'mime' => $file->getClientMediaType(),
                'path' => str_replace(APP_DIR, '', self::$f . DIRECTORY_SEPARATOR . $filename)
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Salva um raw em disco
    |--------------------------------------------------------------------------
    */
    public static function raw($data, $name, $path){

        //cria a pasta de destino
        $path  = Filesystem::directory("~/storage/{$path}", true);

        //anexa o nome do arquivo ao caminho
        $path .= "/{$name}";

        //salva os dados em disco
        if(file_put_contents($path, $data)){
            return [
                'name' => $name,
                'path' => str_replace(APP_DIR, '', $path)
            ];
        }

        return !1;

    }

    /*
    |--------------------------------------------------------------------------
    | Faz o download de um arquivo no disco
    |--------------------------------------------------------------------------
    */
    public static function download($name, $path, $disp = "view", $mime = 'application/pdf', $delete = false){

        //new slim response
        $response = new Response(200);

        //get file realpath
        $fullpath = Filesystem::directory("~/{$path}");

        if(is_file($fullpath)){

            return ($disp == 'download') ? $response->withHeader('Content-Description','File Transfer')
                ->withHeader("Content-Disposition","attachament; filename=\"{$name}\"")
                ->withHeader('Content-Type', $mime)
                ->withHeader('Content-Transfer-Encoding', 'binary')
                ->withHeader('Content-Length', filesize($fullpath))
                ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->withHeader('Pragma', 'public')
                ->withHeader('Expires', '0')
                ->write(file_get_contents($fullpath)) : $response->withHeader('Content-type', $mime)->write(file_get_contents($fullpath));

        }else{

            return $response->getBody()->write("O arquivo selecionado não existe.");

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Exlui um arquivo em disco
    |--------------------------------------------------------------------------
    */
    public static function delete($files){
        foreach ((array) $files as $file) {

            //pega o caminho completo do arquivo
            $fullpath = Filesystem::directory("~/{$file}");

            if(is_file($fullpath)){
                @unlink(
                    $fullpath
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Move um arquivo para um diretório e atribui um nome único
    |--------------------------------------------------------------------------
    */
    public function moveUploadedFile($directory, UploadedFile $uploadedFile){
        $basename  = bin2hex(random_bytes(8));
        $filename  = sprintf('%s.%0.8s', $basename, self::$mimes[$uploadedFile->getClientMediaType()]);
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $filename;
    }

}