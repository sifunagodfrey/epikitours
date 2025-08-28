<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "Newsletters";
$pageDescription = "Manage your newsletter subscriptions on EpikiTours.";
$pageSlug = "visitors/news-letter";

// -------------------
// Start session
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Protect page (must be logged in)
// -------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// -------------------
// Database connection
// -------------------
require_once __DIR__ . '/../config/database.php';

// -------------------
// Fetch newsletter subscription status (example table: epi_newsletters)
// -------------------
$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT subscribed FROM epi_newsletters WHERE user_id = :uid LIMIT 1");
    $stmt->execute(['uid' => $userId]);
    $subscription = $stmt->fetch(PDO::FETCH_ASSOC);
    $isSubscribed = $subscription['subscribed'] ?? 0;
} catch (PDOException $e) {
    error_log("Newsletter Fetch Error: " . $e->getMessage());
    $isSubscribed = 0;
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">Newsletter Subscription</h4>
    <p class="text-muted">Manage your subscription to EpikiTours newsletters and stay updated with latest tours and
        offers.</p>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['success']); ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm rounded-3">
            <div class="card-body p-4">

                <!-- Newsletter Form -->
                <form action="../helpers/process-newsletter.php" method="POST">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="subscribed" name="subscribed" value="1"
                            <?= $isSubscribed ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="subscribed">
                            Subscribe to EpikiTours newsletters
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Subscription
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>