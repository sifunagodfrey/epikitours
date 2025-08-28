<?php
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Dashboard";
$metaDescription = "Overview of key metrics and activities in the EpikiTours admin panel.";

// -------------------
// Fetch Dashboard Data
// -------------------

// Total Bookings
$totalBookings = $pdo->query("SELECT COUNT(*) FROM epi_bookings")->fetchColumn();

// Total Tours
$totalTours = $pdo->query("SELECT COUNT(*) FROM epi_tours")->fetchColumn();

// Total Payments (KES)
$totalPayments = $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM epi_payments")->fetchColumn();

// Total Users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM epi_users")->fetchColumn();

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>

<h4 class="mb-4">Admin Dashboard</h4>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <!-- Total Bookings -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-2x text-info mb-2"></i>
                <h5 class="card-title">Bookings</h5>
                <p class="card-text fw-bold"><?= number_format($totalBookings) ?></p>
            </div>
        </div>
    </div>

    <!-- Total Tours -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <i class="fas fa-map-marked-alt fa-2x text-success mb-2"></i>
                <h5 class="card-title">Tours</h5>
                <p class="card-text fw-bold"><?= number_format($totalTours) ?></p>
            </div>
        </div>
    </div>

    <!-- Total Payments -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <i class="fas fa-credit-card fa-2x text-warning mb-2"></i>
                <h5 class="card-title">Payments</h5>
                <p class="card-text fw-bold">KES <?= number_format($totalPayments) ?></p>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h5 class="card-title">Users</h5>
                <p class="card-text fw-bold"><?= number_format($totalUsers) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity / Reports Placeholder -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-chart-bar me-2 text-dark"></i> Recent Reports
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Report data will appear here...</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-comments me-2 text-secondary"></i> Latest Messages
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">No new messages yet...</p>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
