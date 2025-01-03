<?php
require_once '../config/Database.php';
require_once '../src/Category.php';
require_once '../src/Tag.php';

use App\Config\Database;
use App\Src\Category;
use App\Src\Tag;

$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $categoryName = $_POST['category_name'];
    $categoryManager = new Category($pdo);
    $categoryManager->createCategory($categoryName);
    header("Location: categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) 
&& $_GET['action'] === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $categoryManager = new Category($pdo);
    $categoryManager->deleteCategory($id);
    header("Location: categories.php"); 
    exit;
}

//////////////////////////////////////////////////
//////////////////////////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Tag_name'])) {
    $tagName = $_POST['Tag_name'];
    $tagManager = new Tag($pdo);
    $tagManager->createTag($tagName);
    header("Location: tags.php");
    exit;
}