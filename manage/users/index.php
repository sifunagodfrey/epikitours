<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Users";
$metaDescription = "Manage users, roles, and access in the EpikiTours admin panel.";

// -------------------
// Fetch all users
// -------------------
$users = $pdo->query("SELECT * FROM epi_users ORDER BY created_at DESC");

// -------------------
// Delete User
// -------------------
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user_id'])) {
    $userToDelete = intval($_POST['delete_user_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM epi_users WHERE id = :id");
        $stmt->execute([':id' => $userToDelete]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_success'] = "User deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "User not found or could not be deleted.";
        }
    } catch (PDOException $e) {
        $_SESSION['flash_error'] = "Error: " . $e->getMessage();
    }

    // Redirect so refresh doesn't resubmit the delete
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// -------------------
// Retrieve and clear flash messages
// -------------------
$deleteSuccess = $_SESSION['flash_success'] ?? "";
$deleteError   = $_SESSION['flash_error'] ?? "";

unset($_SESSION['flash_success'], $_SESSION['flash_error']);


ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">User Management</h4>
    <a href="create-account.php" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add New User
    </a>
</div>

<!-- Delete alert messages -->
<?php if ($deleteSuccess): ?>
  <div class="alert alert-success"><?= $deleteSuccess ?></div>
<?php elseif ($deleteError): ?>
  <div class="alert alert-danger"><?= $deleteError ?></div>
<?php endif; ?>

<!-- User Table -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;    
                        while ($row = $users->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['user_role']); ?></td>
                        <td><?= ucfirst($row['status']); ?></td>
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm px-2 py-1 text-decoration-none">Edit</a>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-user-id="<?= $row['id'] ?>" 
                                    data-user-name="<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reusable Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong id="deleteUserName"></strong>?
        </div>
        <div class="modal-footer">
          <input type="hidden" name="delete_user_id" id="deleteUserId">
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-outline-primary px-2 py-1" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Roles & Permissions -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-user-shield text-primary me-2"></i> Roles
            </div>
            <div class="card-body">
                <p class="text-muted">Manage user roles (Admin, Staff, Customer)...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-cog me-1"></i> Manage Roles
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <i class="fas fa-lock text-danger me-2"></i> Permissions
            </div>
            <div class="card-body">
                <p class="text-muted">Set permissions for different user roles...</p>
                <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit Permissions
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JS toggles delete modal -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget; 
    const userId = button.getAttribute('data-user-id');
    const userName = button.getAttribute('data-user-name');

    // Update modal content
    deleteModal.querySelector('#deleteUserId').value = userId;
    deleteModal.querySelector('#deleteUserName').textContent = userName;
  });
});
</script>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>