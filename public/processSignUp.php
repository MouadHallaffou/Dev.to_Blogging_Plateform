<?php
session_start();
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
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $pdo = Database::connect();

        $user = new User($pdo);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPasswordHash($passwordHash);

        try {
            if ($user->save()) {
                $_SESSION['message'] = 'Inscription réussie';
                header("Location: login.php");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors de l\'inscription : ' . $e->getMessage();
            header("Location: signup.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'Veuillez remplir tous les champs';
        header("Location: signup.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'Méthode de requête non autorisée';
    header("Location: signup.php");
    exit;
}
