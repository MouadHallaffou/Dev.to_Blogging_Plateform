<?php
namespace App\Config;
use PDO;
use PDOException;

class Database
{
    private static $host = "localhost"; 
    private static $dbname = "devblog_db"; 
    private static $username = "root"; 
    private static $password = ""; 
    private static $connection; 

    public static function connect(): PDO
    {
        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname ;"charset=utf8mb4";
            self::$connection = new PDO($dsn, self::$username, self::$password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Erreur de connexion : " . $e->getMessage());
        }

        return self::$connection;
    }
}
