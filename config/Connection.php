<?php
namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $host = "localhost"; 
    private $dbname = "devblog_db"; 
    private $username = "root"; 
    private $password = ""; 
    private $connection; 

    public function connect(): PDO
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Erreur de connexion : " . $e->getMessage());
        }

        return $this->connection;
    }
}
