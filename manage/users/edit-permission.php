<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Update User Permissions";
$metaDescription = "Edit user permissions from the admin panel.";

// -------------------
// Get permission ID from query
// -------------------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Permission ID is required.");
}
$permissionId = (int) $_GET['id'];

// -------------------
// Fetch permission
// -------------------
$stmt = $pdo->prepare("SELECT * FROM epi_permissions WHERE id = ?");
$stmt->execute([$permissionId]);
$permission = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$permission) {
    die("Permission not found.");
}

// -------------------
// Fetch all roles
// -------------------
$roles = $pdo->query("SELECT * FROM epi_roles ORDER BY role ASC")->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Fetch current permission roles (ensure array)
// -------------------
$stmt = $pdo->prepare("SELECT role_id FROM epi_role_permissions WHERE permission_id = ?");
$stmt->execute([$permissionId]);
$currentRoles = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!is_array($currentRoles)) {
    $currentRoles = [];
}

// -------------------
// Handle update form
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedRoles = isset($_POST['roles']) ? $_POST['roles'] : [];
    // sanitize to ints
    $selectedRoles = array_map('intval', $selectedRoles);

    try {
        $pdo->beginTransaction();

        // Clear old roles
        $stmt = $pdo->prepare("DELETE FROM epi_role_permissions WHERE permission_id = ?");
        $stmt->execute([$permissionId]);

        // Insert new roles (permission_id, role_id)
        if (!empty($selectedRoles)) {
            $stmt = $pdo->prepare("INSERT INTO epi_role_permissions (permission_id, role_id) VALUES (?, ?)");
            foreach ($selectedRoles as $roleId) {
                $stmt->execute([$permissionId, $roleId]);
            }
        }

        $pdo->commit();
        $updateSuccess = "Permission updated successfully!";

        // Refresh current roles so checkboxes reflect changes immediately
        $stmt = $pdo->prepare("SELECT role_id FROM epi_role_permissions WHERE permission_id = ?");
        $stmt->execute([$permissionId]);
        $currentRoles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!is_array($currentRoles)) {
            $currentRoles = [];
        }

    } catch (PDOException $e) {
        $pdo->rollBack();
        $updateError = "Error updating permissions: " . $e->getMessage();
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Permission</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Permission</h4>
    <a href="manage-permissions" class="btn btn-secondary">Back to Permissions</a>
</div>

<!-- Display alert messages -->
<?php if (!empty($updateSuccess)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($updateSuccess) ?></div>
<?php endif; ?>
<?php if (!empty($updateError)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($updateError) ?></div>
<?php endif; ?>

<div class="card mt-4">
    <div class="card-body">
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Permission</label>
                    <input type="text" name="permission" class="form-control" value="<?= htmlspecialchars(ucfirst($permission['permission'])) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Roles</label>
                    <?php foreach ($roles as $role): ?>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="roles[]" 
                                value="<?= (int)$role['id'] ?>" 
                                id="role<?= (int)$role['id'] ?>"
                                <?= in_array($role['id'], $currentRoles, true) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="role<?= (int)$role['id'] ?>">
                                <?= htmlspecialchars($role['role']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
         
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">Update Permission</button>
                <a href="manage-permissions" class="btn btn-secondary text-decoration-none">Back to Permissions</a>
            </div>
        </form>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>
