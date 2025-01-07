<?php
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
    <!-- Google Fonts Link For Icons -->
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
            <button class="login-btn">LOG IN</button>
        </nav>
    </header>

    <div class="py-16 text-white z-0">
    <div class="container mx-auto px-4 lg:px-8">

        <!-- Articles Grid -->
        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($articles as $article) : ?>
                <article class="bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col">
                    <!-- Article Metadata -->
                    <div class="p-4 flex items-center text-xs text-gray-400">
                        <time datetime="<?= htmlspecialchars($article['created_at']) ?>">Creer le : 
                            <?= htmlspecialchars($article['created_at']) ?>
                        </time>
                        <span class="ml-auto bg-gray-700 text-white rounded-full px-3 py-1">
                            <?= htmlspecialchars($article['category_name']) ?>
                        </span>
                    </div>

                    <!-- Article Content -->
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-semibold text-gray-100 mb-2 hover:text-indigo-400">
                            Titre : <a href="#"><?= htmlspecialchars($article['title']) ?></a>
                        </h3>
                        <p class="text-sm text-gray-300 mb-4 line-clamp-3">
                            <?= htmlspecialchars($article['content']) ?>
                        </p>
                    </div>

                    <!-- Read More -->
                    <div class="p-4 mt-auto">
                        <a href="#" class="text-indigo-400 font-medium hover:text-indigo-500">Read more →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
 </div>

 <div class="blur-bg-overlay"></div>
    <div class="form-popup">
        <span class="close-btn material-symbols-rounded">close</span>
        <div class="form-box login">
            
            <div class="form-details">
                <h2>Welcome Back</h2>
                <p>Please log in using your personal information to stay connected with us.</p>
            </div>

            <div class="form-content">
                <h2>LOGIN</h2>
                <form action="processLogin.php" method="POST">
                    <div class="input-field">
                        <input type="text" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <a href="#" class="forgot-pass-link">Forgot password?</a>
                    <button type="submit">Log In</button>
                </form>
                <div class="bottom-link">
                    Don't have an account?
                    <a href="#" id="signup-link">Signup</a>
                </div>
            </div>
        </div>
        <div class="form-box signup">
            <div class="form-details">
                <h2>Create Account</h2>
                <p>To become a part of our community, please sign up using your personal information.</p>
            </div>
            <div class="form-content">
                <h2>SIGNUP</h2>
                <form action="processSignUp.php" method="POST">
                    <div class="input-field">
                        <input type="text" name="username" required>
                        <label>Enter your Username</label>
                    </div>
                    <div class="input-field">
                        <input type="email" name="email" required>
                        <label>Enter your email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" required>
                        <label>Create password</label>
                    </div>
                    <div class="policy-text">
                        <input type="checkbox" id="policy">
                        <label for="policy">
                            I agree the
                            <a href="#" class="option">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
                <div class="bottom-link">
                    Already have an account?
                    <a href="#" id="login-link">Login</a>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/js/scriptlogin.js"></script>
</body>

</html>