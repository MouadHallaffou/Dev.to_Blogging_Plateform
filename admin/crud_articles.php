<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../src/Article.php';
require_once '../config/Database.php';

use Src\Article;

$article = new Article();

// Récupérer les articles acceptés par l'admin
try {
    $articles = $article->getArticlesByStatus('accepte');
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
}

// Création d'un article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_article'])) {
    $article->setTitle($_POST['title']);
    $article->setContent($_POST['content']);
    $article->setAuthorId($_SESSION['user_id']);
    $article->setExcerpt($_POST['excerpt']);
    $article->setMetaDescription($_POST['meta_description']);
    $article->setCategoryId($_POST['category_id']);
    $article->setScheduledDate($_POST['scheduled_date']);

    if (!empty($_POST['featured_image'])) {
        $article->setFeaturedImage($_POST['featured_image']);
    }

    $tagIds = $_POST['tags'] ?? [];

    if ($article->create($tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la création de l'article.";
    }
}

// Suppression d'un article
if (isset($_GET['id']) && !empty($_GET['id'])) {
    if ($article->delete((int)$_GET['id'])) {
        header('Location: articles.php');
        exit;
    } else {
        echo "Erreur lors de la suppression de l'article.";
    }
}

// Modification d'un article
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {
    $article->setId($_POST['id']);
    $article->setTitle($_POST['title_edit'] ?? null);
    $article->setContent($_POST['content_edit'] ?? null);
    $article->setExcerpt($_POST['excerpt_edit'] ?? '');
    $article->setMetaDescription($_POST['meta_description_edit'] ?? '');
    $article->setCategoryId($_POST['category_id'] ?? null);
    $article->setScheduledDate($_POST['scheduled_date'] ?? null);

    if (!empty($_POST['featured_image'])) {
        $article->setFeaturedImage($_POST['featured_image']);
    }

    $tagIds = $_POST['tags'] ?? [];

    if ($article->update($tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de l'article.";
    }
}

// Récupérer les articles soumis
try {
    $articlesSoumis = $article->getArticlesByStatus('soumis');
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
}
