<?php
// -------------------
// helpers/process-account-settings.php
// -------------------
session_start();
require_once __DIR__ . '/../config/database.php';

// Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../visitors/account-settings.php");
    exit;
}

// Get current user
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: ../login");
    exit;
}

// Get POST values
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Basic validation
if (empty($firstName) || empty($lastName) || !$email) {
    $_SESSION['error'] = "First name, last name, and email are required.";
    header("Location: ../visitors/account-settings.php");
    exit;
}

try {
    // -------------------
    // Update basic info
    // -------------------
    $stmt = $pdo->prepare("UPDATE epi_users SET first_name = :fname, last_name = :lname, email = :email, phone = :phone, updated_at = NOW() WHERE id = :uid");
    $stmt->execute([
        'fname' => $firstName,
        'lname' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'uid' => $userId
    ]);

    // -------------------
    // Handle password change
    // -------------------
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "All password fields are required to change password.";
            header("Location: ../visitors/account-settings.php");
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "New password and confirmation do not match.";
            header("Location: ../visitors/account-settings.php");
            exit;
        }

        // Verify current password
        $stmt = $pdo->prepare("SELECT password FROM epi_users WHERE id = :uid");
        $stmt->execute(['uid' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            header("Location: ../visitors/account-settings.php");
            exit;
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE epi_users SET password = :pwd, updated_at = NOW() WHERE id = :uid");
        $stmt->execute(['pwd' => $hashedPassword, 'uid' => $userId]);
    }

    $_SESSION['success'] = "Account updated successfully.";
    header("Location: ../visitors/account-settings.php");
    exit;

} catch (PDOException $e) {
    error_log("Account Settings Update Error: " . $e->getMessage());
    $_SESSION['error'] = "Something went wrong. Please try again.";
    header("Location: ../visitors/account-settings.php");
    exit;
}
