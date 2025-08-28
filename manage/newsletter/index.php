<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Newsletter";
$metaDescription = "Manage newsletters and email campaigns in the EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Newsletter</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Newsletter Management</h4>
    <a href="#" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Create Campaign
    </a>
</div>

<!-- Newsletter Management Section -->
<div class="row g-4">

    <!-- Subscribers -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-users text-primary me-2"></i> Subscribers
            </div>
            <div class="card-body">
                <p class="text-muted">List of subscribers will appear here...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-list me-1"></i> View All Subscribers
                </a>
            </div>
        </div>
    </div>

    <!-- Campaigns -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-envelope-open-text text-success me-2"></i> Campaigns
            </div>
            <div class="card-body">
                <p class="text-muted">Previous campaigns will appear here...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-history me-1"></i> View Campaign History
                </a>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-file-alt text-warning me-2"></i> Templates
            </div>
            <div class="card-body">
                <p class="text-muted">Saved email templates will appear here...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-edit me-1"></i> Manage Templates
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-chart-bar text-info me-2"></i> Campaign Analytics
            </div>
            <div class="card-body">
                <p class="text-muted">Engagement stats (open/click rates) will appear here...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-chart-line me-1"></i> View Analytics
                </a>
            </div>
        </div>
    </div>

</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
