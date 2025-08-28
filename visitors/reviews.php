<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "My Reviews";
$pageDescription = "See and manage the reviews you have submitted on EpikiTours.";
$pageSlug = "visitors/reviews";

// -------------------
// Start session
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Protect page (must be logged in)
// -------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login");
    exit;
}

// -------------------
// Database connection
// -------------------
require_once __DIR__ . '/../config/database.php';

// -------------------
// Fetch reviews for logged-in visitor
// -------------------
$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("
        SELECT r.id, r.rating, r.comment, r.created_at,
               t.title AS tour_title
        FROM epi_reviews r
        JOIN epi_tours t ON r.tour_id = t.id
        WHERE r.user_id = :uid
        ORDER BY r.created_at DESC
    ");
    $stmt->execute(['uid' => $userId]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Reviews Fetch Error: " . $e->getMessage());
    $reviews = [];
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">My Reviews</h4>
    <p class="text-muted">Here you can see all the reviews you’ve written about your EpikiTours experiences.</p>
</div>

<?php if (empty($reviews)): ?>
    <div class="alert alert-info text-center">You haven’t submitted any reviews yet. After your tours, come back and share
        your experience!</div>
<?php else: ?>
    <div class="list-group shadow-sm">
        <?php foreach ($reviews as $review): ?>
            <div class="list-group-item py-3">
                <h5 class="mb-1 text-primary"><?= htmlspecialchars($review['tour_title']); ?></h5>
                <div class="mb-2">
                    <!-- Star Rating -->
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $review['rating']): ?>
                            <i class="fas fa-star text-warning"></i>
                        <?php else: ?>
                            <i class="far fa-star text-warning"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <p class="mb-2"><?= nl2br(htmlspecialchars($review['comment'])); ?></p>
                <small class="text-muted">Reviewed on <?= date("d M Y", strtotime($review['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>s