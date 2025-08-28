<?php
// -------------------
// visitors-dashboard-layout.php
// Layout for visitor dashboard pages
// -------------------
require_once __DIR__ . '/../../routes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Authentication Check
// -------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic Title -->
    <title><?php echo $pageTitle; ?> – EpikiTours</title>

    <!-- Meta Description -->
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="EpikiTours, Travel Kenya, Safari Tours, Adventure Holidays, Guided Tours, Vacations">

    <!-- Favicon -->
    <link rel="icon" href="../images/epikitours-icon.png?v=1" type="image/png" />

    <!-- Author -->
    <meta name="author" content="EpikiTours">

    <!-- Open Graph (Facebook) -->
    <meta property="og:title" content="<?php echo $pageTitle; ?> – EpikiTours">
    <meta property="og:description" content="<?php echo $pageDescription; ?>">
    <meta property="og:image" content="../images/epiki-tours-cover.jpg">
    <meta property="og:url" content="https://epikitours.com/<?php echo $pageSlug; ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?> – EpikiTours">
    <meta name="twitter:description" content="<?php echo $pageDescription; ?>">
    <meta name="twitter:image" content="../images/epiki-tours-twitter-card.jpg">

    <!-- CSS Links -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Top Bar and Navigation -->
    <?php include BASE_PATH . 'includes/main-header.php'; ?>

    <!-- Hamburger button (visible only on mobile) -->
    <div class="container mt-3 d-md-none">
        <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboardMenu"
            aria-controls="dashboardMenu">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Offcanvas Sidebar (mobile menu) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="dashboardMenu"
        aria-labelledby="dashboardMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="dashboardMenuLabel">My Account</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <?php include 'includes/sidebar-menu.php'; ?>
        </div>
    </div>

    <!-- Dashboard Wrapper -->
    <div class="container py-4">
        <div class="row">
            <!-- Sidebar (visible on md+ screens) -->
            <div class="col-md-3 mb-4 d-none d-md-block">
                <?php include 'includes/sidebar-menu.php'; ?>
            </div>
            <!-- Main Dashboard Content -->
            <div class="col-md-9">
                <?php echo $pageContent; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <!-- JS Scripts -->
    <script src="../js/custom.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>