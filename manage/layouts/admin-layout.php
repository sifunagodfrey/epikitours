<?php
// -------------------
// layouts/admin-layout.php
// -------------------

// Map routes
require_once __DIR__ . '/../routes.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../helpers/auth_helpers.php';

// -------------------
// Restrict access to admins only
// -------------------
if (!isset($_SESSION['user_id']) || !hasRole('admin')) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= ($pageTitle ?? 'Admin Dashboard') . ' | Epikitours' ?></title>
    <meta name="description" content="<?= $metaDescription ?? 'Welcome to the Epikitours admin dashboard.' ?>">
    <meta name="keywords" content="Epikitours, Travel, Tours, Admin, Dashboard">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>images/epikitours-icon.png" />

    <!-- CSS Files -->
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= BASE_URL ?>css/custom.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Topbar -->
    <?php include __DIR__ . '/../includes/top-bar.php'; ?>

    <!-- Layout -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="sidebar bg-white border-end px-0 col-md-3 col-lg-2 d-md-block">
                <div class="list-group list-group-flush shadow-sm">

                    <!-- Dashboard -->
                    <a href="<?= BASE_URL ?>dashboard"
                        class="list-group-item list-group-item-action <?= isActive('dashboard') ?>">
                        <i class="fas fa-tachometer-alt text-primary me-2"></i> Dashboard
                    </a>

                    <!-- Tours -->
                    <a href="<?= BASE_URL ?>tours/"
                        class="list-group-item list-group-item-action <?= isActive('tours') ?>">
                        <i class="fas fa-map-marked-alt text-success me-2"></i> Tours
                    </a>

                    <!-- Bookings -->
                    <a href="<?= BASE_URL ?>bookings/"
                        class="list-group-item list-group-item-action <?= isActive('bookings') ?>">
                        <i class="fas fa-calendar-check text-info me-2"></i> Bookings
                    </a>

                    <!-- Payments -->
                    <a href="<?= BASE_URL ?>payments/"
                        class="list-group-item list-group-item-action <?= isActive('payments') ?>">
                        <i class="fas fa-credit-card text-warning me-2"></i> Payments
                    </a>

                    <!-- Messages -->
                    <a href="<?= BASE_URL ?>messages/"
                        class="list-group-item list-group-item-action <?= isActive('messages') ?>">
                        <i class="fas fa-comments text-secondary me-2"></i> Messages
                    </a>

                    <!-- Reports -->
                    <a href="<?= BASE_URL ?>reports/"
                        class="list-group-item list-group-item-action <?= isActive('reports') ?>">
                        <i class="fas fa-chart-bar text-dark me-2"></i> Reports
                    </a>

                    <!-- Newsletter -->
                    <a href="<?= BASE_URL ?>newsletter/"
                        class="list-group-item list-group-item-action <?= isActive('newsletter') ?>">
                        <i class="fas fa-envelope-open-text text-primary me-2"></i> Newsletter
                    </a>

                    <!-- Users -->
                    <a href="<?= BASE_URL ?>users/"
                        class="list-group-item list-group-item-action <?= isActive('users') ?>">
                        <i class="fas fa-users text-primary me-2"></i> Users
                    </a>

                    <!-- Settings -->
                    <a href="<?= BASE_URL ?>settings/"
                        class="list-group-item list-group-item-action <?= isActive('settings') ?>">
                        <i class="fas fa-sliders-h text-dark me-2"></i> Settings
                    </a>

                    <!-- Backup -->
                    <a href="<?= BASE_URL ?>backup/"
                        class="list-group-item list-group-item-action <?= isActive('backup') ?>">
                        <i class="fas fa-database text-warning me-2"></i> Backup
                    </a>

                    <!-- Logout -->
                    <a href="<?= BASE_URL ?>logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>

                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
                <?= $pageContent ?? '' ?>
            </main>
        </div>
    </div>

    <!-- JS Files -->
    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>js/custom.js"></script>

</body>

</html>