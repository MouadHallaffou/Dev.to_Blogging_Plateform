<?php
namespace Src;
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
use PDO;

class Article
{
    private $id;
    private $title;
    private $slug;
    private $content;
    private $excerpt;
    private $metaDescription;
    private $categoryId;
    private $authorId;
    private $featuredImage;
    private $status;
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

    public function create($tagIds = []) {
        // Générer le slug  a partir du titre
        $slug = $this->generateSlug($this->title);
 
        $slugQuery = self::$db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $slugQuery->bindParam(':slug', $slug);
        $slugQuery->execute();
        $slugExists = $slugQuery->fetchColumn();
    
        if ($slugExists > 0) {
            $slug = $this->generateUniqueSlug($slug);
        }

        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();
        $categoryExists = $categoryQuery->fetchColumn();
    
        if ($categoryExists == 0) {
            die('La catégorie spécifiée n\'existe pas.');
        }
 
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $imageName = $_FILES['featured_image']['name'];
            $imageTmp = $_FILES['featured_image']['tmp_name'];
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Dev.to_Blogging_Plateform/public/assets/images/';
            $imagePath = $uploadDir . basename($imageName);
    
            if (!is_dir($uploadDir)) {
                die('Le dossier n\'existe pas');
            }
    
            if (!is_writable($uploadDir)) {
                die('Le dossier n\'est pas accessible.');
            }
    
            if (move_uploaded_file($imageTmp, $imagePath)) {
                $this->featuredImage = $imagePath;
            } else {
                die('Erreur : Impossible de télécharger l\'image');
            }
        } else {
            die('Aucune image téléchargée');
        }
    
        // Insertion d article
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

            if (!empty($tagIds)) {
                foreach ($tagIds as $tagId) {
                    $checkTagQuery = self::$db->prepare("SELECT COUNT(*) FROM tags WHERE id = :tag_id");
                    $checkTagQuery->bindParam(':tag_id', $tagId);
                    $checkTagQuery->execute();
                    $tagExists = $checkTagQuery->fetchColumn();
    
                    if ($tagExists > 0) {
                        $insertTagQuery = self::$db->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)");
                        $insertTagQuery->bindParam(':article_id', $articleId);
                        $insertTagQuery->bindParam(':tag_id', $tagId);
                        $insertTagQuery->execute();
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    private function generateSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        return $slug;
    }

    private function generateUniqueSlug($slug) {
        $counter = 1;
        $uniqueSlug = $slug . '-' . $counter;

        while ($this->slugExists($uniqueSlug)) {
            $counter++;
            $uniqueSlug = $slug . '-' . $counter;
        }
        return $uniqueSlug;
    }

    private function slugExists($slug) {
        $query = self::$db->prepare("SELECT COUNT(*) FROM articles WHERE slug = :slug");
        $query->bindParam(':slug', $slug);
        $query->execute();
        return $query->fetchColumn() > 0;
    }
     
    // Méthode pour mettre à jour un article
    public function update($tagIds = []){
        // Vérifier si la catégorie existe
        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();
        $categoryExists = $categoryQuery->fetchColumn();

        if ($categoryExists == 0) {
            die('La catégorie spécifiée n\'existe pas.');
        }

        // Générer un slug mis à jour à partir du titre
        $slug = $this->generateSlug($this->title);
        if ($this->slugExists($slug)) {
            $slug = $this->generateUniqueSlug($slug);
        }

        // Traiter la nouvelle image si elle est téléchargée
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $imageName = $_FILES['featured_image']['name'];
            $imageTmp = $_FILES['featured_image']['tmp_name'];
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Dev.to_Blogging_Plateform/public/assets/images/';
            $imagePath = $uploadDir . basename($imageName);

            if (!move_uploaded_file($imageTmp, $imagePath)) {
                die('Erreur : Impossible de télécharger la nouvelle image');
            }

            $this->featuredImage = $imagePath;
        }

        // Mise à jour de l'article
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
            if (!empty($tagIds)) {
                $deleteTagsQuery = self::$db->prepare("DELETE FROM article_tags WHERE article_id = :article_id");
                $deleteTagsQuery->bindParam(':article_id', $this->id, PDO::PARAM_INT);
                $deleteTagsQuery->execute();

                foreach ($tagIds as $tagId) {
                    $insertTagQuery = self::$db->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (:article_id, :tag_id)");
                    $insertTagQuery->bindParam(':article_id', $this->id, PDO::PARAM_INT);
                    $insertTagQuery->bindParam(':tag_id', $tagId, PDO::PARAM_INT);
                    $insertTagQuery->execute();
                }
            }
            return true;
        }

        return false;
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
