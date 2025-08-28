<?php
session_start(); // Must be at the top

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Upcoming Tours";
$pageDescription = "Stay updated with EpikiTours’ upcoming adventures.";
$pageSlug = "upcoming-tours";
$bannerImage = "images/epiki-tours-mountain-top.jpg";

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <!-- Intro -->
    <div class="text-center mb-5">
        <p class="text-muted">
            Don’t miss out on our special upcoming tours — designed for the adventurous, the curious, and the explorers
            at heart.
        </p>
    </div>

    <!-- Tours Grid -->
    <div class="row g-4">
        <!-- Great Migration Safari -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-elephants.jpg" alt="Wildebeest migration safari" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Great Migration Safari</h5>
                    <p class="text-muted small">July – September 2025</p>
                    <p class="card-text">Experience the breathtaking wildebeest migration across the Mara River.</p>
                    <a href="<?= BASE_URL ?>tours/great-migration-safari" class="btn btn-primary mt-auto">Reserve
                        Spot</a>
                </div>
            </div>
        </div>

        <!-- Coastal Easter Holiday Escape -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-coast-image.jpg" alt="Easter holiday at Diani Beach" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Easter Coast Escape</h5>
                    <p class="text-muted small">April 2025</p>
                    <p class="card-text">Celebrate Easter at the coast with beach bonfires, seafood, and ocean
                        adventures.</p>
                    <a href="<?= BASE_URL ?>tours/easter-coast-escape" class="btn btn-primary mt-auto">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Mount Kenya New Year Trek -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-mountain-top.jpg" alt="New Year trek to Mount Kenya" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Mt. Kenya New Year Trek</h5>
                    <p class="text-muted small">Dec 30, 2025 – Jan 2, 2026</p>
                    <p class="card-text">Welcome the New Year at Africa’s second-highest peak with sunrise views.</p>
                    <a href="<?= BASE_URL ?>tours/mt-kenya-newyear" class="btn btn-primary mt-auto">Join Trek</a>
                </div>
            </div>
        </div>

        <!-- Lake Naivasha Bird Festival -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-boat-in-water.jpg" alt="Lake Naivasha bird watching festival"
                    class="card-img-top" style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Lake Naivasha Bird Festival</h5>
                    <p class="text-muted small">November 2025</p>
                    <p class="card-text">Celebrate bird life with boat rides, photography, and expert-led talks.</p>
                    <a href="<?= BASE_URL ?>tours/naivasha-bird-festival" class="btn btn-primary mt-auto">Get
                        Tickets</a>
                </div>
            </div>
        </div>

        <!-- Cultural Festival -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epic-tours-historical-sites.jpg" alt="Cultural festival and heritage tour"
                    class="card-img-top" style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Cultural & Heritage Festival</h5>
                    <p class="text-muted small">August 2025</p>
                    <p class="card-text">Dance, food, music, and traditions from Kenya’s diverse communities.</p>
                    <a href="<?= BASE_URL ?>tours/cultural-festival" class="btn btn-primary mt-auto">Reserve Seat</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5">
        <a href="<?= BASE_URL ?>tours/calendar" class="btn btn-outline-primary btn-lg">View Full Tour Calendar</a>
    </div>
</div>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
