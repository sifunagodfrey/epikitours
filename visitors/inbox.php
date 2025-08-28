<?php
// -------------------
// Page variables
// -------------------
$pageTitle = "Inbox";
$pageDescription = "Check your messages and notifications from EpikiTours.";
$pageSlug = "visitors/inbox";

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
// Fetch inbox messages
// -------------------
$userId = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.subject, m.message, m.is_read, m.created_at,
               u.name AS sender_name, u.email AS sender_email
        FROM epi_messages m
        JOIN epi_users u ON m.sender_id = u.id
        WHERE m.receiver_id = :uid
        ORDER BY m.created_at DESC
    ");
    $stmt->execute(['uid' => $userId]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Inbox Fetch Error: " . $e->getMessage());
    $messages = [];
}

// -------------------
// Start output buffering
// -------------------
ob_start();
?>

<div class="text-center mb-5">
    <h4 class="mb-3">My Inbox</h4>
    <p class="text-muted">Here you’ll find messages from EpikiTours, tour guides, and support.</p>
</div>

<?php if (empty($messages)): ?>
    <div class="alert alert-info text-center">No messages yet. Stay tuned for updates and communication.</div>
<?php else: ?>
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $index => $msg): ?>
                    <tr class="<?= $msg['is_read'] ? '' : 'fw-bold'; ?>">
                        <td><?= $index + 1; ?></td>
                        <td>
                            <?= htmlspecialchars($msg['sender_name']); ?><br>
                            <small class="text-muted"><?= htmlspecialchars($msg['sender_email']); ?></small>
                        </td>
                        <td><?= htmlspecialchars($msg['subject']); ?></td>
                        <td><?= date("d M Y H:i", strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if ($msg['is_read']): ?>
                                <span class="badge bg-secondary">Read</span>
                            <?php else: ?>
                                <span class="badge bg-success">New</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view-message.php?id=<?= $msg['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$pageContent = ob_get_clean();
include 'layouts/visitors-dashboard-layout.php';
?>