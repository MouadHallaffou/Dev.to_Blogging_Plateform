<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../admin/crud_articles.php';
require_once __DIR__ . '/crud_user.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form | Dev blog</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0">
    <link rel="stylesheet" href="./assets/css/styleLogin.css">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <header>
        <nav class="navbar">
            <span class="hamburger-btn material-symbols-rounded">menu</span>
            <a href="#" class="logo">
                <img src="./assets/images/logo.jpg" alt="logo">
                <h2>Dev Blogging</h2>
            </a>
            <ul class="links">
                <span class="close-btn material-symbols-rounded">close</span>
                <li><a href="#">Home</a></li>
                <li><a href="#">Articles</a></li>
                <li><a href="#">Courses</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-greeting flex items-center text-white text-lg ml-20">
                    <p><?= htmlspecialchars($_SESSION['username']) ?></p>
                    <img src="../admin/img/undraw_profile_2.svg" alt="Profile Picture" class="h-8 w-8 rounded-full ml-1">
                </div>
                <a href="logout.php" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 transition">
                    Log Out
                </a>
            <?php else: ?>
                <a href="UserSingUp.php" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                    Log In
                </a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="py-16 text-white z-0">
        <div class="container mx-auto px-4 lg:px-8">
            <!--Grid -->
            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articles as $article) : ?>
                    <article class="bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col">
                        <!--  -->
                        <div class="p-4 flex items-center text-xs text-gray-400">
                            <time datetime="<?= htmlspecialchars($article['created_at']) ?>">Creer le : 
                                <?= htmlspecialchars($article['created_at']) ?>
                            </time>
                            <span class="ml-auto bg-gray-700 text-white rounded-full px-3 py-1">
                                <?= htmlspecialchars($article['category_name']) ?>
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-100 mb-2 hover:text-indigo-400">
                                Titre : <a href="#"><?= htmlspecialchars($article['title']) ?></a>
                            </h3>
                            <p class="text-sm text-gray-300 mb-4 line-clamp-3">
                                <?= htmlspecialchars($article['content']) ?>
                            </p>
                        </div>

                        <div class="p-4 mt-auto">
                            <a href="#" class="text-indigo-400 font-medium hover:text-indigo-500">Read more →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="blur-bg-overlay"></div>

    <script src="./assets/js/scriptlogin.js"></script>
</body>

</html>
