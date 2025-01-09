<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Article.php'; 

use App\Config\Database;

$articleObj = new Src\Article();

// Récupération des catégories et des tags
$categories = $articleObj->getCategories();
$tags = $articleObj->getTags();
$currentArticle = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $articleId = intval($_GET['id']);

    try {
        $pdo = Database::connect(); 

        // Requête pour récupérer l'article spécifique avec ses catégories et tags
        $sql = "SELECT a.id, a.title, a.slug, a.content, a.excerpt, a.meta_description, a.created_at, a.views, 
                a.category_id, 
                c.name AS name, 
                COALESCE(GROUP_CONCAT(t.name), '') AS tags
                FROM articles a
                JOIN categories c ON a.category_id = c.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                WHERE a.id = :articleId
                GROUP BY a.id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $currentArticle = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$currentArticle) {
            die("Article non trouvé.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération de l'article : " . $e->getMessage());
    }

    // Récupération de tous les articles
    try {
        $sql = "SELECT a.id AS article_id, a.title,a.featured_image, c.name AS category_name, 
                    GROUP_CONCAT(t.name) AS tags, a.views, a.created_at
                FROM articles a
                JOIN categories c ON a.category_id = c.id
                LEFT JOIN article_tags at ON a.id = at.article_id
                LEFT JOIN tags t ON at.tag_id = t.id
                GROUP BY a.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des articles : " . $e->getMessage());
    }
} else {
    die("ID d'article non valide.");
}


// Couleurs pour graphiques
$colors = [
    'rgb(78, 115, 223)',    
    'rgb(28, 200, 138)',    
    'rgb(54, 185, 204)',    
    'rgb(246, 194, 62)',    
    'rgb(231, 74, 59)',     
    'rgb(133, 135, 150)',   
    'rgb(90, 92, 105)',     
    'rgb(244, 246, 249)'    
];
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DevBlog - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'components/sidebar.php'; ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <!-- formulaire de modification -->
                    <div class="modal show" id="editArticleModal" tabindex="-1" aria-labelledby="editArticleModalLabel"  aria-hidden="true" style="display: block;">
                        <div class="modal-dialog" style="max-width: 500px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Modifier un Article</h5>
                                </div>
                                <div class="modal-body" style="max-height: 80vh; overflow-y: auto; padding: 20px;">
                                <form action="crud_articles.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($currentArticle['id']) ?>">
                                        <!-- Titre -->
                                        <div class="mb-3">
                                            <label for="article_title" class="form-label">Titre de l'article</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="article_title" 
                                                name="title_edit" 
                                                value="<?= htmlspecialchars($currentArticle['title']) ?>" 
                                                required>
                                        </div>

                                        <!-- Contenu -->
                                        <div class="mb-3">
                                            <label for="article_content" class="form-label">Contenu</label>
                                            <textarea 
                                                class="form-control" 
                                                id="article_content" 
                                                name="content_edit" 
                                                rows="6" 
                                                required><?= htmlspecialchars($currentArticle['content']) ?></textarea>
                                        </div>

                                        <!-- Excerpt -->
                                        <div class="mb-3">
                                            <label for="article_excerpt" class="form-label">Extrait</label>
                                            <textarea 
                                                class="form-control" 
                                                id="article_excerpt" 
                                                name="excerpt_edit" 
                                                rows="3"><?= htmlspecialchars($currentArticle['excerpt']) ?></textarea>
                                        </div>

                                        <!-- Meta description -->
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Description Métas</label>
                                            <textarea 
                                                class="form-control" 
                                                id="meta_description" 
                                                name="meta_description_edit" 
                                                rows="3"><?= htmlspecialchars($currentArticle['meta_description']) ?></textarea>
                                        </div>

                                        <!-- Catégorie -->
                                        <div class="mb-3">
                                            <label for="article_category" class="form-label">Catégorie</label>
                                            <select class="form-control" id="article_category" name="category_id" required>
                                                <option value="">Choisissez une catégorie</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= $category['id'] ?>" 
                                                        <?= $category['id'] == ($currentArticle['category_id'] ?? '') ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($category['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="article_tags" class="form-label">Tags</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <?php 
                                                $selectedTags = explode(',', $currentArticle['tags']);
                                                foreach ($tags as $tag): ?>
                                                    <div class="form-check">
                                                        <input 
                                                            type="checkbox" 
                                                            class="form-check-input" 
                                                            id="tag<?= $tag['id'] ?>" 
                                                            name="tags[]" 
                                                            value="<?= $tag['id'] ?>"
                                                            <?= in_array($tag['id'], $selectedTags) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="tag<?= $tag['id'] ?>">
                                                            <?= htmlspecialchars($tag['name']) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Image -->
                                        <div class="mb-3">
                                            <label for="article_featured_image" class="form-label">Image mise en avant (URL)</label>
                                            <input 
                                                type="url" 
                                                class="form-control" 
                                                id="article_featured_image" 
                                                name="featured_image" 
                                                placeholder="Entrez l'URL de l'image"
                                                value="<?= htmlspecialchars($currentArticle['featured_image'] ?? '') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="article_scheduled_date" class="form-label">Date de publication programmée</label>
                                            <input 
                                                type="datetime-local" 
                                                class="form-control form-control-lg rounded shadow-sm" 
                                                id="article_scheduled_date" 
                                                name="scheduled_date">
                                        </div>
                                        <!-- Boutons -->
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" name="update_article" class="btn btn-primary">Modifier</button>
                                            <a href="articles.php" class="btn btn-secondary">Annuler</a>
                                        </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ---------------- -->
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Articles</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= is_array($articles) ? count($articles) : 0 ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <!-- Content Row -->

                <!-- DataTales Example -->
                 <?php if (!empty($articles)): ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Articles</h6>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive overflow-auto" style="max-height: 260px;">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Category</th>
                                            <th>Tags</th>
                                            <th>Views</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($articles as $article): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($article['article_id']) ?></td>
                                                <td><?= htmlspecialchars($article['title']) ?></td>
                                                <td> <img src="<?= htmlspecialchars($article['featured_image']) ?>" class="article-image" alt="img" style="width: 30px; height: 30px;"></td>
                                                <td><?= htmlspecialchars($article['category_name']) ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($article['tags'])) {
                                                        $tags = explode(',', $article['tags']);
                                                        foreach ($tags as $tag) {
                                                            echo '<span class="badge badge-primary mr-1">' . htmlspecialchars($tag) . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span class="badge badge-secondary">Aucun tag</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td data-order="<?= $article['views'] ?>">
                                                    <?= number_format($article['views']) ?>
                                                </td>
                                                <td data-order="<?= strtotime($article['created_at']) ?>">
                                                    <?= date('M d, Y H:i', strtotime($article['created_at'])) ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <a href="edit-article.php?id=<?= $article['article_id'] ?>" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="crud_articles.php?id=<?= $article['article_id'] ?>"
                                                            class="btn btn-danger btn-sm delete-article"
                                                            data-id="<?= $article['article_id'] ?>"
                                                            onclick="return confirm('vous etes rûr de supprimer cet article ?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                ?php else: ?>
                                 <p>Aucun article trouvé.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include 'components/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>