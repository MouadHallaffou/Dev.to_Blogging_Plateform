<?php
require_once '../src/Article.php';
require_once '../config/Database.php';

use Src\Article;

$article = new Article();

// Créer un article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_article'])) {
    $article->setTitle($_POST['title']);
    $article->setContent($_POST['content']);
    $article->setExcerpt($_POST['excerpt']);
    $article->setMetaDescription($_POST['meta_description']);
    $article->setCategoryId($_POST['category_id']);
    $article->setScheduledDate($_POST['scheduled_date']);

    if (isset($_POST['featured_image']) && !empty($_POST['featured_image'])) {
        $imageUrl = $_POST['featured_image'];

        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            $article->setFeaturedImage($imageUrl);
        } else {
            echo "L'URL de l'image est invalide.";
            exit;
        }
    }

    $tagIds = isset($_POST['tags']) ? $_POST['tags'] : [];

    if ($article->create($tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la création de l'article.";
    }
}

// Suppression d article
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $articleId = (int)$_GET['id'];
    if ($articleId > 0) {
        if ($article->delete($articleId)) {
            header('Location: articles.php');
            exit;
        } else {
            echo "Erreur lors de la suppression de l'article.";
        }
    }
}

// modifier l'article
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {
    $article->setId($_POST['id']);
    $article->setTitle($_POST['title_edit'] ?? null);
    $article->setContent($_POST['content_edit'] ?? null);
    $article->setExcerpt($_POST['excerpt_edit'] ?? '');
    $article->setMetaDescription($_POST['meta_description_edit'] ?? '');
    $article->setCategoryId($_POST['category_id'] ?? null);
    $article->setScheduledDate($_POST['scheduled_date'] ?? null);

    // Si une nouvelle URL d'image est fournie
    if (isset($_POST['featured_image']) && filter_var($_POST['featured_image'], FILTER_VALIDATE_URL)) {
        // Mettre à jour l'URL de l'image
        $article->setFeaturedImage($_POST['featured_image']);
    }

    $tagIds = $_POST['tags'] ?? [];

    // Validation des champs obligatoires
    if (!$article->getTitle() || !$article->getContent() || !$article->getCategoryId()) {
        echo "Les champs obligatoires sont manquants.";
        exit;
    }

    // Appel à la méthode de mise à jour
    if ($article->update($tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de l'article.";
    }
}
