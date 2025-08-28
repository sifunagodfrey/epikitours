<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic Title -->
    <title><?php echo $pageTitle; ?> – Epiki Tours</title>

    <!-- Meta Description -->
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords"
        content="Epiki Tours, Travel Kenya, Safari Adventures, Holiday Packages, Tours and Travel, Africa Exploration">

    <!-- Favicon -->
    <link rel="icon" href="images/epiki-tours-logo.png?v=1" type="image/png" />

    <!-- Author -->
    <meta name="author" content="Epiki Tours">

    <!-- Open Graph (Facebook) -->
    <meta property="og:title" content="<?php echo $pageTitle; ?> – Epiki Tours">
    <meta property="og:description" content="<?php echo $pageDescription; ?>">
    <meta property="og:image" content="<?php echo $bannerImage ?? 'images/epiki-tours-banner.jpg'; ?>">
    <meta property="og:url" content="https://epikitours.com/<?php echo $pageSlug; ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?> – Epiki Tours">
    <meta name="twitter:description" content="<?php echo $pageDescription; ?>">
    <meta name="twitter:image" content="<?php echo $bannerImage ?? 'images/epiki-tours-card.jpg'; ?>">

    <!-- CSS Links -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- Top Bar and Navigation -->
    <?php include 'includes/main-header.php'; ?>

    <!-- Page Banner -->
    <section class="position-relative" style="
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.4)), 
                    url('<?php echo $bannerImage ?? 'images/epiki-tours-banner.jpg'; ?>') center/cover no-repeat;
        height: 250px;">
        <div class="text-center text-white"
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <h1 class="fw-bold display-6"><?php echo $pageTitle; ?></h1>
            <?php if (!empty($pageDescription)): ?>
                <p class="lead"><?php echo $pageDescription; ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <?php echo $pageContent; ?>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JS Scripts -->
    <script src="js/custom.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>