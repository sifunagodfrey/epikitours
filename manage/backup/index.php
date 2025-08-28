<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Backup";
$metaDescription = "Manage and download system backups in the EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Backup</li>
    </ol>
</nav>

<!-- Page Header -->
<h4 class="mb-4">System Backups</h4>

<!-- Backup Actions -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <i class="fas fa-database text-warning me-2"></i> Backup Management
    </div>
    <div class="card-body">
        <p class="text-muted">You can create, download, or restore backups of your database and important files.</p>
        <div class="d-flex gap-2">
            <button class="btn btn-success">
                <i class="fas fa-download me-1"></i> Download Latest Backup
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Create New Backup
            </button>
            <button class="btn btn-danger">
                <i class="fas fa-upload me-1"></i> Restore Backup
            </button>
        </div>
    </div>
</div>

<!-- Backup History -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <i class="fas fa-history text-secondary me-2"></i> Backup History
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>File Name</th>
                        <th>Date Created</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center">No backups available yet...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
