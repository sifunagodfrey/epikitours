<?php
// -------------------
// manage/index.php
// -------------------
declare(strict_types=1);

// -------------------
// Load routes (defines BASE_URL, BASE_PATH) and DB
// -------------------
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/../config/database.php';

// -------------------
// Start session (if not already started)
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Page metadata (used by the layout)
// -------------------
$pageTitle = 'Admin Dashboard';
$metaDescription = 'Overview of the Epikitours admin panel.';

// -------------------
// Begin capturing page content
// -------------------
ob_start();

// -------------------
// Fetch some safe summary metrics using only tables/columns available
// (Using only epi_users as requested — no assumptions about other tables.)
// -------------------
try {
    $totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM epi_users")->fetchColumn();
    $adminCount = (int) $pdo->query("SELECT COUNT(*) FROM epi_users WHERE user_role = 'admin'")->fetchColumn();
    $guideCount = (int) $pdo->query("SELECT COUNT(*) FROM epi_users WHERE user_role = 'guide'")->fetchColumn();
    $visitorCount = (int) $pdo->query("SELECT COUNT(*) FROM epi_users WHERE user_role = 'visitor'")->fetchColumn();
} catch (PDOException $e) {
    // -------------------
    // If queries fail, show zeros (layout will still load).
    // -------------------
    $totalUsers = $adminCount = $guideCount = $visitorCount = 0;
}

// -------------------
// Optionally fetch the logged-in user's name for a greeting.
// We use a simple prepared statement against epi_users (exists in your schema).
// -------------------
$greetingName = '';
if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT first_name, last_name FROM epi_users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($u) {
            $greetingName = htmlspecialchars(trim($u['first_name'] . ' ' . $u['last_name']));
        }
    } catch (PDOException $e) {
        $greetingName = '';
    }
}
?>

<!-- -------------------
     Admin Dashboard Content
     ------------------- -->
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0"><?= htmlspecialchars($pageTitle) ?></h1>
            <p class="text-muted small mb-0"><?= htmlspecialchars($metaDescription) ?></p>
        </div>
        <div class="text-end">
            <?php if ($greetingName): ?>
                <small class="text-muted">Hello, <strong><?= $greetingName ?></strong></small>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="mb-2">Total Users</h6>
                <h3 class="mb-0"><?= number_format($totalUsers) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="mb-2">Admins</h6>
                <h3 class="mb-0"><?= number_format($adminCount) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="mb-2">Guides</h6>
                <h3 class="mb-0"><?= number_format($guideCount) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6 class="mb-2">Visitors</h6>
                <h3 class="mb-0"><?= number_format($visitorCount) ?></h3>
            </div>
        </div>
    </div>

    <!-- A simple quick-links row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>admin/users" class="btn btn-outline-primary">Manage Users</a>
                <a href="<?= BASE_URL ?>admin/tours" class="btn btn-outline-success">Tours (if implemented)</a>
                <a href="<?= BASE_URL ?>admin/bookings" class="btn btn-outline-info">Bookings (if implemented)</a>
                <a href="<?= BASE_URL ?>admin/settings" class="btn btn-outline-secondary">Settings</a>
            </div>
        </div>
    </div>

    <!-- Placeholder for future widgets -->
    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-0 text-muted">No additional modules enabled yet. Add tour & booking modules to show more
                insights here.</p>
        </div>
    </div>
</div>

<?php
// -------------------
// End buffer & render through admin layout
// -------------------
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
