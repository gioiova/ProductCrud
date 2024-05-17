<?php

namespace app\models;

use app\Database;
use app\helpers\UtilHelper;

class Product
{
    public ?int $id = null;
    public string $title;
    public string $description;
    public float $price;
    public array $imageFile;

    public  ?string $imagePath = null;
    public  function  load($data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->price = floatval($data['price']);
        $this->imageFile = $data['imageFile'];
        $this->imagePath = $data['image'] ?? null;

    }

    public function save()
    {

        //creating image directory
        if(!is_dir(__DIR__.'/../public/images')){
            mkdir(__DIR__.'/../public/images');

        }


        //create
        if(empty($errors)){

            //creating imagepath with random name
            if($this->imageFile && $this->imageFile['tmp_name']){


                //if image exists deleting while update product
                if($this->imagePath){
                    unlink(__DIR__.'/../public/'.$this->imagePath);
                }

                $this->imagePath = 'images/'.UtilHelper::randomString(8).'/'.$this->imageFile['name'];
                mkdir(dirname(__DIR__.'/../public/'.$this->imagePath));

                //adding image to directory
                move_uploaded_file($this->imageFile['tmp_name'],__DIR__.'/../public/'.$this->imagePath);

            }
        }

        $db = Database::$db;
        if($this->id){
            //update
            $db->updateProduct($this);

        }else{
            //create
            $db = Database::$db;
            $db->createProduct($this);

        }



    }

}
