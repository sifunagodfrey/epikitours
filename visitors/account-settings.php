<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "Account Settings";
$pageDescription = "Update your profile information and change your password on EpikiTours.";
$pageSlug = "visitors/account-settings";

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
// Fetch current user info
// -------------------
$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM epi_users WHERE id = :uid");
    $stmt->execute(['uid' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Account Settings Fetch Error: " . $e->getMessage());
    $user = [];
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">Account Settings</h4>
    <p class="text-muted">Update your personal information or change your password.</p>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['success']); ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm rounded-3 w-100">
            <div class="p-4">

                <!-- Update Profile Form -->
                <form action="includes/process-account-settings.php" method="POST">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            value="<?= htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>

                    <hr>

                    <h6>Change Password</h6>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Account
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