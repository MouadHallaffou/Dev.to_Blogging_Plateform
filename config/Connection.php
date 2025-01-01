<?php

$host = 'localhost';
$db = 'devto';
$user = 'root';
$password = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "Connexion réussie à la base de données!";
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}


