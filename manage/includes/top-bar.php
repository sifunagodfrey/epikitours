<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../../includes/website_visitor_tracking.php';
?>
<!-- Top Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-3 py-2">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center w-100">

      <!-- Sidebar Toggle (Visible on small screens only) -->
      <button class="btn d-lg-none me-2" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars fa-lg"></i>
      </button>

      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center fw-bold" href="<?= BASE_URL ?>dashboard">
        <img src="<?= BASE_URL ?>images/epiki-tours-logo.png" alt="Epikitours logo" height="40" class="me-2">
      </a>

      <!-- Right Side -->
      <div class="d-flex align-items-center gap-3">

        <?php
        require_once __DIR__ . '/../../config/database.php';

        // Logged in user ID
        $userId = $_SESSION['user_id'] ?? 0;

        // -------------------
        // TEMP: disable system logs query since `isk_system_logs` table does not exist
        // Replace this with your actual notifications logic later
        // -------------------
        $unreadCount = 0;
        ?>

        <!-- Notifications -->
        <a href="<?= BASE_URL ?>system-notifications?mark_read=1"
          class="text-decoration-none position-relative text-primary d-none d-lg-inline-block">
          <i class="fas fa-bell fa-lg"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $unreadCount ?>
            <span class="visually-hidden">unread notifications</span>
          </span>
        </a>
        <!-- View Website Button -->
        <a href="https://epikitours.com" target="_blank" class="btn d-none d-lg-inline-block">
          <i class="fas fa-eye me-1 text-primary"></i> Visit Site
        </a>

        <!-- User Dropdown -->
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-primary"
            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle fa-lg me-1 text-primary"></i>
            <span class="d-none d-sm-inline text-dark">
              Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>my-profile"><i class="fas fa-user me-2"></i> Profile</a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout"><i
                  class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</nav>