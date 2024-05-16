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
        $this->price = $data['price'];
        $this->imageFile = $data['imageFile'];
        $this->imagePath = $data['image'] ?? null;

    }

    public function save()
    {


        //tu ar arsebobs images directory iqmneba
        //validate_productis relatiurad rom shemqnas images directtorya gamovikenete __dir__
        if(!is_dir(__DIR__.'/../public/images')){
            mkdir(__DIR__.'/../public/images');

        }




        //Form validation

        if(!$this->title){
            $errors[] = 'Product title is required';
        }

        if(!$this->price){
            $errors[] = 'Product price is required';
        }

        //tu erorebis masivi carielia vinaxavt surats
        if(empty($errors)){


            //imagepath aris suratis mdebareobis misamarti images/5BoJYGC9/s10.jpg
            //suratis atvirtvisas iqmeneba imagepath randomuli saxelis directoriit
            if($this->imageFile && $this->imageFile['tmp_name']){

                //tu surati arsebobs washlis arsebuls surats radgan axali daematos
                if($this->imagePath){
                    unlink(__DIR__.'/../public/'.$this->imagePath);
                }

                $this->imagePath = 'images/'.UtilHelper::randomString(8).'/'.$this->imageFile['name'];
                mkdir(dirname(__DIR__.'/../public/'.$this->imagePath));
                //randomuli saxelis directoryashi emateba atvirtuli foto tavis saxelit
                move_uploaded_file($this->imageFile['tmp_name'],__DIR__.'/../public/'.$this->imagePath); //image['tmp_name'] saxeli emateba imagepathshi

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