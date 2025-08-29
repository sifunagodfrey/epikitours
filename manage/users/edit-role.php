<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Update User Roles";
$metaDescription = "Edit user roles from the admin panel.";

// -------------------
// Get role ID from query
// -------------------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Role ID is required.");
}
$roleId = (int) $_GET['id'];

// -------------------
// Fetch role
// -------------------
$stmt = $pdo->prepare("SELECT * FROM epi_roles WHERE id = ?");
$stmt->execute([$roleId]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    die("Role not found.");
}

// -------------------
// Fetch all permissions
// -------------------
$permissions = $pdo->query("SELECT * FROM epi_permissions ORDER BY permission ASC")->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Fetch current role permissions
// -------------------
$stmt = $pdo->prepare("SELECT permission_id FROM epi_role_permissions WHERE role_id = ?");
$stmt->execute([$roleId]);
$currentPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

// -------------------
// Handle update form
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedPermissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

    try {
        $pdo->beginTransaction();

        // 1. Clear old permissions
        $stmt = $pdo->prepare("DELETE FROM epi_role_permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);

        // 2. Insert new permissions
        if (!empty($selectedPermissions)) {
            $stmt = $pdo->prepare("INSERT INTO epi_role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($selectedPermissions as $permId) {
                $stmt->execute([$roleId, $permId]);
            }
        }

        $pdo->commit();
        $updateSuccess = "Role updated successfully!";
        
        // Refresh current permissions so checkboxes reflect changes immediately
        $stmt = $pdo->prepare("SELECT permission_id FROM epi_role_permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);
        $currentPermissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } catch (PDOException $e) {
        $pdo->rollBack();
        $updateError = "Error updating role: " . $e->getMessage();
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Role</li>
    </ol>
</nav>

<!-- Create role form -->
<div class="d-flex">
    <div class="container-fluid">
        <h4 class="mb-0">Edit Role</h4>

        <!-- Display alert messages -->
        <?php if (!empty($updateSuccess)): ?>
            <div class="alert alert-success mt-2"><?= htmlspecialchars($updateSuccess) ?></div>
        <?php endif; ?>
        <?php if (!empty($updateError)): ?>
            <div class="alert alert-danger mt-2"><?= htmlspecialchars($updateError) ?></div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" name="role" class="form-control" value="<?= ucfirst($role['role']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Permissions</label>
                            <?php foreach ($permissions as $perm): ?>
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permissions[]" 
                                        value="<?= $perm['id'] ?>" 
                                        id="perm<?= $perm['id'] ?>"
                                        <?= in_array($perm['id'], $currentPermissions) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="perm<?= $perm['id'] ?>">
                                        <?= htmlspecialchars($perm['permission']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                 
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Update Role</button>
                        <a href="manage-roles" class="btn btn-secondary text-decoration-none">Back to Roles</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
include BASE_PATH . 'layouts/admin-layout.php';
?>