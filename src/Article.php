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

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
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

    //methode inserte articles:
    public function create(){
        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();
        $categoryExists = $categoryQuery->fetchColumn();

        if ($categoryExists == 0) {
            die('La categorie existe pas');
        }

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $imageName = $_FILES['featured_image']['name'];
            $imageTmp = $_FILES['featured_image']['tmp_name'];
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Dev.to_Blogging_Plateform/public/assets/images/';
            $imagePath = $uploadDir . basename($imageName);

            if (!is_dir($uploadDir)) {
                die('Le dossier ni existe pas');
            }

            if (!is_writable($uploadDir)) {
                die('Le dossier non accessible.');
            }

            if (move_uploaded_file($imageTmp, $imagePath)) {
                $this->featuredImage = $imagePath;
            } else {
                die('Erreur : Impossible telecharger l image');
            }
        } else {
            die('Aucune image telecharger');
        }

        $sql = "INSERT INTO articles (title, slug, content, excerpt, meta_description, category_id, featured_image, status, scheduled_date)
            VALUES (:title, :slug, :content, :excerpt, :meta_description, :category_id, :featured_image, :status, :scheduled_date)";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':excerpt', $this->excerpt);
        $stmt->bindParam(':meta_description', $this->metaDescription);
        $stmt->bindParam(':category_id', $this->categoryId);
        $stmt->bindParam(':featured_image', $this->featuredImage);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':scheduled_date', $this->scheduledDate);

        return $stmt->execute();
    }

    //methode modifier article
    public function update(){
        $categoryQuery = self::$db->prepare("SELECT COUNT(*) FROM categories WHERE id = :category_id");
        $categoryQuery->bindParam(':category_id', $this->categoryId);
        $categoryQuery->execute();
        $categoryExists = $categoryQuery->fetchColumn();

        if ($categoryExists == 0) {
            die('La catégorie spécifiée n\'existe pas.');
        }

        $sql = "UPDATE articles SET title = :title, slug = :slug, content = :content, excerpt = :excerpt, meta_description = :meta_description,
                category_id = :category_id, author_id = :author_id, featured_image = :featured_image, status = :status, scheduled_date = :scheduled_date
                WHERE id = :id";

        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':slug', $this->slug);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':excerpt', $this->excerpt);
        $stmt->bindParam(':meta_description', $this->metaDescription);
        $stmt->bindParam(':category_id', $this->categoryId);
        $stmt->bindParam(':featured_image', $this->featuredImage);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':scheduled_date', $this->scheduledDate);

        return $stmt->execute();
    }

    // Method delete 
    public function delete(){
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Method Select
    public function fetchAll(){
        $sql = "SELECT * FROM articles";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // recuperer les categories depuis la base de donner
    public function getCategories(){
        $sql = "SELECT id, name FROM categories";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // recuperer lestags depuis la base de donner
    public function getTags(){
        $sql = "SELECT id, name FROM tags";
        $stmt = self::$db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
