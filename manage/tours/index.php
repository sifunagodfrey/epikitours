<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Manage Tours";
$metaDescription = "View and manage all tours in the EpikiTours admin panel.";

// -------------------
// Handle Delete Request
// -------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_tour_id'])) {
    $tourToDelete = intval($_POST['delete_tour_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM epi_tours WHERE id = :id");
        $stmt->execute([':id' => $tourToDelete]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_success'] = "Tour deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Tour not found or could not be deleted.";
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
// Fetch Tours
// -------------------
try {
    $stmt = $pdo->query("
        SELECT id, title, location, start_date, end_date, status, created_at 
        FROM epi_tours 
        ORDER BY created_at DESC
    ");
    $tours = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching tours: " . $e->getMessage());
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tours</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Tours</h4>
    <a href="<?= BASE_URL ?>tours/add-tour.php" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Tour
    </a>
</div>

<!-- Delete Alerts -->
<?php if ($deleteSuccess): ?>
  <div class="alert alert-success"><?= $deleteSuccess ?></div>
<?php elseif ($deleteError): ?>
  <div class="alert alert-danger"><?= $deleteError ?></div>
<?php endif; ?>

<!-- Tours Table -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tours)): ?>
                        <?php foreach ($tours as $index => $tour): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($tour['title']) ?></td>
                                <td><?= htmlspecialchars($tour['location'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($tour['start_date']) ?></td>
                                <td><?= htmlspecialchars($tour['end_date']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($tour['status']) {
                                        'upcoming' => 'info',
                                        'ongoing' => 'success',
                                        'completed' => 'secondary',
                                        'canceled' => 'danger',
                                        default => 'dark'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($tour['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>tours/edit-tour.php?id=<?= $tour['id'] ?>"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-tour-id="<?= $tour['id'] ?>"
                                            data-tour-title="<?= htmlspecialchars($tour['title']) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No tours found.</td>
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
          Are you sure you want to delete the tour <strong id="deleteTourTitle"></strong>?
        </div>
        <div class="modal-footer">
          <input type="hidden" name="delete_tour_id" id="deleteTourId">
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
    const tourId = button.getAttribute('data-tour-id');
    const tourTitle = button.getAttribute('data-tour-title');

    deleteModal.querySelector('#deleteTourId').value = tourId;
    deleteModal.querySelector('#deleteTourTitle').textContent = tourTitle;
  });
});
</script>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
