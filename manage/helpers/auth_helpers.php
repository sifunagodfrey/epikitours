<?php
// -------------------
// helpers/auth_helpers.php
// -------------------

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

/**
 * Check if a user is logged in
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Get the logged-in user record
 */
function getAuthenticatedUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    $pdo = $GLOBALS['pdo'];
    $stmt = $pdo->prepare("SELECT * FROM epi_users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

/**
 * Check if logged-in user has a specific role
 * Example: hasRole('admin')
 */
function hasRole(string $role): bool
{
    $user = getAuthenticatedUser();
    if (!$user) {
        return false;
    }

    return $user['user_role'] === $role;
}

/**
 * Restrict page to specific roles only
 */
function requireRole(array $roles): void
{
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "visitors/login.php");
        exit;
    }

    $user = getAuthenticatedUser();
    if (!$user || !in_array($user['user_role'], $roles, true)) {
        // Optional: send to "access denied" page
        header("Location: " . BASE_URL . "visitors/access-denied.php");
        exit;
    }
}
