<?php
// Start session and check login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

$userId = $_SESSION['user_id'];
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 4) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$errors = [];
$success = false;

// Sanitize input
$fullName = trim($_POST['full_name']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$country = trim($_POST['country'] ?? '');

// Handle profile image upload
$profilePhotoName = null;
if (!empty($_FILES['profile_picture']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        $profilePhotoName = 'profile_' . $userId . '_' . time() . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/profiles/';
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadDir . $profilePhotoName);
    } else {
        $errors[] = "Invalid image format. Only JPG, PNG, or WEBP allowed.";
    }
}

// Handle password update
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];
$passwordChanged = false;

if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
    // Fetch current password
    $stmt = $pdo->prepare("SELECT password FROM isk_users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        $errors[] = "Current password is incorrect.";
    } elseif (strlen($newPassword) < 6) {
        $errors[] = "New password must be at least 6 characters.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "New passwords do not match.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $passwordChanged = true;
    }
}

if (empty($errors)) {
    // Begin transaction
    $pdo->beginTransaction();
    try {
        // Update users table
        $updateUser = $pdo->prepare("UPDATE isk_users SET first_name = ?, phone = ?" . ($passwordChanged ? ", password = ?" : "") . " WHERE id = ?");
        $params = [$fullName, $phone];
        if ($passwordChanged) {
            $params[] = $hashedPassword;
        }
        $params[] = $userId;
        $updateUser->execute($params);

        // Update or insert user_profiles
        $checkProfile = $pdo->prepare("SELECT id FROM isk_user_profiles WHERE user_id = ?");
        $checkProfile->execute([$userId]);

        if ($checkProfile->rowCount() > 0) {
            // Update
            $query = "UPDATE isk_user_profiles SET address = ?, city = ?, country = ?";
            $params = [$address, $city, $country];
            if ($profilePhotoName) {
                $query .= ", profile_photo = ?";
                $params[] = $profilePhotoName;
            }
            $query .= " WHERE user_id = ?";
            $params[] = $userId;

            $updateProfile = $pdo->prepare($query);
            $updateProfile->execute($params);
        } else {
            // Insert
            $insertProfile = $pdo->prepare("INSERT INTO isk_user_profiles (user_id, address, city, country, profile_photo) VALUES (?, ?, ?, ?, ?)");
            $insertProfile->execute([$userId, $address, $city, $country, $profilePhotoName]);
        }

        $pdo->commit();
        $_SESSION['success_message'] = "Profile updated successfully.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $errors[] = "Failed to update profile. Please try again.";
    }
} else {
    $_SESSION['error_message'] = implode("<br>", $errors);
}

header("Location: account-settings");
exit;
