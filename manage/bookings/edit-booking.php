<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Update Booking";
$metaDescription = "Edit existing booking in EpikiTours admin panel.";

$errors = [];
$success = "";

// -------------------
// Session start
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Get booking ID from URL
// -------------------
$booking_id = $_GET['id'] ?? null;
if (!$booking_id) {
    die("Booking ID not provided.");
}

// -------------------
// Fetch booking record
// -------------------
$stmt = $pdo->prepare("SELECT * FROM epi_bookings WHERE id = :id");
$stmt->execute([':id' => $booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

// -------------------
// Handle Form Submit
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id   = $_POST['customer_id'] ?? '';
    $tour_id       = $_POST['tour_id'] ?? '';
    $tour_guide    = $_POST['tour_guide'] ?? '';
    $total_amount  = $_POST['total_amount'] ?? '';
    $status        = $_POST['status'] ?? 'pending';
    $booking_date  = $_POST['booking_date'] ?? '';

    // Validation
    if ($customer_id === '') $errors[] = "Customer is required.";
    if ($tour_id === '') $errors[] = "Tour is required.";
    if ($tour_guide === '') $errors[] = "Tour guide is required.";
    if ($total_amount === '' || !is_numeric($total_amount)) $errors[] = "Valid tour amount is required.";
    if ($status === '') $errors[] = "Status is required.";
    if ($booking_date === '') $errors[] = "Booking date is required.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE epi_bookings
                SET customer_id = :customer_id,
                    tour_id = :tour_id,
                    tour_guide = :tour_guide,
                    total_amount = :total_amount,
                    status = :status,
                    booking_date = :booking_date
                WHERE id = :id
            ");
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':tour_id' => $tour_id,
                ':tour_guide' => $tour_guide,
                ':total_amount' => $total_amount,
                ':status' => $status,
                ':booking_date' => $booking_date,
                ':id' => $booking_id
            ]);
            $success = "Booking successfully updated!";
            // Refresh booking data
            $stmt = $pdo->prepare("SELECT * FROM epi_bookings WHERE id = :id");
            $stmt->execute([':id' => $booking_id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// -------------------
// Fetch customers (visitors) and guides for dropdowns
// -------------------
$customers = $pdo->query("SELECT id, first_name, last_name FROM epi_users WHERE user_role = 'visitor' ORDER BY first_name")->fetchAll(PDO::FETCH_ASSOC);
$guides    = $pdo->query("SELECT id, first_name, last_name FROM epi_users WHERE user_role = 'guide' ORDER BY first_name")->fetchAll(PDO::FETCH_ASSOC);
$tours     = $pdo->query("SELECT id, title FROM epi_tours ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>bookings">Bookings</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Booking</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Update Booking</h4>
</div>

<!-- Alerts -->
<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<!-- Update Booking Form -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="post">
            <!-- Customer -->
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($booking['customer_id'] == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['first_name'].' '.$c['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tour -->
            <div class="mb-3">
                <label for="tour_id" class="form-label">Tour</label>
                <select name="tour_id" id="tour_id" class="form-control" required>
                    <option value="">-- Select Tour --</option>
                    <?php foreach ($tours as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($booking['tour_id'] == $t['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tour Guide -->
            <div class="mb-3">
                <label for="tour_guide" class="form-label">Tour Guide</label>
                <select name="tour_guide" id="tour_guide" class="form-control" required>
                    <option value="">-- Select Guide --</option>
                    <?php foreach ($guides as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= ($booking['tour_guide'] == $g['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['first_name'].' '.$g['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tour Price -->
            <div class="mb-3">
                <label for="total_amount" class="form-label">Tour Price (KES)</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" 
                       value="<?= htmlspecialchars($booking['total_amount']) ?>" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" <?= ($booking['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= ($booking['status'] == 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                    <option value="cancelled" <?= ($booking['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <!-- Booking Date -->
            <div class="mb-3">
                <label for="booking_date" class="form-label">Booking Date</label>
                <input type="date" name="booking_date" id="booking_date" 
                       value="<?= htmlspecialchars($booking['booking_date']) ?>" class="form-control" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Update Booking
            </button>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
