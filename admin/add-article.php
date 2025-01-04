<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../src/Article.php';

$article = new Src\Article();

$categories = $article->getCategories();
$tags = $article->getTags();

// Prepare data for the chart
// $categories = [];
// $counts = [];
// Define colors for the chart
$colors = [
    'rgb(78, 115, 223)',    // primary
    'rgb(28, 200, 138)',    // success
    'rgb(54, 185, 204)',    // info
    'rgb(246, 194, 62)',    // warning
    'rgb(231, 74, 59)',     // danger
    'rgb(133, 135, 150)',   // secondary
    'rgb(90, 92, 105)',     // dark
    'rgb(244, 246, 249)'    // light
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

                <?php include 'components/topbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <!-- formulaire d'ajoute -->
                    <div class="modal show" id="addarticleModal" tabindex="-1" aria-labelledby="addarticleModalLabel" aria-hidden="true" style="display: block;">
                        <div class="modal-dialog" style="max-width: 500px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addarticleModalLabel">Ajouter une Article</h5>
                                </div>
                                <div class="modal-body" style="max-height: 80vh; overflow-y: auto; padding: 20px;">
                                    <form action="crud_articles.php" method="POST" enctype="multipart/form-data">
                                        <!-- Titre -->
                                        <div class="mb-3">
                                            <label for="article_title" class="form-label">Titre de l'article</label>
                                            <input 
                                                type="text" 
                                                class="form-control form-control-lg rounded shadow-sm" 
                                                id="article_title" 
                                                name="title" 
                                                placeholder="Saisissez le titre de l'article" 
                                                required>
                                        </div>

                                        <!-- Slug -->
                                        <div class="mb-3">
                                            <label for="article_slug" class="form-label">Slug</label>
                                            <input 
                                                type="text" 
                                                class="form-control form-control-lg rounded shadow-sm" 
                                                id="article_slug" 
                                                name="slug" 
                                                placeholder="Saisissez le slug" 
                                                required>
                                        </div>

                                        <!-- Contenu -->
                                        <div class="mb-3">
                                            <label for="article_content" class="form-label">Contenu</label>
                                            <textarea 
                                                class="form-control rounded shadow-sm" 
                                                id="article_content" 
                                                name="content" 
                                                rows="6" 
                                                placeholder="Écrivez le contenu de l'article" 
                                                required></textarea>
                                        </div>

                                        <!-- Extrait -->
                                        <div class="mb-3">
                                            <label for="article_excerpt" class="form-label">Extrait</label>
                                            <textarea 
                                                class="form-control rounded shadow-sm" 
                                                id="article_excerpt" 
                                                name="excerpt" 
                                                rows="3" 
                                                placeholder="Ajoutez un extrait...."></textarea>
                                        </div>

                                        <!-- Méta-description -->
                                        <div class="mb-3">
                                            <label for="article_meta_description" class="form-label">Méta-description</label>
                                            <textarea 
                                                class="form-control rounded shadow-sm" 
                                                id="article_meta_description" 
                                                name="meta_description" 
                                                rows="2" 
                                                maxlength="160" 
                                                placeholder="Ajoutez une méta-description ..."></textarea>
                                        </div>
                                        <!-- Image mise en avant -->
                                        <div class="mb-3">
                                            <label for="article_featured_image" class="form-label">Image mise en avant</label>
                                            <input type="file" class="form-control" id="article_featured_image" name="featured_image">
                                        </div>

                                         <!-- Catégorie -->
                                         <div class="mb-3">
                                            <label for="article_category" class="form-label">Catégorie</label>
                                        <select class="form-control form-select-lg rounded shadow-sm" id="article_category" name="category_id" required>
                                            <option value="">Choisissez une catégorie</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        </div>
                                        <!-- Tags -->
                                        <div class="mb-3">
                                            <label for="article_tags" class="form-label">Tags</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            <?php foreach ($tags as $tag): ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="tag<?= $tag['id']; ?>" name="tags[]" value="<?= $tag['id']; ?>">
                                                    <label class="form-check-label" for="tag<?= $tag['id']; ?>"><?= $tag['name']; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        </div>
                                            
                                        <!-- Auteur -->
                                        <!-- <div class="mb-3">
                                            <label for="article_author" class="form-label">Auteur</label>
                                            <select 
                                                class="form-control form-select-lg rounded shadow-sm" 
                                                id="article_author" 
                                                name="author_id" 
                                                required>
                                                <option value="1">Auteur 1</option>
                                                <option value="2">Auteur 2</option>
                                            </select>
                                        </div> -->

                                        <!-- Statut -->
                                        <div class="mb-3">
                                            <label for="article_status" class="form-label">Statut</label>
                                            <select 
                                                class="form-control form-select-lg rounded shadow-sm" 
                                                id="article_status" 
                                                name="status">
                                                <option value="soumis">Soumis</option>
                                                <option value="accepté">Accepté</option>
                                                <option value="refusé">Refusé</option>
                                            </select>
                                        </div>

                                        <!-- Date de publication -->
                                        <div class="mb-3">
                                            <label for="article_scheduled_date" class="form-label">Date de publication programmée</label>
                                            <input 
                                                type="datetime-local" 
                                                class="form-control form-control-lg rounded shadow-sm" 
                                                id="article_scheduled_date" 
                                                name="scheduled_date">
                                        </div>

                                        <!-- Boutons -->
                                        <div class="d-flex justify-content-around gap-4">
                                            <button type="submit" class="btn btn-primary px-4">Ajouter</button>
                                            <a href="articles.php" class="btn btn-secondary px-4">Annuler</a>
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Articles</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Category</th>
                                            <th>Tags</th>
                                            <th>Views</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody>
                                    <?php foreach($articles as $article): ?>
                                        <tr>
                                            <td>
                                               
                                                <?= htmlspecialchars($article['title']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($article['author_name']) ?></td>
                                            <td><?= htmlspecialchars($article['category_name']) ?></td>
                                            <td>
                                                <?php
                                                if ($article['tags']) {
                                                    $tags = explode(',', $article['tags']);
                                                    foreach($tags as $tag) {
                                                        echo '<span class="badge badge-primary mr-1">' . htmlspecialchars($tag) . '</span>';
                                                    }
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
                                                <div class="btn-group">
                                                    <a href="view-article.php?id=<?= $article['id'] ?>" 
                                                    class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit-article.php?id=<?= $article['id'] ?>" 
                                                    class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm delete-article" 
                                                            data-id="<?= $article['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody> -->
                                </table>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
        <!-- Initialize the pie chart -->
        <script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart
    var ctx = document.getElementById("categoryPieChart");
    var categoryPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categories) ?>,
            datasets: [{
                data: <?= json_encode($counts) ?>,
                backgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
                hoverBackgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
    </script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
</body>

</html>