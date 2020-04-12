<?php

/*
 * File        : Mailer.php
 * Description : Wrapper de envio de email
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Util;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer {

    private $mailer;
    private static $i = false;

    /*
    |--------------------------------------------------------------------------
    | Singleton construtor
    |--------------------------------------------------------------------------
    */
    public static  function to($addresses, $bcc = false){

        if(!self::$i){

            //inicializa uma instância via singleton
            self::$i = new self();

            //inicializa o PHPMailer
            self::$i->mailer = new PHPMailer();

            //inicializa a configuração
            $config = APP_MAIL;

            //server
            self::$i->mailer->Host        = $config['host'];
            self::$i->mailer->Username    = $config['user'];
            self::$i->mailer->Password    = $config['pass'];
            self::$i->mailer->Port        = $config['port'];
            self::$i->mailer->SMTPSecure  = $config['encryption'];
            self::$i->mailer->CharSet     = $config['charset'];
            self::$i->mailer->SMTPAuth    = true;
            self::$i->mailer->SMTPOptions = $config['smtp'];
            self::$i->mailer->isSMTP();

            //headers
            self::$i->mailer->From        = $config['from']['address'];
            self::$i->mailer->FromName    = $config['from']['name'];

            //content-type
            self::$i->mailer->isHTML();

            //debug log
            if($config['debug']){
                self::$i->mailer->SMTPDebug = 3;
            }

            foreach ((array) $addresses as $address){
                ($bcc) ? self::$i->mailer->addBCC($address) : self::$i->mailer->addAddress($address);
            }


        }

        return self::$i;

    }

    /*
    |--------------------------------------------------------------------------
    | Configura o assunto
    |--------------------------------------------------------------------------
    */
    public function subject($subject){
        self::$i->mailer->Subject = $subject;
        return $this;
    }


    /*
    |--------------------------------------------------------------------------
    | Carrega um template twig como corpo do email
    |--------------------------------------------------------------------------
    */
    public function template($template, array $args = []){

        //adiciona o template renderizado
        self::$i->mailer->Body  = Template::load($template, $args);

        return $this;

    }

    /*
    |--------------------------------------------------------------------------
    | Static constructor
    |--------------------------------------------------------------------------
    */
    public function attachment($file){

        self::$i->mailer->addAttachment($file['path'], $file['name']);

        return $this;

    }

    /*
    |--------------------------------------------------------------------------
    | Configura remetentes com cópia oculta
    |--------------------------------------------------------------------------
    */
    public function bcc($mails){

        //adiciona os destinatários de cópia oculta
        foreach ((array) $mails as $mail){
            self::$i->mailer->addBCC($mail);
        }

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Envia o email
    |--------------------------------------------------------------------------
    */
    public function send(){

        //envia o email
        $sended = self::$i->mailer->send();

        //limpa as configurações para o próximo envio
        self::$i->mailer->clearAddresses();
        self::$i->mailer->clearAllRecipients();
        self::$i->mailer->clearAttachments();
        self::$i->mailer->clearCustomHeaders();
        self::$i->mailer->Body = '';

        return $sended;

    }

}
