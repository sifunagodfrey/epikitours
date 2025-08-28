<?php
// -------------------
// manage/helpers/process-admin-user.php
// Handles admin-created user accounts: create with specified role/status, delete users
// -------------------

session_start();
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // -------------------
    // Create user
    // -------------------
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $email     = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone     = trim($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';
    $userRole  = $_POST['user_role'] ?? 'visitor';
    $status    = $_POST['status'] ?? 'active';

    $redirectBase = '../users/create-account.php';

    // -------------------
    // Validation
    // -------------------
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: {$redirectBase}");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: {$redirectBase}");
        exit;
    }

    try {
        // -------------------
        // Check if email exists
        // -------------------
        $stmt = $pdo->prepare("SELECT id FROM epi_users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: {$redirectBase}");
            exit;
        }

        // -------------------
        // Insert into epi_users
        // -------------------
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO epi_users (first_name, last_name, email, password, phone, user_role, status, created_at)
            VALUES (:first_name, :last_name, :email, :password, :phone, :user_role, :status, NOW())
        ");
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name'  => $lastName,
            ':email'      => $email,
            ':password'   => $hashedPassword,
            ':phone'      => $phone,
            ':user_role'  => $userRole,
            ':status'     => $status
        ]);

        $userId = $pdo->lastInsertId();

        // -------------------
        // Log admin creation activity
        // -------------------
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $logStmt = $pdo->prepare("
            INSERT INTO epi_activity_logs (user_id, action, ip_address, created_at)
            VALUES (?, 'Admin Created User', ?, NOW())
        ");
        $logStmt->execute([$userId, $ipAddress]);

        $_SESSION['success'] = "User created successfully.";
        header("Location: {$redirectBase}");
        exit;

    } catch (PDOException $e) {
        error_log("Admin Create User Error: " . $e->getMessage());
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: {$redirectBase}");
        exit;
    }

} else {
    header("Location: ../users/create-account.php");
    exit;
}
