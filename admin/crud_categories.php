<?php
require_once '../config/Database.php';
require_once '../src/Category.php';

use App\Config\Database;
use App\Src\Category;

class CategoryController
{
    private $pdo;
    private $categoryModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->categoryModel = new Category($pdo);
    }

    public function displayCategories()
    {
        try {
            $categories = $this->categoryModel->getAllCategory();
            return $categories ?: [];
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    public function createCategory($categoryName)
    {
        if (!empty($categoryName)) {
            $this->categoryModel->createCategory($categoryName);
            header("Location: categories.php");
            exit;
        }
    }

    public function updateCategory($categoryId, $categoryName)
    {
        if (!empty($categoryId) && !empty($categoryName)) {
            $this->categoryModel->updateCategory($categoryId, $categoryName);
            header("Location: categories.php");
            exit;
        }
    }

    public function deleteCategory($categoryId)
    {
        if (!empty($categoryId) && is_numeric($categoryId)) {
            $this->categoryModel->deleteCategory($categoryId);
            header("Location: categories.php");
            exit;
        }
    }

    public function handleRequest()
    {
        // Méthode CREATE
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && !isset($_POST['category_id'])) {
            $this->createCategory($_POST['category_name']);
        }

        // Méthode UPDATE
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryEdit_name']) && isset($_POST['category_id'])) {
            $this->updateCategory($_POST['category_id'], $_POST['categoryEdit_name']);
        }

        // Méthode DELETE
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->deleteCategory($_GET['id']);
        }
    }
}

$pdo = Database::connect();
$categoryController = new CategoryController($pdo);

$categories = $categoryController->displayCategories();
$categoryLabels = array_column($categories, 'name');
$categoryCounts = array_column($categories, 'count');

$categoryController->handleRequest();
