<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config/Database.php'; 
require_once 'src/BaseModel.php';
require_once 'src/Category.php';
require_once 'src/User.php';

use App\Config\Database;
use App\Src\Category;
use App\Src\User;

try {
    $db = new Database();
    $pdo = $db->connect();
    echo "Connecté à la base de données!<br>";


} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
