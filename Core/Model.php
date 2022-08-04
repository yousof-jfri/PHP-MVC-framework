<?php

namespace Core;

use PDO;
use App\Config;

abstract class Model
{
    protected $pdo;
    protected $tableName = null;

    public function __construct()
    {
        $this->pdo = $this->make();
        $this->checkTable();
    }

    // check the table exsist or not
    protected function checkTable()
    {
        $stmt = $this->pdo->prepare("SHOW TABLES LIKE '{$this->tableName}'");

        $stmt->execute();

        if($stmt->fetch() == false){
            throw new \Exception("Table {$this->tableName} Not Found");
        }
    }

    protected function make()
    {
        try{
            return new PDO("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_DATABASE , Config::DB_USERNAME, Config::DB_PASSWORD);


        }catch(\PDOException $e){
            throw $e;
        }
    }

    // return all the users
    public function all()
    {
        if(is_null($this->tableName))
        {
            throw new \Exception('Table name not null');
        }

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName}");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // find the user
    public function find($id)
    {
        if(is_null($id)){
            throw new \Exception("method find need 1 parameter id");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");

        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}