<?php
// -------------------
// manage/login.php
// -------------------

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header("Location: index.php");
    exit;
}

// Database connection
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user from epi_users
    $stmt = $pdo->prepare("SELECT * FROM epi_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['user_role'] !== 'admin') {
            $error = "Access denied. Admins only.";
        } else {
            // Save session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['user_role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            // Redirect to dashboard
            header("Location: index");
            exit;
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EpikiTours Admin Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <link rel="icon" href="../images/epiki-tours-icon.png" type="image/png" />
    <style>
        body {
            background: url('../images/epiki-tours-mountain-top.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .bg-blur {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 bg-blur">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-white">
                            <div class="text-center p-4">
                                <img src="../images/epiki-tours-logo.png" alt="EpikiTours Logo" class="img-fluid mb-3"
                                    style="max-height: 50px;">
                                <h4 class="fw-bold">Welcome to EpikiTours</h4>
                                <p class="text-muted">Admin access panel for managing tours & bookings</p>
                            </div>
                        </div>

                        <div class="col-md-6 p-4">
                            <h5 class="text-center mb-4">Admin Login</h5>
                            <hr>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address *</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="admin@epikitours.com" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>

                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="forgot-password.php" class="text-primary text-decoration-none">Forgot
                                        Password?</a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>