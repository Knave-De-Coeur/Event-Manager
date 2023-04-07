<?php

namespace src\utils\database;

use PDO;
use PDOException;

class Database extends PDO
{
    private $dbConnection = null;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        try {
            parent::__construct("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db", $user, $pass);

        } catch (PDOException $e) {
            exit("something went wrong getting connection: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}