<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Settings";
$metaDescription = "Manage application settings for EpikiTours admin panel.";

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
    </ol>
</nav>

<!-- Page Header -->
<h4 class="mb-4">Application Settings</h4>

<!-- General Settings -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <i class="fas fa-sliders-h text-primary me-2"></i> General Settings
    </div>
    <div class="card-body">
        <form>
            <div class="mb-3">
                <label for="siteName" class="form-label">Site Name</label>
                <input type="text" id="siteName" class="form-control" placeholder="EpikiTours">
            </div>
            <div class="mb-3">
                <label for="siteEmail" class="form-label">Support Email</label>
                <input type="email" id="siteEmail" class="form-control" placeholder="support@epikitours.com">
            </div>
            <div class="mb-3">
                <label for="currency" class="form-label">Default Currency</label>
                <select id="currency" class="form-select">
                    <option value="KES" selected>KES - Kenyan Shilling</option>
                    <option value="USD">USD - US Dollar</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<!-- Security Settings -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light">
        <i class="fas fa-lock text-danger me-2"></i> Security Settings
    </div>
    <div class="card-body">
        <form>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="twoFactorAuth">
                <label class="form-check-label" for="twoFactorAuth">Enable Two-Factor Authentication</label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="maintenanceMode">
                <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
            </div>
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-shield-alt me-1"></i> Update Security
            </button>
        </form>
    </div>
</div>

<!-- System Info -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-light">
        <i class="fas fa-info-circle text-secondary me-2"></i> System Information
    </div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            <li><strong>PHP Version:</strong> <?= phpversion() ?></li>
            <li><strong>Server Software:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></li>
            <li><strong>Database:</strong> MySQL</li>
        </ul>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
