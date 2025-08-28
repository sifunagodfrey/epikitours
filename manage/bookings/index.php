<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Manage Bookings";
$metaDescription = "View and manage all bookings in the EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bookings</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Bookings</h4>
    <a href="<?= BASE_URL ?>bookings/add.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Booking
    </a>
</div>

<!-- Bookings Table -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Tour</th>
                        <th>Booking Date</th>
                        <th>Guests</th>
                        <th>Total (KES)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Static Placeholder Row -->
                    <tr>
                        <td colspan="8" class="text-center">Booking data will appear here...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
