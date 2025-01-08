<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/User.php';
use App\Config\Database;
use App\Src\User;
$pdo = Database::connect();
if(isset($_GET['id'])){
    $user = new User($pdo);
    $user->setId($_GET['id']);
    $user->asignRole();
    header('Location: AllUsers.php'); 
    exit();
}