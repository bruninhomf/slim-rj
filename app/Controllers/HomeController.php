<?php

/*
 * File        : HomeController.php
 * Description : Controller da página inicial
 * Author      : Bruno Firmiano <bruno.firmiano@inovedados.com.br>
*/

namespace App\Controllers;

use Slim\Http\Request;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        return $this->view('home', 'index');
    }

}