<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/Database.php';
require_once '../src/User.php';

use App\Config\Database;
use App\Src\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash passworde
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $pdo = Database::connect();

        $user = new User($pdo);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPasswordHash($passwordHash);

        try {
            if ($user->save()) {
                $_SESSION['message'] = 'Inscription avec succes';
                header("Location: index.php");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors d inscription : ' . $e->getMessage();
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'vous vouller remplir tous les champs';
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'mhethode de la requete non autorise';
    header("Location: index.php");
    exit;
}
