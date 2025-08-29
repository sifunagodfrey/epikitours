<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Create User Permissions";
$metaDescription = "Create user permissions from the admin panel.";

// -------------------
// Fetch all roles
// -------------------
$roles = $pdo->query("SELECT * FROM epi_roles ORDER BY role ASC")->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Create permission
// -------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['permission'])) {
    $permission = trim($_POST['permission']);
    $selectedRoles = $_POST['roles'] ?? [];

    if (!empty($permission)) {
        try {
            $pdo->beginTransaction();

            // Insert permission
            $stmt = $pdo->prepare("INSERT INTO epi_permissions (permission, created_at) VALUES (:permission, NOW())");
            $stmt->execute([':permission' => $permission]);
            $permissionId = $pdo->lastInsertId();

            // Insert role-permission mappings if any roles selected
            if (!empty($selectedRoles)) {
                $stmtMap = $pdo->prepare("INSERT INTO epi_role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
                foreach ($selectedRoles as $roleId) {
                    $stmtMap->execute([
                        ':role_id' => $roleId,
                        ':permission_id' => $permissionId
                    ]);
                }
            }

            $pdo->commit();
            $updateSuccess = "Permission created successfully" . (!empty($selectedRoles) ? " with roles." : ".");
        } catch (PDOException $e) {
            $pdo->rollBack();
            $updateError = "Error inserting permission: " . $e->getMessage();
        }
    } else {
        $updateError = "Permission field cannot be empty.";
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add New Role</li>
    </ol>
</nav>

<!-- Create role form -->
<div class="d-flex">
    <div class="container-fluid">
        <h4 class="mb-0">Create Permission</h4>

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
                            <label class="form-label">Permission</label>
                            <input type="text" name="permission" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role(s)</label>
                            <?php foreach ($roles as $role): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role<?= $role['id'] ?>">
                                    <label class="form-check-label" for="role<?= $role['id'] ?>">
                                        <?= htmlspecialchars($role['role']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                 
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Create Permission</button>
                        <a href="index.php" class="btn btn-secondary text-decoration-none">Back to User Management</a>
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