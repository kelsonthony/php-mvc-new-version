<?php

namespace app\core;



class RouterCore {

    private $uri;
    private $method;
    private $getArr = [];
    
    public function __construct() {
        
        $this->initialize();
        require_once('../app/config/router.php');
        $this->execute();
    }

    private function initialize() {
        
        $this->method = $_SERVER['REQUEST_METHOD'];
        //dd($_SERVER);
        //dd($this->method);
        $uri = $_SERVER['REQUEST_URI'];

        $ex = explode('/', $uri);

        $uri = $this->normalizeURI($ex);

        //dd($ex);
        //dd(array_values(array_filter($ex)));
        //dd($uri);

        for ( $i = 0; $i < UNSET_URI_COUNT; $i++ ) {
            unset($uri[$i]);
        }

        //$this->uri = $uri;
        $this->uri = implode('/', $this->normalizeURI($uri)) ;

        if(DEBUG_URI)
            dd($this->uri);
        
        //dd($uri);
        //dd(str_replace('/phpmvc/php-standard-mvc/', '', $uri));
    }

    private function get($router, $call) {
        
        $this->getArr[] = [
            'router' => $router,
            'call'=> $call
        ];
    }
    
    private function execute(){
        switch($this->method){
            case 'GET':
                $this->executeGet();
                break;
            case 'POST':
                
                break;
        }
    }

    private function executeGet() {

        foreach($this->getArr as $get){
            $r = substr($get['router'], 1);

            if( substr($r, -1) == '/' ){
                $r = substr($r, 0, -1);
            }

            if( $r == $this->uri ) {
                if( is_callable($get['call']) ) {
                    $get['call']();
                    break;
                }else{
                    $this->executeController($get['call']);
                }
            }
        }
    }

    private function executeController($get) {
        //dd($get);
        $ex = explode('@', $get);
        //dd($ex);
        if(!isset($ex[0]) || !isset($ex[1])) {

            (new \app\controller\MessageController)->message('Dados inválidos', 'Controller ou método não encontrado: ' . $get, 404);

            return; 
        }

        $cont = 'app\\controller\\' . $ex[0];
        //dd($cont);
        if( !class_exists($cont) ){
            (new \app\controller\MessageController)->message('Dados inválidos', 'Controller não encontrado: ' . $get, 404);
            return;
        }

        if( !method_exists($cont, $ex[1]) ) {
            (new \app\controller\MessageController)->message('Dados inválidos', 'Método não encontrado: ' . $get, 404);
            return;
        }

        call_user_func_array([
            new $cont,
            $ex[1]
        ], []);
    }

    private function normalizeURI($arr) {

        return array_values(array_filter($arr));
    }

}