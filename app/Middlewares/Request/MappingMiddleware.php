<?php

/*
 * File        : MappingMiddleware.php
 * Description : Middleware de mapeamento de objetos nas rotas
*/


namespace App\Middlewares\Request;

use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Middlewares\Middleware;

class MappingMiddleware extends Middleware {

    //mapeamento de objetos
    protected $mapping = [
        'user' => User::class
    ];

    /*
    |--------------------------------------------------------------------------
    | Slim Callable
    |--------------------------------------------------------------------------
    */
    public function __invoke(Request $request, Response $response, $next){

        //recupera o objeto da rota
        $route = $request->getAttribute('route');

        if(!is_null($route)){

            //itera os objetos mapeados
            foreach ($this->mapping as $k => $model){

                //recupera o parâmetro da rota que foi mapeado
                $mapped = $route->getArgument($k);

                //verifica se o objeto foi mapeado na rota e se ele existe no banco de dados
                if(!empty($mapped)){

                    //localiza o objeto no banco
                    $obj = $model::find($mapped);

                    if($obj){

                        //adiciona o objeto ao container
                        $this->container[$k] = function() use($obj){
                            return $obj;
                        };

                    }else{

                        //obtem as propriedades da model via reflection
                        try {
                            $name = (new \ReflectionClass($model))->getProperty('mapping');
                        } catch (\ReflectionException $e) {
                            $name = 'object';
                        }

                        //retorna o erro de objeto inválido
                        return ($request->isGet()) ? $this->container->view->render($response, 'layouts/error.twig', [
                            'error' => "O {$name} selecionado é inválido."
                        ]) : $response->withStatus(400)->withJson([
                            'result' => false,
                            'error' => "O {$name} selecionado é inválido."
                        ]);
                    }

                }
            }

        }

        //salva o método de requisição atual
        $this->container['method'] = function () use($request){
            return $request->getMethod();
        };

        //continua a stack
        return $next($request, $response);

    }

}