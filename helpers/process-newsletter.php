<?php
// -------------------
// Start session
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Protect (must be logged in)
// -------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// -------------------
// Database connection
// -------------------
require_once __DIR__ . '/../config/database.php';

$userId = $_SESSION['user_id'];
$isSubscribed = isset($_POST['subscribed']) ? 1 : 0;

try {
    // Check if user already has a subscription row
    $stmt = $pdo->prepare("SELECT id FROM epi_newsletters WHERE user_id = :uid LIMIT 1");
    $stmt->execute(['uid' => $userId]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update existing record
        $update = $pdo->prepare("UPDATE epi_newsletters SET subscribed = :subscribed, updated_at = NOW() WHERE user_id = :uid");
        $update->execute([
            'subscribed' => $isSubscribed,
            'uid' => $userId
        ]);
    } else {
        // Insert new record
        $insert = $pdo->prepare("INSERT INTO epi_newsletters (user_id, subscribed, created_at, updated_at) VALUES (:uid, :subscribed, NOW(), NOW())");
        $insert->execute([
            'uid' => $userId,
            'subscribed' => $isSubscribed
        ]);
    }

    $_SESSION['success'] = $isSubscribed ? "You have subscribed to EpikiTours newsletters!" : "You have unsubscribed from EpikiTours newsletters.";
} catch (PDOException $e) {
    error_log("Newsletter Subscription Error: " . $e->getMessage());
    $_SESSION['error'] = "Failed to update subscription. Please try again later.";
}

// -------------------
// Redirect back
// -------------------
header("Location: ../visitors/news-letter.php");
exit;
