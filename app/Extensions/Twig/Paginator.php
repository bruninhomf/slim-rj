<?php

/*
 * File        : Paginator.php
 * Description : Extensão de paginação de resultados
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Extensions\Twig;

use Illuminate\Database\Eloquent\Builder;

class Paginator {

    //query builder
    protected $query;

    //página atual
    protected $page;

    //quantidade de resultados por página
    protected $limit;

    //quantidade de resultados
    protected $count = 0;

    //resultados
    protected $items;

    /*
    |--------------------------------------------------------------------------
    | Builder
    |--------------------------------------------------------------------------
    */
    public function __construct(Builder $query, $limit, $page = null){

        //recupera a contagem dos resultados
        $this->count = $query->count();

        //recupera a página atual
        $this->page  = filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;

        //recupera o limite de resultados por página
        $this->limit = filter_var($limit, FILTER_VALIDATE_INT) ? $limit : 100;

        //pega o limite inferior de resultados
        $first = ($this->page - 1) * $this->limit;

        //recupera os resultados
        $this->items = $query->skip($first)->take($this->limit)->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Retorna os resultados
    |--------------------------------------------------------------------------
    */
    public function items(){
        return $this->items;
    }

    /*
    |--------------------------------------------------------------------------
    | Retorna o total de resultados
    |--------------------------------------------------------------------------
    */
    public function count(){
        return $this->count;
    }

    /*
    |--------------------------------------------------------------------------
    | Seta a página atual
    |--------------------------------------------------------------------------
    */
    public function html(){

        $adjc = 2;
        $prev = $this->page - 1;
        $next = $this->page + 1;
        $last = ceil($this->count / $this->limit);
        $pent = $last - 1;

        $html = "<div class='btn-group pull-right'>";

        if($last > 1){

            //btn página anterior
            $html .= ($this->page > 1) ?
                "<a class='btn btn-default go-page' data-page='{$prev}'><i class='fa fa-arrow-left'></i></a>" :
                "<a class='btn btn-default' disabled='disabled'><i class='fa fa-arrow-left'></i></a>";

            if ($last < 7 + ($adjc * 2)) {

                for ($counter = 1; $counter <= $last; $counter++) {

                    $html .= ($counter == $this->page) ?
                        "<a class='btn btn-sec'>{$counter}</a>" :
                        "<a class='btn btn-default go-page' data-page='{$counter}'>{$counter}</a>";
                }

            } elseif ($last > 5 + ($adjc * 2)) {

                if ($this->page < 1 + ($adjc * 2)) {

                    for ($counter = 1; $counter < 4 + ($adjc * 2); $counter++) {
                        $html .= ($counter == $this->page) ?
                            "<a class='btn btn-sec'>{$counter}</a>" :
                            "<a class='btn btn-default go-page' data-page='{$counter}'>{$counter}</a>";
                    }

                    $html .= "<a class='btn btn-default'>...</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='{$pent}'>{$pent}</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='{$last}'>{$last}</a>";
                    
                } elseif ($last - ($adjc * 2) > $this->page && $this->page > ($adjc * 2)) {

                    $html .= "<a class='btn btn-default go-page' data-page='1'>1</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='2'>2</a>";
                    $html .= "<a class='btn btn-default'>...</a>";

                    for ($counter = $this->page - $adjc; $counter <= $this->page + $adjc; $counter++) {

                        $html .= ($counter == $this->page) ?
                            "<a class='btn btn-sec'>{$counter}</a>" :
                            "<a class='btn btn-default go-page' data-page='{$counter}'>{$counter}</a>";

                    }

                    $html .= "<a class='btn btn-default'>..</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='{$pent}'>{$pent}</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='{$last}'>{$last}</a>";

                } else {

                    $html .= "<a class='btn btn-default go-page' data-page='1'>1</a>";
                    $html .= "<a class='btn btn-default go-page' data-page='2'>2</a>";
                    $html .= "<a class='btn btn-default'>...</a>";

                    for ($counter = $last - (2 + ($adjc * 2)); $counter <= $last; $counter++) {

                        $html .= ($counter == $this->page) ?
                            "<a class='btn btn-sec'>{$counter}</a>" :
                            "<a class='btn btn-default go-page' data-page='{$counter}'>{$counter}</a>";

                    }

                }
            }

            //btn próxima página
            $html .= ($this->page < $counter - 1) ?
                "<a class='btn btn-default go-page' data-page='{$next}'><i class='fa fa-arrow-right'></i></a>" :
                "<a class='btn btn-default' disabled='disabled'><i class='fa fa-arrow-right'></i></a>";

        }

        $html .= "</div>";

        return $html;

    }

}