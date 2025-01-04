<?php
require_once '../src/Article.php';
require_once '../config/Database.php';

$article = new Src\Article();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article->setTitle($_POST['title']);
    $article->setSlug($_POST['slug']);
    $article->setContent($_POST['content']);
    $article->setExcerpt($_POST['excerpt']);
    $article->setMetaDescription($_POST['meta_description']);
    $article->setCategoryId($_POST['category_id']);
    $article->setStatus($_POST['status']);
    $article->setScheduledDate($_POST['scheduled_date']);

    if (isset($_FILES['featured_image'])) {
        $article->setFeaturedImage($_FILES['featured_image']['name']);
    }

    if ($article->create()) {
        header("Location: /admin/articles.php?success=true");
        exit; 
    } else {
        echo "erreur";
    }
}
?>
