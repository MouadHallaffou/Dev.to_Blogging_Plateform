<?php

require_once '../src/Article.php';
require_once '../config/Database.php';

use Src\Article;
use App\Config\Database;
$article = new Article();

//affiches les articles 
try {
    $pdo = Database::connect();
    $sql = "SELECT a.id AS article_id, a.title, a.slug, a.content, a.featured_image, a.excerpt, a.meta_description, a.created_at, a.views, 
            c.name AS category_name, 
            COALESCE(GROUP_CONCAT(t.name), '') AS tags
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN article_tags at ON a.id = at.article_id
            LEFT JOIN tags t ON at.tag_id = t.id
            GROUP BY a.id ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
}

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
