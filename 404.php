<?php
// ---------------------------
// Page Metadata
// ---------------------------
$pageTitle = "Page Not Found";
$pageDescription = "The page you’re looking for doesn’t exist on Epiki Tours.";
$pageSlug = "404";
$bannerImage = "images/epiki-tours-banner.jpg"; // Update with a travel-themed image

// Start output buffering
ob_start();
?>

<!-- 404 Not Found Section -->
<div class="container my-5 text-center">
    <h1 class="display-4 text-primary mb-3">Lost in the Journey?</h1>
    <p class="lead text-muted">
        Oops! The page you’re looking for doesn’t exist, has been moved, or is taking a different route.
    </p>

    <div class="mt-4">
        <a href="/" class="btn btn-primary me-2">🏠 Back to Homepage</a>
        <a href="contact-us" class="btn btn-outline-primary">📩 Contact Support</a>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>