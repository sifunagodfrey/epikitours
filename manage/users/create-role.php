<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Create User Roles";
$metaDescription = "Create user roles from the admin panel.";

// -------------------
// Fetch all permissions
// -------------------
$permissions = $pdo->query("SELECT * FROM epi_permissions ORDER BY permission ASC")->fetchAll(PDO::FETCH_ASSOC);

// -------------------
// Create role
// -------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['role'])) {
    $role = trim($_POST['role']);
    $selectedPermissions = $_POST['permissions'] ?? [];

    if (!empty($role)) {
        try {
            $pdo->beginTransaction();

            // Insert role
            $stmt = $pdo->prepare("INSERT INTO epi_roles (role, created_at) VALUES (:role, NOW())");
            $stmt->execute([':role' => $role]);
            $roleId = $pdo->lastInsertId();

            // Insert role-permission mappings if any
            if (!empty($selectedPermissions)) {
                $stmtMap = $pdo->prepare("INSERT INTO epi_role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
                foreach ($selectedPermissions as $permId) {
                    $stmtMap->execute([
                        ':role_id' => $roleId,
                        ':permission_id' => $permId
                    ]);
                }
            }

            $pdo->commit();
            $updateSuccess = "Role created successfully" . (!empty($selectedPermissions) ? " with permissions." : ".");
        } catch (PDOException $e) {
            $pdo->rollBack();
            $updateError = "Error inserting role: " . $e->getMessage();
        }
    } else {
        $updateError = "Role field cannot be empty.";
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
        <h4 class="mb-0">Create Role</h4>

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
                            <input type="text" name="role" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Permissions</label>
                            <?php foreach ($permissions as $perm): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" id="perm<?= $perm['id'] ?>">
                                    <label class="form-check-label" for="perm<?= $perm['id'] ?>">
                                        <?= htmlspecialchars($perm['permission']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                 
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Create Role</button>
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