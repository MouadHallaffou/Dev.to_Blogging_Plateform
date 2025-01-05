<?php

namespace Src;
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
use PDO;

class Article{
    private $id;
    private $title;
    private $slug;
    private $content;
    private $excerpt;
    private $metaDescription;
    private $categoryId;
    private $authorId;
    private $featuredImage;
    private $scheduledDate;
    private $createdAt;
    private $updatedAt;
    private $views;
    private static ?PDO $db = null;

    public function __construct(){
        self::$db = Database::connect();
    }

    // Getter et Setter methods
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }

    public function getTitle(){
        return $this->title;
    }
    public function setTitle($title){
        $this->title = $title;
    }

    public function getSlug(){
        return $this->slug;
    }
    public function setSlug($slug){
        $this->slug = $slug;
    }

    public function getContent(){
        return $this->content;
    }
    public function setContent($content){
        $this->content = $content;
    }

    public function getExcerpt(){
        return $this->excerpt;
    }
    public function setExcerpt($excerpt){
        $this->excerpt = $excerpt;
    }

    public function getMetaDescription(){
        return $this->metaDescription;
    }
    public function setMetaDescription($metaDescription){
        $this->metaDescription = $metaDescription;
    }

    public function getCategoryId(){
        return $this->categoryId;
    }
    public function setCategoryId($categoryId){
        $this->categoryId = $categoryId;
    }

    public function getAuthorId(){
        return $this->authorId;
    }
    public function setAuthorId($authorId){
        $this->authorId = $authorId;
    }

    public function getFeaturedImage(){
        return $this->featuredImage;
    }
    public function setFeaturedImage($featuredImage){
        $this->featuredImage = $featuredImage;
    }

    public function getScheduledDate(){
        return $this->scheduledDate;
    }
    public function setScheduledDate($scheduledDate){
        $this->scheduledDate = $scheduledDate;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }
    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(){
        return $this->updatedAt;
    }
    public function setUpdatedAt($updatedAt){
        $this->updatedAt = $updatedAt;
    }

    public function getViews(){
        return $this->views;
    }
    public function setViews($views){
        $this->views = $views;
    }

    // Création d'un article
    public function create($tagIds = []){
        // Générer le slug à partir du titre
        $slug = $this->generateSlug($this->title);
        // Vérifier si le slug existe déjà
        $slugQuery = self::$db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $slugQuery->bindParam(':slug', $slug);
        $slugQuery->execute();
    
        if ($slugQuery->fetchColumn() > 0) {
            $slug = $this->generateUniqueSlug($slug);
        }
    
        // Vérifier si la catégorie existe
        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();
    
        if ($categoryQuery->fetchColumn() == 0) {
            die('La catégorie spécifiée n\'existe pas.');
        }
        
        // Valider l URL de image mise en avant
        if (isset($_POST['featured_image'])) {
            $featuredImageUrl = trim($_POST['featured_image']);
            // Vérifier si l'URL est valide
            if (!filter_var($featuredImageUrl, FILTER_VALIDATE_URL)) {
                die('L\'URL de l\'image mise en avant n\'est pas valide.');
            }
            $this->featuredImage = $featuredImageUrl;
        }
    
        // Insertion de l article dans la base de donner
        $sql = "INSERT INTO articles (title, slug, content, excerpt, meta_description, category_id, featured_image, scheduled_date)
                VALUES (:title, :slug, :content, :excerpt, :meta_description, :category_id, :featured_image, :scheduled_date)";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':excerpt', $this->excerpt);
        $stmt->bindParam(':meta_description', $this->metaDescription);
        $stmt->bindParam(':category_id', $this->categoryId);
        $stmt->bindParam(':featured_image', $this->featuredImage);
        $stmt->bindParam(':scheduled_date', $this->scheduledDate);
    
        if ($stmt->execute()) {
            $articleId = self::$db->lastInsertId();
            // Ajouter les tags
            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $this->addTagToArticle($articleId, $tagId);
                }
            }
            return true;
        }
        return false;
    } 

    // Mise à jour d'un article
    public function update($tagIds = []){
        // Vérifier si la catégorie existe
        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();

        if ($categoryQuery->fetchColumn() == 0) {
            die('La catégorie spécifiée n\'existe pas.');
        }

        // Générer un slug mis à jour
        $slug = $this->generateSlug($this->title);
        if ($this->slugExists($slug)) {
            $slug = $this->generateUniqueSlug($slug);
        }

        // Valider l URL de l'image
        if (!empty($this->featuredImage) && !filter_var($this->featuredImage, FILTER_VALIDATE_URL)) {
            die('L\'URL de l\'image mise en avant n\'est pas valide.');
        }

        // update article
        $sql = "UPDATE articles 
                SET title = :title, slug = :slug, content = :content, excerpt = :excerpt, meta_description = :meta_description, 
                    category_id = :category_id, featured_image = :featured_image, scheduled_date = :scheduled_date, updated_at = NOW()
                WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':excerpt', $this->excerpt);
        $stmt->bindParam(':meta_description', $this->metaDescription);
        $stmt->bindParam(':category_id', $this->categoryId);
        $stmt->bindParam(':featured_image', $this->featuredImage);
        $stmt->bindParam(':scheduled_date', $this->scheduledDate);

        if ($stmt->execute()) {
            $this->updateTags($tagIds);
            return true;
        }
        return false;
    }

    private function addTagToArticle($articleId, $tagId){
        $insertTagQuery = self::$db->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)");
        $insertTagQuery->bindParam(':article_id', $articleId);
        $insertTagQuery->bindParam(':tag_id', $tagId);
        $insertTagQuery->execute();
    }

    private function updateTags($tagIds){
        $deleteTagsQuery = self::$db->prepare("DELETE FROM article_tags WHERE article_id = :article_id");
        $deleteTagsQuery->bindParam(':article_id', $this->id, PDO::PARAM_INT);
        $deleteTagsQuery->execute();

        foreach ($tagIds as $tagId) {
            $this->addTagToArticle($this->id, $tagId);
        }
    }

    private function generateSlug($title){
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    private function generateUniqueSlug($slug){
        $counter = 1;
        $uniqueSlug = $slug . '-' . $counter;

        while ($this->slugExists($uniqueSlug)) {
            $counter++;
            $uniqueSlug = $slug . '-' . $counter;
        }
        return $uniqueSlug;
    }

    private function slugExists($slug){
        $query = self::$db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $query->bindParam(':slug', $slug);
        $query->execute();

        return $query->fetchColumn() > 0;
    }

    // Méthode pour supprimer un article
    public function delete($articleId) {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Méthode pour récupérer tous les articles
    public function fetchAll(){
        $sql = "SELECT * FROM articles";
        $stmt = self::$db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour recuperer les catégories depuis la base de données
    public function getCategories(){
        $sql = "SELECT id, name FROM categories";
        $stmt = self::$db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour recuperer les tags depuis la base de donner
    public function getTags(){
        $sql = "SELECT id, name FROM tags";
        $stmt = self::$db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
