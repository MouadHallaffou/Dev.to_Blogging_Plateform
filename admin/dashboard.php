<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/crud_articles.php';
require_once __DIR__ . '/crud_tags.php';
require_once __DIR__ . '/crud_categories.php';
require_once __DIR__ . '/../public/crud_user.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Article.php';
use App\Src\User;
use Src\Article;
// Prepare data for the chart
$categories = [];
$counts = [];
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
foreach ($category_stats as $stat) {
    $categories[] = $stat['category_name'];
    $counts[] = $stat['article_count'];
}

$topUsers = new User($pdo); 
$top_users = $topUsers->getTopAuthors($pdo);

$topArticles = new Article($pdo); 
$top_articles = $topArticles->get_top_articles($pdo);
         
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($articlesSoumis) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($total_users) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tags
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($tags) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Categories</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($categories) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <!-- Content Column -->
                        <div class="col-xl-8 col-lg-7">
                            <!-- Top Authors Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Top Authors</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Actions:</div>
                                            <a class="dropdown-item" href="users.php">View All Users</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($top_users as $index => $user): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary text-white">
                                                    <!-- <?php if ($user['profile_picture_url']): ?>
                                                        <img src="<?= htmlspecialchars($user['profile_picture_url']) ?>"
                                                            class="rounded-circle"
                                                            style="width: 40px; height: 40px; object-fit: cover;"
                                                            alt="<?= htmlspecialchars($user['username']) ?>">
                                                    <?php else: ?> -->
                                                        <i class="fas fa-user"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-gray-500">Author #<?= $index + 1 ?></div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($user['username']) ?></div>
                                                <div class="text-gray-800">
                                                    <?= number_format($user['article_count']) ?> articles
                                                    <span class="mx-1">•</span>
                                                    <?= number_format((int)$user['total_views']) ?> total views
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <a href=""
                                                    class="btn btn-primary btn-sm">
                                                    View Profile
                                                </a>
                                            </div>
                                        </div>
                                        <?php if ($index < count($top_users) - 1): ?>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Top Articles Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Most Read Articles</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink2">
                                            <div class="dropdown-header">Actions:</div>
                                            <a class="dropdown-item" href="./entities/articles/articles.php">View All Articles</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($top_articles as $index => $article): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success text-white">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-gray-500">
                                                    Published <?= date('M d, Y', strtotime($article['created_at'])) ?>
                                                    by <?= htmlspecialchars($article['author_name']) ?>
                                                </div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($article['title']) ?></div>
                                                <div class="text-gray-800">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <?= number_format($article['views']) ?> views
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <a href="./entities/articles/view-article.php?id=<?= $article['id'] ?>"
                                                    class="btn btn-success btn-sm">
                                                    Read Article
                                                </a>
                                            </div>
                                        </div>
                                        <?php if ($index < count($top_articles) - 1): ?>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Category Distribution</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Category Actions:</div>
                                            <a class="dropdown-item" href="./entities/categories/categories.php">Manage Categories</a>
                                            <a class="dropdown-item" href="./entities/categories/add-category.php">Add Category</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="categoryPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <?php foreach ($category_stats as $index => $stat): ?>
                                            <span class="mr-2">
                                                <i class="fas fa-circle" style="color: <?= $colors[$index % count($colors)] ?>"></i>
                                                <?= htmlspecialchars($stat['category_name']) ?>
                                                (<?= $stat['article_count'] ?>)
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($articlesSoumis as $articlesm): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($articlesm['title']) ?></td>
                                                <td><?= htmlspecialchars($articlesm['author_name']) ?></td> 
                                                <td><?= htmlspecialchars($articlesm['category_name']) ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($articlesm['tags'])) {
                                                        $tags = explode(',', $articlesm['tags']);
                                                        foreach ($tags as $tag) {
                                                            echo '<span class="badge badge-primary mr-1">' . htmlspecialchars($tag) . '</span>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= number_format($articlesm['views']) ?></td>
                                                <td><?= date('M d, Y H:i', strtotime($articlesm['created_at'])) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $articlesm['status'] === 'soumis' ? 'warning' : ($articlesm['status'] === 'accepte' ? 'success' : 'danger') ?>">
                                                        <?= ucfirst($articlesm['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <form action="change-status.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="article_id" value="<?= $articlesm['article_id'] ?>">
                                                        <input type="hidden" name="status" value="accepte">
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                <?= $articlesm['status'] !== 'soumis' ? 'disabled' : '' ?>>
                                                            Accepter
                                                        </button>
                                                    </form>
                                                    <form action="change-status.php" method="POST" style="display:inline;">
                                                        <input type="hidden" name="article_id" value="<?= $articlesm['article_id'] ?>">
                                                        <input type="hidden" name="status" value="refuse">
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                <?= $articlesm['status'] !== 'soumis' ? 'disabled' : '' ?>>
                                                            Refuser
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
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
                    <a class="btn btn-primary" href="../public/Logout.php">Logout</a>
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
                            var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
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