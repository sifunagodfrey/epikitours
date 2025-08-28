<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], [3, 4])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$pageTitle = "My Address";
$pageDescription = "Manage your delivery address.";
$pageSlug = "customers/my-address";
$bannerImage = "../images/isokoni-online-shopping.jpg";

// Fetch user profile
$stmt = $pdo->prepare("
    SELECT street_address, country, county, delivery_location
    FROM isk_user_profiles
    WHERE user_id = ?
");
$stmt->execute([$userId]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Extract values
$streetAddress = $profile['street_address'] ?? '';
$country = $profile['country'] ?? 'Kenya';
$selectedCounty = $profile['county'] ?? '';
$selectedLocation = $profile['delivery_location'] ?? '';

// Fetch counties
$counties = $pdo->query("SELECT id, name FROM isk_counties ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch delivery locations based on selected county (name match)
$countyRow = array_filter($counties, fn($c) => $c['name'] === $selectedCounty);
$countyId = $countyRow ? array_values($countyRow)[0]['id'] : null;

$locationOptions = [];
if ($countyId) {
    $stmt = $pdo->prepare("SELECT id, location_name FROM isk_location_pricing WHERE county_id = ?");
    $stmt->execute([$countyId]);
    $locationOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

ob_start();
?>

<div class="mb-5">
    <h5 class="mb-3">Saved Delivery Address</h5>
    <p class="text-muted small">Update your preferred delivery address for future orders.</p>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="my-address-update.php" method="post" class="card shadow-sm p-4 bg-white">

        <!-- Street Address -->
        <div class="mb-3">
            <label for="street_address" class="form-label">Street / Building <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="street_address" name="street_address"
                value="<?= htmlspecialchars($streetAddress) ?>" required>
        </div>

        <!-- County -->
        <div class="mb-3">
            <label for="county" class="form-label">County <span class="text-danger">*</span></label>
            <select class="form-select" id="county" name="county" required>
                <option value="">-- Select County --</option>
                <?php foreach ($counties as $county): ?>
                    <option value="<?= htmlspecialchars($county['name']) ?>" <?= ($selectedCounty === $county['name']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($county['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Delivery Location -->
        <div class="mb-3">
            <label for="delivery_location" class="form-label">Delivery Location <span
                    class="text-danger">*</span></label>
            <input type="text" class="form-control" id="delivery_location" name="delivery_location"
                value="<?= htmlspecialchars($selectedLocation ?? '') ?>" required>
        </div>

        <!-- Country -->
        <div class="mb-4">
            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="country" name="country"
                value="<?= htmlspecialchars($country) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-save me-1"></i> Save Address
        </button>
    </form>
</div>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>