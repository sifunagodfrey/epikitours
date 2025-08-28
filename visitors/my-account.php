<?php
// -------------------
// Page Variables
// -------------------
$pageTitle = "My Account";
$pageDescription = "Welcome to your EpikiTours visitor dashboard.";
$pageSlug = "visitors/my-account";

// -------------------
// Optional Custom Banner
// -------------------
$bannerImage = "images/epiki-tours-mountain-top.jpg";

// -------------------
// Page Content Starts
// -------------------
ob_start();
?>
<div class="text-center mb-5">
    <h4 class="mb-3">Welcome to Your Account</h4>
    <p class="text-muted">
        Manage your bookings, messages, and account settings all in one place.
    </p>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <!-- Bookings -->
    <div class="col">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <i class="fas fa-suitcase-rolling fa-2x text-success mb-3"></i>
                <h6>My Bookings</h6>
                <p class="text-muted small">View and manage your current and past tours.</p>
                <a href="my-bookings" class="btn btn-sm btn-outline-primary">View Bookings</a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="col">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                <h6>Messages</h6>
                <p class="text-muted small">View messages or updates from guides and support.</p>
                <a href="inbox" class="btn btn-sm btn-outline-primary">Go to Messages</a>
            </div>
        </div>
    </div>

    <!-- Account Settings -->
    <div class="col">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-cog fa-2x text-secondary mb-3"></i>
                <h6>Account Settings</h6>
                <p class="text-muted small">Update your profile, email, or password.</p>
                <a href="account-settings" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<?php
// -------------------
// Render Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>