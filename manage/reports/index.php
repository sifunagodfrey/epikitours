<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Reports";
$metaDescription = "View system and business performance reports in the EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Reports</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Reports & Analytics</h4>
    <button class="btn btn-outline-secondary">
        <i class="fas fa-download me-1"></i> Export Report
    </button>
</div>

<!-- Reports Section -->
<div class="row g-4">

    <!-- Sales Report -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-chart-line text-primary me-2"></i> Sales Overview
            </div>
            <div class="card-body">
                <p class="text-muted">Charts and sales summary will appear here...</p>
            </div>
        </div>
    </div>

    <!-- Booking Trends -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-calendar-check text-success me-2"></i> Booking Trends
            </div>
            <div class="card-body">
                <p class="text-muted">Booking statistics will appear here...</p>
            </div>
        </div>
    </div>

    <!-- Customer Insights -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-users text-info me-2"></i> Customer Insights
            </div>
            <div class="card-body">
                <p class="text-muted">Customer demographics and behavior data will appear here...</p>
            </div>
        </div>
    </div>

    <!-- System Logs -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-clipboard-list text-warning me-2"></i> System Logs
            </div>
            <div class="card-body">
                <p class="text-muted">System logs and recent activity will appear here...</p>
            </div>
        </div>
    </div>

</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
