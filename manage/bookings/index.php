<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Manage Bookings";
$metaDescription = "View and manage all bookings in the EpikiTours admin panel.";

// -------------------
// Handle Delete Request
// -------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_booking_id'])) {
    $bookingToDelete = intval($_POST['delete_booking_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM epi_bookings WHERE id = :id");
        $stmt->execute([':id' => $bookingToDelete]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_success'] = "Booking deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Booking not found or could not be deleted.";
        }
    } catch (PDOException $e) {
        $_SESSION['flash_error'] = "Error: " . $e->getMessage();
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// -------------------
// Flash Messages
// -------------------
$deleteSuccess = $_SESSION['flash_success'] ?? "";
$deleteError   = $_SESSION['flash_error'] ?? "";
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// -------------------
// Fetch bookings
// -------------------
try {
    $stmt = $pdo->query("
        SELECT 
            b.id,
            b.booking_date,
            b.total_amount,
            b.status,
            b.confirmation_code,
            c.first_name AS customer_first,
            c.last_name AS customer_last,
            g.first_name AS guide_first,
            g.last_name AS guide_last,
            t.title AS tour_title
        FROM epi_bookings b
        JOIN epi_users c ON b.customer_id = c.id
        JOIN epi_users g ON b.tour_guide = g.id
        JOIN epi_tours t ON b.tour_id = t.id
        ORDER BY b.booking_date DESC
    ");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching bookings: ' . $e->getMessage());
}

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
    <a href="<?= BASE_URL ?>bookings/add-booking.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Booking
    </a>
</div>

<!-- Delete Alerts -->
<?php if ($deleteSuccess): ?>
  <div class="alert alert-success"><?= $deleteSuccess ?></div>
<?php elseif ($deleteError): ?>
  <div class="alert alert-danger"><?= $deleteError ?></div>
<?php endif; ?>

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
                        <th>Tour Guide</th>
                        <th>Total (KES)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($booking['customer_first'] . ' ' . $booking['customer_last']) ?></td>
                                <td><?= htmlspecialchars($booking['tour_title']) ?></td>
                                <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                                <td><?= htmlspecialchars($booking['guide_first'] . ' ' . $booking['guide_last']) ?></td>
                                <td><?= number_format($booking['total_amount'], 2) ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($booking['status']) {
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>bookings/edit-booking.php?id=<?= $booking['id'] ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-booking-id="<?= $booking['id'] ?>"
                                            data-customer-name="<?= htmlspecialchars($booking['customer_first'].' '.$booking['customer_last']) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete the booking for <strong id="deleteCustomerName"></strong>?
        </div>
        <div class="modal-footer">
          <input type="hidden" name="delete_booking_id" id="deleteBookingId">
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS to bind modal -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const bookingId = button.getAttribute('data-booking-id');
    const customerName = button.getAttribute('data-customer-name');

    deleteModal.querySelector('#deleteBookingId').value = bookingId;
    deleteModal.querySelector('#deleteCustomerName').textContent = customerName;
  });
});
</script>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
