<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;

$db = new Database();

try {
    $connection = $db->connect();
    echo "Connexion réussie !";

    $query = $connection->query("SELECT * FROM users");
    $users = $query->fetchAll();

    foreach ($users as $user) {
        echo $user['username'] . "<br>";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
