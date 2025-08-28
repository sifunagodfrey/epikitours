<?php
require_once __DIR__ . '/../routes.php';
require_once __DIR__ . '/../../config/database.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Edit User";
$metaDescription = "Edit user's profile details; role, status, phone and password.";

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

// -------------------
// Fetch user account details by id
// -------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$userId = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM epi_users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// -------------------
// Update user account details
// -------------------
$updateSuccess = $updateError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $role = trim($_POST['user_role'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');

    try {
        if ($newPassword !== "") {
            // Hash new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                UPDATE epi_users 
                SET email = :email, phone = :phone, user_role = :role, status = :status, password = :password 
                WHERE id = :id
            ");
            $stmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':role' => $role,
                ':status' => $status,
                ':password' => $hashedPassword,
                ':id' => $userId
            ]);
        } else {
            // Update without password
            $stmt = $pdo->prepare("
                UPDATE epi_users 
                SET email = :email, phone = :phone, user_role = :role, status = :status
                WHERE id = :id
            ");
            $stmt->execute([
                ':email' => $email,
                ':phone' => $phone,
                ':role' => $role,
                ':status' => $status,
                ':id' => $userId
            ]);
        }

        if ($stmt->rowCount() > 0) {
            $updateSuccess = "User updated successfully.";
            // Refresh $user with new data
            $stmt = $pdo->prepare("SELECT * FROM epi_users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $updateError = "No changes made.";
        }
    } catch (PDOException $e) {
        $updateError = "Error updating user: " . $e->getMessage();
    }
}

ob_start();
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light p-2 rounded mb-3">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>dashboard">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
    </ol>
</nav>

<div class="d-flex">
    <div class="container-fluid">
        <h4 class="mb-0">Account Details</h4>

        <!-- Update alert messages -->
        <?php if ($updateSuccess): ?>
          <div class="alert alert-success mt-2"><?= $updateSuccess ?></div>
        <?php elseif ($updateError): ?>
          <div class="alert alert-danger mt-2"><?= $updateError ?></div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']); ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>">
                        </div>                    
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="user_role" class="form-select">
                                <?php foreach ($roleOptions as $role): ?>
                                    <option value="<?= htmlspecialchars($role) ?>" 
                                        <?= $role === $user['user_role'] ? 'selected' : '' ?>>
                                        <?= ucfirst($role) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <?php foreach ($statusOptions as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>" 
                                        <?= $status === $user['status'] ? 'selected' : '' ?>>
                                        <?= ucfirst($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>                    
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="new_password" class="form-control" value="" autocomplete="off">
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Update Account</button>
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
