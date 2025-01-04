<?php
namespace App\Config;
use PDO;
use PDOException;

class Database
{
    private static string $host = "localhost";
    private static string $dbname = "devblog_db";
    private static string $username = "root";
    private static string $password = "";
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4";
                self::$connection = new PDO($dsn, self::$username, self::$password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    public static function disconnect(): void
    {
        self::$connection = null;
    }
}
