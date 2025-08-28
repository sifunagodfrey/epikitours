<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Create Account";
$pageDescription = "Register a new EpikiTours account.";
$pageSlug = "register";
$bannerImage = "images/epiki-tours-coast-image.jpg";

// -------------------
// Start Session (for errors & messages)
// -------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-3">
                <div class="card-body p-4">
                    <!-- Error Alert -->
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <!-- Success Alert -->
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center">
                            <?= htmlspecialchars($_SESSION['success']); ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form action="helpers/process-register.php" method="POST">
                        <div class="row">
                            <!-- First Name -->
                            <div class="mb-3 col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="John" required>
                            </div>

                            <!-- Last Name -->
                            <div class="mb-3 col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Doe" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="you@example.com" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+254700000000"
                                required>
                        </div>

                        <!-- Password with Eye Icon -->
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Choose a strong password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <hr class="my-4">

                    <!-- Login Redirect -->
                    <p class="text-center mb-0">
                        Already have an account?
                        <a href="login" class="text-primary text-decoration-none">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Password Script -->
<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const icon = this.querySelector("i");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
</script>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>