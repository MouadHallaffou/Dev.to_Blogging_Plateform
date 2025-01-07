<?php

use App\Config\Database;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';



try {
    $db = Database::connect();
    echo "Connecté à la base de données!<br>";


} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
