<?php
session_start();
require_once '../config/Database.php';
require_once '../src/User.php';

use App\Config\Database;
use App\Src\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['password'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        try {
            $pdo = Database::connect();
            $user = new User($pdo);

            $foundUser = $user->findByEmail($email);

            if ($foundUser !== null && password_verify($password, $foundUser['password_hash'])) {
                $_SESSION['user_id'] = $foundUser['id_user'];
                $_SESSION['username'] = $foundUser['username'];
                $_SESSION['role'] = $foundUser['role'];

                header("Location: succesSignUp.php"); 
                exit;
            } else {
                $_SESSION['error'] = 'Email ou mot de passe incorrect.';
                header("Location: index.php");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erreur lors de la connexion : ' . htmlspecialchars($e->getMessage());
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'Veuillez remplir tous les champs.';
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION['error'] = 'Méthode de requête non autorisée.';
    header("Location: index.php");
    exit;
}
