<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Manage Tours";
$metaDescription = "View and manage all tours in the EpikiTours admin panel.";

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
                                    <a href="<?= BASE_URL ?>tours/delete-tour.php?id=<?= $tour['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this tour?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
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

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
