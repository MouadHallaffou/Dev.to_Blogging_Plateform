<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;


try {
    $db = new Database();
    $pdo = $db->connect();
    echo "Connecté à la base de données!<br>";


} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
