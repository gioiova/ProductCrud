<?php

namespace app;

class Router
{
    public array $getRoutes = [];
    public  array $postRoutes = [];

    public ?Database $database = null;

    public function __construct(Database $database)
    {
        $this->database = $database;


    }


//    gadaecema url da fn funqcia
    public function get($url,$fn)
    {
        //roca url iqneba es gamoidzaxe fn funqcia
        $this->getRoutes[$url] = $fn;


    }

    public function post($url,$fn)
    {
        //roca url iqneba es gamoidzaxe fn funqcia
        $this->postRoutes[$url] = $fn;

    }

    public function resolve()
    {
        //get movida tu posti inaxeba $methodshi
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $url = $_SERVER['PATH_INFO'] ?? '/';

        if($method === 'get') {
            $fn = $this->getRoutes[$url] ?? null;

        }else{
            $fn = $this->postRoutes[$url] ?? null;
        }

        //tu funqcia ar arsebobs
        if(!$fn){
            echo "Page not found";
            exit;

        }


        $fn[0] = new $fn[0];

        //this aris routeri sadac exla var
        echo call_user_func($fn,$this);


    }

    //render miigebs views da daarenderebs layoutshi
    public  function renderView($view, $params= [])
    {

        foreach ( $params as $key => $value) {
            $$key = $value;
            
        }


        ob_start();
        include __DIR__."/views/$view.php";

        //es contenti aris is rac weria $view.php-shi iqneba es create,update
        //contenti xdeba create an update php da gamodis _layout
        $content = ob_get_clean();
        include __DIR__."/views/_layout.php";


    }



}