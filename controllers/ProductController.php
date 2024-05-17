<?php

namespace app\controllers;


use app\models\Product;
use app\Router;
use const http\Client\Curl\Versions\IDN;

class  ProductController
{

    public function index(Router $router)
    {
        $keyword =   $_GET['search'] ?? '';

        $products = $router->database->getProducts($keyword);
        $router->renderView('/products/index',[
            'products'=>$products,
            'keyword'=>$keyword
        ]);
    }

    public function create(Router $router)
    {
        $productData = [
            'image' =>'',
            'title'=> '',
            'description'=> '',
            'price' => ''
        ];

        $errors = [];

        if($_SERVER['REQUEST_METHOD']=== 'POST'){

            $productData['title']  = $_POST['title'];
            $productData['description']  = $_POST['description'];
            $productData['price']  = $_POST['price'];
            $productData['imageFile'] = $_FILES['image'] ?? null;

            //Form validation

            if(empty(trim($productData['title']))){
                $errors[] = 'Product title is required';
            }

            if(!$productData['price']){
                $errors[] = 'Product price is required';
            }


            if(empty($errors)) {

                $product = new Product();
                $product->load($productData);
                $product->save();
                header('LOCATION:/products');
                exit;
            }

        }

        $router->renderView('products/create',[
            'product'=>$productData,
            'errors'=>$errors
        ]) ;
    }

    public function update(Router $router)
    {
        $id = $_GET['id'] ?? null;
        if(!$id){
            header('Location:/products');
            exit;
        }

        $productData = $router->database->getProductById($id);

        if($_SERVER['REQUEST_METHOD']=== 'POST'){

            $productData['title']  = $_POST['title'];
            $productData['description']  = $_POST['description'];
            $productData['price']  = $_POST['price'];
            $productData['imageFile'] = $_FILES['image'] ?? null;


            $product = new Product();
            $product->load($productData);
            $product->save();
            header('LOCATION:/products');
            exit;
        }


        $router->renderView('/products/update',[
            'product'=>$productData
        ]) ;
    }


    public function delete(Router $router)
    {
        $id = $_POST['id'] ?? null;
        if(!$id){
            header('Location:/products');
            exit;
        }

        if($router->database->deleteProduct($id)) {


            header('LOCATION:/products');
            exit;
        }


    }

}