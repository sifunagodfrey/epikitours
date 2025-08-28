<?php
// -------------------
// helpers/process-register.php
// Handles user registration, assigns default role "visitor", and logs activity
// -------------------

session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    // -------------------
    // Validation
    // -------------------
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../create-account.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: ../create-account.php");
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
            header("Location: ../create-account.php");
            exit;
        }

        // -------------------
        // Insert into epi_users
        // -------------------
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO epi_users (first_name, last_name, email, password, phone, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $phone]);

        $userId = $pdo->lastInsertId();

        // -------------------
        // Assign default role (visitor)
        // -------------------
        $roleStmt = $pdo->prepare("SELECT id FROM epi_roles WHERE name = 'visitor' LIMIT 1");
        $roleStmt->execute();
        $role = $roleStmt->fetch(PDO::FETCH_ASSOC);

        if ($role) {
            $roleId = $role['id'];
            $stmt = $pdo->prepare("INSERT INTO epi_user_roles (user_id, role_id) VALUES (?, ?)");
            $stmt->execute([$userId, $roleId]);
        }

        // -------------------
        // Log registration activity
        // -------------------
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $logStmt = $pdo->prepare("
            INSERT INTO epi_activity_logs (user_id, action, ip_address, created_at) 
            VALUES (?, 'User Registered', ?, NOW())
        ");
        $logStmt->execute([$userId, $ipAddress]);

        $_SESSION['success'] = "Account created successfully. You can now login.";
        header("Location: ../login.php");
        exit;

    } catch (PDOException $e) {
        error_log("Registration Error: " . $e->getMessage());
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: ../create-account.php");
        exit;
    }
} else {
    header("Location: ../create-account.php");
    exit;
}
