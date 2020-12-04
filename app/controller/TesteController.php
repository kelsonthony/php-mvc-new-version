<?php

namespace app\controller;

use app\core\Controller;

class TesteController extends Controller
{

    public function __connstruct() {
        //echo "Construtor";
    }

    public function index()
    {
        $this->load('home/main', [
            'nome' => 'Kelson'
        ]);
    }

    public function seta()
    {
        $this->load('home/main', [
            'nome' => 'Kelson'
        ]);
    }
}
