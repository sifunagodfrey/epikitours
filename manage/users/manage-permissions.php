<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';
session_start();

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Edit User Permissions";
$metaDescription = "Manage user permissions from the admin panel.";

// -------------------
// Fetch all permissions with roles 
// -------------------
$sql = "
    SELECT 
        p.id,
        p.permission,
        p.created_at,
        p.updated_at,
        GROUP_CONCAT(r.role ORDER BY r.role SEPARATOR ', ') AS roles
    FROM epi_permissions p
    LEFT JOIN epi_role_permissions pr ON p.id = pr.permission_id
    LEFT JOIN epi_roles r ON pr.role_id = r.id
    GROUP BY p.id, p.permission, p.created_at, p.updated_at
    ORDER BY p.created_at DESC
";

$permissions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Handle Delete Request
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_permission_id'])) {
    $deleteId = (int) $_POST['delete_permission_id'];

    try {
        $pdo->beginTransaction();

        // Delete role-permission mappings
        $stmt = $pdo->prepare("DELETE FROM epi_role_permissions WHERE permission_id = ?");
        $stmt->execute([$deleteId]);

        // Delete permission itself
        $stmt = $pdo->prepare("DELETE FROM epi_permissions WHERE id = ?");
        $stmt->execute([$deleteId]);

        $pdo->commit();
        $_SESSION['flash_success'] = "Permission deleted successfully.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash_error'] = "Error deleting permission: " . $e->getMessage();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit User Permissions</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manage Permissions</h4>
    <a href="create-permission" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add New Permission
    </a>
</div>

<!-- Permissions table --> 
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <!-- Delete alert messages -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
              <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
              <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash_error'])): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
              <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Permission</th>
                        <th>Access Role(s)</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($permissions as $perm): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= ucfirst(htmlspecialchars($perm['permission'])); ?></td>
                        <td>
                            <?php if (!empty($perm['roles'])): ?>
                                <?php foreach (explode(',', $perm['roles']) as $role): ?>
                                    <?= ucfirst(trim($role)) ?><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <em>No roles</em>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($perm['created_at']); ?></td>
                        <td><?= htmlspecialchars($perm['updated_at']); ?></td>
                        <td>
                            <a href="edit-permission.php?id=<?= $perm['id'] ?>" 
                               class="btn btn-outline-primary btn-sm px-2 py-1 text-decoration-none">Edit</a>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-permission-id="<?= $perm['id'] ?>" 
                                    data-permission-name="<?= htmlspecialchars($perm['permission']) ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
        <div class="modal-body">
          Are you sure you want to delete permission: <strong id="deletePermissionName"></strong>?
        </div>
        <div class="modal-footer">
          <input type="hidden" name="delete_permission_id" id="deletePermissionId">
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-outline-primary px-2 py-1" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete modal Pop-up script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var permId = button.getAttribute('data-permission-id');
        var permName = button.getAttribute('data-permission-name');

        document.getElementById('deletePermissionId').value = permId;
        document.getElementById('deletePermissionName').textContent = permName;
    });
});
</script>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>