<?php
require_once __DIR__ . '/../vendor/autoload.php'; 

use App\Config\Database;
use App\Src\Tag;

$db = new Database();
$pdo = $db->connect(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Tag_name'])) {
    $TagName = $_POST['Tag_name']; 
    $TagManager = new Tag($pdo);
    $TagManager->createTag($TagName); 
    header("Location: categories.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add New Tag</title>
</head>
<body>

<form action="add-Tag.php" method="POST">
<div class="bg-blue-200 min-h-screen flex items-center">

    <div class="w-full">
        <h2 class="text-center text-blue-400 font-bold text-2xl uppercase mb-10">Add New Tag</h2>
        <div class="bg-white p-10 rounded-lg shadow md:w-3/4 mx-auto lg:w-1/2">
            <form action="">
                <div class="mb-5">
                    <label for="name" class="block mb-2 font-bold text-gray-600">Tag Name:</label>
                    <input type="text" name="Tag_name" id="Tag_name" placeholder="Add New Tag ..." class="border border-gray-300 shadow p-3 w-full rounded mb-">
                </div>

                <button type="submit" class="block w-full bg-blue-500 text-white font-bold p-4 rounded-lg">Add Tag</button>
            </form>
        </div>
    </div>
</div>
</form>

</body>
</html>