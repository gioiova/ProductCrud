<?php

namespace app;

use app\models\Product;
use PDO;
use Dotenv\Dotenv;
class Database
{
    public $pdo = null;
    public  static $db = null;


    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/'); // Adjust the path if necessary
        $dotenv->load();

        $dbHost = $_ENV['DB_HOST'];
        $dbPort = $_ENV['DB_PORT'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASS'];

        // Connect to DB using environment variables
        $this->pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPass);
        //throw exception if connection failed
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db = $this;




    }


    //get product by keyword for searching
    public  function getProducts($keyword= '')
    {
        if($keyword){
            $statement = $this->pdo->prepare('SELECT * FROM products WHERE title like :keyword ORDER BY create_date DESC ');
            $statement->bindValue(':keyword',"%$keyword%");
        }else{
            $statement = $this->pdo->prepare('SELECT * FROM products  ORDER BY create_date DESC ');

        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);


    }

    public function getProductById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $statement->bindValue(':id',$id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //delete
    public function deleteProduct($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM products WHERE id=:id ');
        $statement->bindValue(':id',$id);
        return $statement->execute();



    }

    //update
    public function updateProduct(Product $product)
    {

        $statement = $this->pdo->prepare("UPDATE products SET title=:title,
                                    image=:image,
                                    description=:description,
                                    price=:price WHERE id= :id");

        $statement->bindValue(':title',$product->title);
        $statement->bindValue(':image',$product->imagePath);
        $statement->bindValue(':description',$product->description);
        $statement->bindValue(':price',$product->price);
        $statement->bindValue(':id',$product->id);

        $statement->execute();



    }

    //create
    public function createProduct(Product $product)
    {
        $statement = $this->pdo->prepare("INSERT INTO products(title,image,description,price,create_date)
                   VALUES(:title,:image,:description,:price,:date)");

        $statement->bindValue(':title',$product->title);
        $statement->bindValue(':image',$product->imagePath);
        $statement->bindValue(':description',$product->description);
        $statement->bindValue(':price',$product->price);
        $statement->bindValue(':date',date('Y-m-d H:i:s'));

//execute am kvelapers gadaitans bazashi rasac shevitan inputshi
        $statement->execute();

    }



}