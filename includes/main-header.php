<?php
// -------------------
// Required Includes (keep these as they handle routing & tracking)
// -------------------
require_once __DIR__ . '/../routes.php';
?>

<!-- Top Bar -->
<div class="top-bar-gradient text-white py-2">
  <div
    class="container d-flex flex-column flex-md-row justify-content-md-between align-items-center text-center text-md-start">

    <!-- Left: Contact Email -->
    <div class="d-flex flex-row align-items-center mb-2 mb-md-0">
      <div class="me-3">
        <i class="fas fa-envelope me-1"></i>
        <a href="mailto:support@epikitours.com" class="text-white text-decoration-none">
          support@epikitours.com
        </a>
      </div>
    </div>

    <!-- Center: Social Media -->
    <div class="d-none d-md-flex flex-row justify-content-center mb-2 mb-md-0">
      <a href="https://facebook.com" target="_blank" class="text-white mx-2">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://x.com" target="_blank" class="text-white mx-2"> <i class="fab fa-x-twitter"></i> </a>
      <a href="https://instagram.com" target="_blank" class="text-white mx-2">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://linkedin.com" target="_blank" class="text-white mx-2">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>


    <!-- Right: Contact Phone -->
    <div class="d-none d-md-flex align-items-center">
      <div class="me-3">
        <i class="fas fa-phone-alt me-1"></i> +254 726 790718
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS -->
<style>
  .top-bar-gradient {
    background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  }
</style>


<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm sticky-top">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand me-3" href="<?= BASE_URL ?>">
      <img src="<?= BASE_URL ?>images/epiki-tours-logo.png" alt="EpikiTours Logo" height="45">
    </a>

    <!-- Mobile Menu Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?= isActive('top-tours'); ?>" href="<?= BASE_URL ?>top-tours">Top Tours</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('upcoming-tours'); ?>" href="<?= BASE_URL ?>upcoming-tours">Upcoming Tours</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('virtual-destinations'); ?>" href="<?= BASE_URL ?>virtual-destinations">
            Destinations</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('about-us'); ?>" href="<?= BASE_URL ?>about-us">About Us</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= isActive('contact-us'); ?>" href="<?= BASE_URL ?>contact-us">Contact Us</a>
        </li>

        <?php if (isset($_SESSION['user_name'])): ?>
          <!-- Dropdown for Logged In Users -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="fas fa-user-circle me-1 text-primary"></i> Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="<?= BASE_URL ?>visitors/my-account">My Account</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>visitors/my-bookings">My Bookings</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>visitors/logout">Log Out</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item ms-2">
            <a href="<?= BASE_URL ?>login" class="btn btn-secondary btn-sm">
              <i class="fas fa-user-circle me-1"></i> Join Us
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>