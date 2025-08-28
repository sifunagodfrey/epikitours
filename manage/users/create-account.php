<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Create Account";
$metaDescription = "Create a new user account from the Epikitours Admin Panel.";

// -------------------
// Fetch ENUM options for role and status
// -------------------
function getEnumValues($pdo, $table, $column) {
    $stmt = $pdo->prepare("
        SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = :table 
        AND COLUMN_NAME = :column
    ");
    $stmt->execute([
        ':table' => $table,
        ':column' => $column
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        preg_match("/^enum\((.*)\)$/", $row['COLUMN_TYPE'], $matches);
        return str_getcsv($matches[1], ",", "'");
    }
    return [];
}

$roleOptions = getEnumValues($pdo, 'epi_users', 'user_role');
$statusOptions = getEnumValues($pdo, 'epi_users', 'status');

session_start();

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create User</li>
    </ol>
</nav>

<!-- Display alert messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="d-flex">
    <div class="container-fluid">
        <h4 class="mb-0">New Account</h4>
        
        <div class="card mt-4">
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>helpers/process-admin-user-creation.php">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>                    
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="user_role" class="form-select" required>
                                <option value="" selected disabled>-- Select Role --</option>
                                <?php foreach ($roleOptions as $role): ?>
                                    <option value="<?= htmlspecialchars($role) ?>">
                                        <?= ucfirst($role) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="" selected disabled>-- Select Status --</option>
                                <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>">
                                        <?= ucfirst($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>                    
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" value="" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirmation_password" class="form-control" required>
                        <div class="invalid-feedback">Passwords must match.</div>
                    </div>
                    
                    <!-- Password match confirmation -->
                    <script>
                        const pwd = document.querySelector("[name='password']");
                        const confirmPwd = document.querySelector("[name='confirmation_password']");

                        confirmPwd.addEventListener("input", () => {
                            if (confirmPwd.value !== pwd.value) {
                                confirmPwd.setCustomValidity("Passwords must match");
                            } else {
                                confirmPwd.setCustomValidity("");
                            }
                        });
                    </script>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Create Account</button>
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