<?php
require_once '../src/Article.php';
require_once '../config/Database.php';

use Src\Article;
$article = new Article();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article->setTitle($_POST['title']);
    $article->setContent($_POST['content']);
    $article->setExcerpt($_POST['excerpt']);
    $article->setMetaDescription($_POST['meta_description']);
    $article->setCategoryId($_POST['category_id']);
    $article->setScheduledDate($_POST['scheduled_date']);

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $article->setFeaturedImage($_FILES['featured_image']['name']);
    }
    $tagIds = isset($_POST['tags']) ? $_POST['tags'] : [];

    if ($article->create($tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la création de l'article.";
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $articleId = (int)$_GET['id']; 
    if ($articleId > 0) {
        if ($article->delete($articleId)) {
            header('Location: articles.php');
            exit;
        } else {
            echo "Une erreur lors de la suppression de l'article.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_article'])) {
    $articleId = $_POST['id'];
    $title = $_POST['title_edit'];
    $content = $_POST['content_edit'];
    $excerpt = $_POST['excerpt_edit'];
    $metaDescription = $_POST['meta_description_edit'];
    $categoryId = $_POST['category_id'];
    $scheduledDate = $_POST['scheduled_date'];
    $tagIds = isset($_POST['tags']) ? $_POST['tags'] : [];
    $featuredImage = null;

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
        $featuredImage = $_FILES['featured_image']['name'];
        move_uploaded_file($_FILES['featured_image']['tmp_name'], '../uploads/' . $featuredImage);
    }

    if ($article->update($articleId, $title, $content, $excerpt, $metaDescription, $categoryId, $scheduledDate, $featuredImage, $tagIds)) {
        header("Location: /Dev.to_Blogging_Plateform/admin/articles.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de l'article.";
    }
}
