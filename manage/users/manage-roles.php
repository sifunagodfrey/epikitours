<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';
session_start();

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Manage User Roles";
$metaDescription = "Manage user roles from the admin panel.";

// -------------------
// Fetch all roles with permissions
// -------------------
$sql = "
    SELECT 
        r.id,
        r.role,
        r.created_at,
        r.updated_at,
        GROUP_CONCAT(p.permission ORDER BY p.permission SEPARATOR ', ') AS permissions
    FROM epi_roles r
    LEFT JOIN epi_role_permissions rp ON r.id = rp.role_id
    LEFT JOIN epi_permissions p ON rp.permission_id = p.id
    GROUP BY r.id, r.role, r.created_at, r.updated_at
    ORDER BY r.created_at DESC
";

$roles = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Handle Delete Request
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_role_id'])) {
    $deleteId = (int) $_POST['delete_role_id'];

    try {
        $pdo->beginTransaction();

        // Delete role-permission mappings first
        $stmt = $pdo->prepare("DELETE FROM epi_role_permissions WHERE role_id = ?");
        $stmt->execute([$deleteId]);

        // Delete role itself
        $stmt = $pdo->prepare("DELETE FROM epi_roles WHERE id = ?");
        $stmt->execute([$deleteId]);

        $pdo->commit();
        $_SESSION['flash_success'] = "Role deleted successfully.";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['flash_error'] = "Error deleting role: " . $e->getMessage();
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
        <li class="breadcrumb-item active" aria-current="page">Manage User Roles</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manage Roles</h4>
    <a href="create-role" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Add New Role
    </a>
</div>

<!-- Roles table --> 
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
                        <th>Role</th>
                        <th>Permission(s)</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;    
                        foreach ($roles as $role): 
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= ucfirst(htmlspecialchars($role['role'])); ?></td>
                        <td>
                            <?php if ($role['permissions']) : ?>
                                <ul>
                                    <?php foreach (explode(',', $role['permissions']) as $perm) : ?>
                                        <li><?= htmlspecialchars(trim($perm)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <em>No permissions</em>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($role['created_at']); ?></td>
                        <td><?= htmlspecialchars($role['updated_at']); ?></td>
                        <td>
                            <a href="edit-role.php?id=<?= $role['id'] ?>" 
                               class="btn btn-outline-primary btn-sm px-2 py-1 text-decoration-none">Edit</a>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-role-id="<?= $role['id'] ?>" 
                                    data-role-name="<?= htmlspecialchars($role['role']) ?>">
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
          Are you sure you want to delete role: <strong id="deleteRoleName"></strong>?
        </div>
        <div class="modal-footer">
          <input type="hidden" name="delete_role_id" id="deleteRoleId">
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
        var roleId = button.getAttribute('data-role-id');
        var roleName = button.getAttribute('data-role-name');

        document.getElementById('deleteRoleId').value = roleId;
        document.getElementById('deleteRoleName').textContent = roleName;
    });
});
</script>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>
