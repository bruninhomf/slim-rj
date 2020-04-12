<?php

/*
 * File        : Kernel.php
 * Description : Application Kernel & D.I Bootstrapper
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Framework;

use App\Util\{Auth, Mailer};

use Slim\{App, Views\Twig, Http\Request, Http\Response, Views\TwigExtension};

use Illuminate\{
    Events\Dispatcher,
    Container\Container as CapsuleContainer,
    Database\Capsule\Manager as Capsule
};

use App\Middlewares\{Auth\SessionMiddleware,
    Auth\ViewMiddleware,
    Request\MappingMiddleware,
    Request\FormMiddleware
};

use App\Extensions\{Support\Arr,
    Support\Env,
    Support\Filesystem,
    Support\Route,
    Support\Server,
    Validation\Validation,
    Slim\Guard};

use Slim\Flash\Messages;
use Respect\Validation\Validator as Respect;
use Throwable;

class Kernel extends App {

    /*
    |--------------------------------------------------------------------------
    | Setup Config
    |--------------------------------------------------------------------------
    */
    public function config($shell = !1){

        $this->environment();
        $this->logger();
        $this->database();
        $this->container();
        $this->view();
        $this->callables();
        $this->routes();

        //config by environment
        if(!$shell){
            $this->csrf();
            $this->validations();
            $this->middlewares();
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Setup Timezone By App Config
    |--------------------------------------------------------------------------
    */
    protected function environment(){

        //timezone
        date_default_timezone_set(APP_TIMEZONE);

        //limite de memoria
        ini_set('memory_limit ', '900M');
        ini_set('max_execution_time ', '300');

        //debug mode
        (APP_DEBUG == !0) ? Server::display_errors() : Server::hide_errors();

        //ssl
        if(APP_SSL == !0){
            Env::https();
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Setup Monolog
    |--------------------------------------------------------------------------
    */
    protected function logger(){

        if (!APP_DEBUG) {
            //sobrescreve o handler de erros
            $this->container['errorHandler'] = function () {

                return function(Request $request, Response $response, Throwable $exception){

                    //mensagem de erro exibida para o usuário
                    $msg = 'Erro ao processar solicitação. Tente novamente mais tarde.';

                    //dispara o email com o erro
                    Mailer::to(APP_OWNER)->subject("Monitoramento de erros - " . APP_NAME)->template('mail.logger.exception', [
                        'class' => get_class($exception),
                        'ip'        => Env::ip(),
                        'session'   => Env::session(),
                        'browser'   => Env::browser(),
                        'exception' => $exception,
                    ])->send();

                    //retorna a resposta para o usuário
                    return ($request->isPost()) ?

                        $response->withStatus(400)->withJson(['result' => false, 'error' => $msg]) :
                        $response->withStatus(500)->withHeader('Content-Type', 'text/html')->write($msg);

                };

            };

            //sobrescreve o handler de erros do php
            $this->container['phpErrorHandler'] = function () {
                return $this->container->errorHandler;
            };
        }



    }

    /*
    |--------------------------------------------------------------------------
    | Setup Eloquent
    |--------------------------------------------------------------------------
    */
    protected function database(){

        //launch capsule
        $capsule = new Capsule();

        //add connections from file
        foreach (APP_DATABASE as $k => $connection){
            $capsule->addConnection($connection, $k);
        }

        //launch model events
        $capsule->setEventDispatcher(new Dispatcher(new CapsuleContainer()));

        //add eloquent to D.I container
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $container['db'] = function() use ($capsule){
            return $capsule;
        };

    }

    /*
    |--------------------------------------------------------------------------
    | Setup Globals
    |--------------------------------------------------------------------------
    */
    protected function container(){

        //Auth
        $this->container['auth'] = function() {
            return new Auth();
        };

        //old post
        $this->container['old'] = function() {
            return Arr::get($_SESSION, 'old');
        };

        //respect validation
        $this->container['validator'] = function(){
            return new Validation();
        };

        //flash service
        $this->container['flash'] = function(){
            return new Messages();
        };

    }

    /*
    |--------------------------------------------------------------------------
    | Setup Twig View
    |--------------------------------------------------------------------------
    */
    protected function view(){

        //injeta a configuração do template engine
        $this->container['view'] = function (){

            $view = new Twig(
                Filesystem::directory('~/resources/views'), [
                'cache' => false,
                'debug' => !0
            ]);

            $view->addExtension(new TwigExtension(
                $this->container->router,
                rtrim(str_ireplace('index.php', '', $this->container['request']->getUri()->getBasePath()), '/')
            ));

            $view->addExtension(new \Twig\Extension\DebugExtension());

            return $view;

        };

    }

    /*
    |--------------------------------------------------------------------------
    | Configura as rotas
    |--------------------------------------------------------------------------
    */
    protected function routes(){

        //arquivos de rota
        $files = [ __DIR__ . "/../../routes/api.php", __DIR__ . "/../../routes/web.php"];

        foreach ($files as $file) {
            require_once $file;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Faz o autoload das controladoras
    |--------------------------------------------------------------------------
    */
    protected function callables(){

        //carrega as controllers para o container
        Filesystem::autoload("app\\Controllers\\", $this->container);

        //carrega os validators
        Filesystem::autoload("app\\Middlewares\\Validation\\", $this->container);

    }

    /*
    |--------------------------------------------------------------------------
    | Configura a proteção CSRF
    |--------------------------------------------------------------------------
    */
    protected function csrf(){

        $this->container['csrf'] = function(){
            $csrf = new Guard();
            $csrf->setPersistentTokenMode(!0);
            $csrf->setFailureCallable(function (Request $request, Response $response, $next) {
                return $response->withStatus(400)->withJson([
                    'result' => false,
                    'error'  => "O token de segurança expirou ou é inválido."
                ]);
            });
            return $csrf;
        };

    }

    /*
    |--------------------------------------------------------------------------
    | Configura as extensões de validação
    |--------------------------------------------------------------------------
    */
    protected function validations(){
        Respect::with('App\\Extensions\\Validation\\');
    }

    /*
    |--------------------------------------------------------------------------
    | Configura a stack de middlewares globais
    |--------------------------------------------------------------------------
    */
    protected function middlewares(){

        $this->add(SessionMiddleware::class);
        $this->add(FormMiddleware::class);
        $this->add(MappingMiddleware::class);
        $this->add(ViewMiddleware::class);
        $this->add($this->container->csrf);

    }

}