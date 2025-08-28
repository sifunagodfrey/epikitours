<?php
require_once 'config/database.php';

// ---------------------------
// Initialize Flags & Messages
// ---------------------------
$showSuccessModal = false;
$showAlreadySubscribedModal = false;
$errorMessage = '';

// ---------------------------
// Newsletter Form Handling
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_submit'])) {
    // Sanitize & validate email
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    // Default subscription preferences
    $receive_promotions = 1;
    $receive_suggestions = 0;
    $receive_order_updates = 0;
    $receive_news_updates = 1;

    if ($email) {
        try {
            // Check if already subscribed
            $check = $pdo->prepare("SELECT id FROM epiki_newsletter_subscribers WHERE email = ?");
            $check->execute([$email]);

            if ($check->rowCount() > 0) {
                $showAlreadySubscribedModal = true;
            } else {
                // Insert new subscriber
                $insert = $pdo->prepare("
                    INSERT INTO epiki_newsletter_subscribers (
                        email, is_verified,
                        receive_promotions,
                        receive_suggestions,
                        receive_order_updates,
                        receive_news_updates
                    ) VALUES (?, 0, ?, ?, ?, ?)
                ");
                $insert->execute([
                    $email,
                    $receive_promotions,
                    $receive_suggestions,
                    $receive_order_updates,
                    $receive_news_updates
                ]);

                $showSuccessModal = true;
            }
        } catch (PDOException $e) {
            $errorMessage = "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $errorMessage = "Please enter a valid email address.";
    }
}
?>

<!-- Newsletter Signup Section -->
<div class="container my-5 bg-white rounded shadow-sm p-4">
    <div class="row align-items-center">
        <!-- Text Content -->
        <div class="col-md-6 mb-4 mb-md-0">
            <h4 class="text-primary">Stay Updated with Epiki Tours</h4>
            <p class="text-muted mb-0">
                Subscribe to our newsletter and be the first to know about new destinations, exclusive tour deals, and
                inspiring travel stories.
            </p>
        </div>

        <!-- Signup Form -->
        <div class="col-md-6">
            <form class="d-flex flex-column flex-md-row gap-2" method="POST" action="">
                <input type="hidden" name="newsletter_submit" value="1">

                <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email"
                    required>

                <button type="submit" class="btn btn-primary btn-lg">
                    Subscribe
                </button>
            </form>

            <!-- Inline Error Message -->
            <?php if (!empty($errorMessage)): ?>
                <div class="mt-3 alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="thankYouModalLabel">Subscription Successful</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Thank you for subscribing to <strong>Epiki Tours</strong>! <br>
                    Get ready for exciting travel updates and exclusive offers in your inbox.</p>
            </div>
        </div>
    </div>
</div>

<!-- Already Subscribed Modal -->
<div class="modal fade" id="alreadySubscribedModal" tabindex="-1" aria-labelledby="alreadySubscribedModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="alreadySubscribedModalLabel">You're Already Subscribed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>This email is already part of our <strong>Epiki Tours</strong> family. Stay tuned for more updates!
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>

<!-- Trigger Modals -->
<?php if ($showSuccessModal): ?>
    <script>
        new bootstrap.Modal(document.getElementById('thankYouModal')).show();
    </script>
<?php elseif ($showAlreadySubscribedModal): ?>
    <script>
        new bootstrap.Modal(document.getElementById('alreadySubscribedModal')).show();
    </script>
<?php endif; ?>

<script>
    // Prevent form resubmission on reload
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>