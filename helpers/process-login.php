<?php
// -------------------
// helpers/process-login.php
// -------------------
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

// -------------------
// 1. Ensure POST
// -------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email and password.']);
    exit;
}

try {
    // -------------------
    // 2. Get user
    // -------------------
    $stmt = $pdo->prepare("
        SELECT id, first_name, last_name, email, password
        FROM epi_users
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        exit;
    }

    // -------------------
    // 3. Set session
    // -------------------
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
    $_SESSION['user_email'] = $user['email'];

    // -------------------
    // 4. Get user role
    // -------------------
    $roleStmt = $pdo->prepare("
        SELECT r.name AS role
        FROM epi_user_roles ur
        JOIN epi_roles r ON r.id = ur.role_id
        WHERE ur.user_id = :uid
        LIMIT 1
    ");
    $roleStmt->execute(['uid' => $user['id']]);
    $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);

    // Normalize role
    $role = strtolower(str_replace([' ', '-'], '_', $roleRow['role'] ?? 'visitor'));
    $_SESSION['user_role'] = $role;

    error_log("User {$user['email']} logged in with role: {$role}");

    // -------------------
    // 5. Update last login
    // -------------------
    $pdo->prepare("UPDATE epi_users SET last_login = NOW() WHERE id = :id")
        ->execute(['id' => $user['id']]);

    // -------------------
    // 6. Log activity
    // -------------------
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $pdo->prepare("
        INSERT INTO epi_activity_logs (user_id, action, ip_address, created_at)
        VALUES (:uid, 'User Logged In', :ip, NOW())
    ")->execute(['uid' => $user['id'], 'ip' => $ip]);

    // -------------------
    // 7. Decide redirect (absolute path from root)
    // -------------------
    switch ($role) {
        case 'admin':
            $redirect = '/admin/dashboard.php';
            break;
        case 'guide':
        case 'tour_guide':
        case 'manager':
            $redirect = '/tour-guide/dashboard.php';
            break;
        default:
            $redirect = '/visitors/my-account.php';
    }

    echo json_encode(['success' => true, 'redirect' => $redirect]);
    exit;

} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
    exit;
}
