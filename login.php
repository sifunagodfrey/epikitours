<?php
// -------------------
// login.php
// -------------------

// Page Metadata
$pageTitle = "Login";
$pageDescription = "Login to your EpikiTours account.";
$pageSlug = "login";
$bannerImage = "images/epiki-tours-mountain-top.jpg";

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location: visitors/my-account");
    exit;
}

require_once __DIR__ . '/config/database.php';

// -------------------
// Handle Login Logic
// -------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || $password === '') {
        $_SESSION['error'] = "Please enter a valid email and password.";
        header("Location: login.php");
        exit;
    }

    try {
        // Fetch user
        $stmt = $pdo->prepare("
            SELECT id, first_name, last_name, email, password, user_role
            FROM epi_users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit;
        }

        // Store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['user_role'] ?? 'visitor';

        // Update last login
        $pdo->prepare("UPDATE epi_users SET last_login = NOW() WHERE id = :id")
            ->execute(['id' => $user['id']]);

        // Log activity
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $pdo->prepare("
            INSERT INTO epi_activity_logs (user_id, action, ip_address, created_at)
            VALUES (:uid, 'User Logged In', :ip, NOW())
        ")->execute(['uid' => $user['id'], 'ip' => $ip]);

        // Redirect to visitor dashboard
        header("Location: visitors/my-account");
        exit;

    } catch (Throwable $e) {
        error_log('Login error: ' . $e->getMessage());
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: login.php");
        exit;
    }
}

// -------------------
// Output Buffer
// -------------------
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-3">
                <div class="card-body p-4">
                    <h4 class="text-center text-primary mb-4">Welcome Back</h4>

                    <!-- Error Alert -->
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" action="login.php">
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="you@example.com" required>
                        </div>

                        <!-- Password with Eye Toggle -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                                onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Register Redirect -->
                    <p class="text-center mb-0">
                        Don’t have an account?
                        <a href="create-account.php" class="text-primary text-decoration-none">Create one</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");
        if (pwd.type === "password") {
            pwd.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            pwd.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<?php
// -------------------
// End Buffer & Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>