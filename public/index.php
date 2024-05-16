<?php
use app\controllers\ProductController;
use app\Router;

//shemogvaq autoload
require_once __DIR__.'/../vendor/autoload.php';

$database = new \app\Database();


//create instance of router class
$router = new Router($database);

$router->get('/',[ProductController::class,'index']);

$router->get('/products',[ProductController::class,'index']);
$router->get('/products/index',[ProductController::class,'index']);

//rodesac get requesti mova createze mashin wadi da gamoidzaxe ProductControler klassis create() funqcia
//get aris creates formis darendereba post ki sheqmna asevea updatezec,deletezec
$router->get('/products/create',[ProductController::class,'create']);
$router->post('/products/create',[ProductController::class,'create']);

$router->get('/products/update',[ProductController::class,'update']);
$router->post('/products/update',[ProductController::class,'update']);

$router->post('/products/delete',[ProductController::class,'delete']);

$router->resolve();

