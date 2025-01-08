<?php
session_start();
require_once '../config/Database.php';

use App\Config\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'], $_POST['status'])) {
    $article_id = $_POST['article_id'];
    $status = $_POST['status'];

    try {
        $pdo = Database::connect();
        $sql = "UPDATE articles SET status = :status WHERE id = :article_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->execute();

        $_SESSION['message'] = 'Article status updated successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error updating article status: ' . $e->getMessage();
    }

    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = 'Invalid request.';
    header("Location: dashboard.php");
    exit;
}

if ($status === 'refuse') {
    try {
        $sql = "DELETE FROM articles WHERE id = :article_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':article_id', $article_id);
        $stmt->execute();

        $_SESSION['message'] = 'Article deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting article: ' . $e->getMessage();
    }
}
