<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Users";
$metaDescription = "Manage users, roles, and access in the EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">User Management</h4>
    <a href="#" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add New User
    </a>
</div>

<!-- User Table -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">User data will appear here...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Roles & Permissions -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-user-shield text-primary me-2"></i> Roles
            </div>
            <div class="card-body">
                <p class="text-muted">Manage user roles (Admin, Staff, Customer)...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-cog me-1"></i> Manage Roles
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-lock text-danger me-2"></i> Permissions
            </div>
            <div class="card-body">
                <p class="text-muted">Set permissions for different user roles...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit Permissions
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
