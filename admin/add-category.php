<?php
require_once '../config/Connection.php'; 
require_once '../src/Category.php'; 

use App\Config\Database;
use App\Src\Category;

$db = new Database();
$pdo = $db->connect(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $categoryName = $_POST['category_name']; 
    $categoryManager = new Category($pdo); 
    $categoryManager->createCategory($categoryName); 
    header("Location: categories.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
</head>
<body>
    <h1>Add New Category</h1>
    <form action="add-category.php" method="POST">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" required>
        <button type="submit">Add Category</button>
    </form>
</body>
</html>
