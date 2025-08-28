<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "My Bookings";
$pageDescription = "View and manage your EpikiTours bookings.";
$pageSlug = "visitors/my-bookings";

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
// Fetch user bookings
// -------------------
$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("
        SELECT b.id AS booking_id, b.booking_date, b.status, b.confirmation_code,
               t.title AS tour_title, t.start_date, t.end_date, t.location
        FROM epi_bookings b
        JOIN epi_tours t ON b.tour_id = t.id
        WHERE b.user_id = :uid
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute(['uid' => $userId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Booking Fetch Error: " . $e->getMessage());
    $bookings = [];
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">My Bookings</h4>
    <p class="text-muted">View your tour bookings, their status, and manage them here.</p>
</div>

<?php if (empty($bookings)): ?>
    <div class="alert alert-info text-center">You have no bookings yet. Browse tours to make a booking.</div>
<?php else: ?>
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Tour</th>
                    <th>Dates</th>
                    <th>Location</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Confirmation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($booking['tour_title']); ?></td>
                        <td><?= date("d M Y", strtotime($booking['start_date'])) . " - " . date("d M Y", strtotime($booking['end_date'])); ?>
                        </td>
                        <td><?= htmlspecialchars($booking['location']); ?></td>
                        <td><?= date("d M Y H:i", strtotime($booking['booking_date'])); ?></td>
                        <td>
                            <?php
                            $statusClass = match ($booking['status']) {
                                'pending' => 'badge bg-warning',
                                'confirmed' => 'badge bg-success',
                                'canceled' => 'badge bg-danger',
                                default => 'badge bg-secondary'
                            };
                            ?>
                            <span class="<?= $statusClass; ?>"><?= ucfirst($booking['status']); ?></span>
                        </td>
                        <td><?= htmlspecialchars($booking['confirmation_code'] ?? '-'); ?></td>
                        <td>
                            <?php if ($booking['status'] === 'pending'): ?>
                                <a href="cancel-booking.php?id=<?= $booking['booking_id']; ?>"
                                    class="btn btn-sm btn-danger">Cancel</a>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>