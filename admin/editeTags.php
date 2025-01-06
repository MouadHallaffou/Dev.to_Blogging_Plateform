<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . './crud_tags.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevBlog - Dashboard</title>
    <!-- Styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <?php include 'components/topbar.php'; ?>

                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                tags
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= count($tags) ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modale de mise à jour -->
                    <?php if ($tag): ?>
                    <div class="modal show" id="updatetagModal" tabindex="-1" aria-labelledby="updatetagModalLabel" aria-hidden="true" style="display: block;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updatetagModalLabel">Modifier Tags</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="crud_tags.php" method="POST">
                                        <input type="hidden" name="tag_id" value="<?= htmlspecialchars($tag['id']) ?>">
                                        <div class="mb-3">
                                            <label for="tag_name" class="form-label">Nom de tag</label>
                                            <input type="text" class="form-control" id="tag_name" name="tagEdit_name" value="<?= htmlspecialchars($tag['name']) ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Modifier</button>
                                        <a href="tags.php" class="btn btn-secondary">Annuler</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">tags</h6>
                        </div>
                        <div class="card-body">
                            <div class="overflow-auto" style="max-height: 260px;">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tags as $tag): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tag['id']) ?></td>
                                            <td><?= htmlspecialchars($tag['name']) ?></td>
                                            <td>
                                                <a href="edittags.php?action=edit&id=<?= $tag['id'] ?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="crud_tag_cat.php?action=delete&id=<?= $tag['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de supprimer cette catégorie ?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php include 'components/footer.php'; ?>
        </div>
    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
</body>

</html>
