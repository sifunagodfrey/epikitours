<?php
session_start(); // Must be at the top

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Top Virtual Tours";
$pageDescription = "Discover EpikiTours’ most popular free virtual tours";
$pageSlug = "top-tours";
$bannerImage = "images/coastal-adventure-epikitours.webp";

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <!-- Intro -->
    <div class="text-center">
        <p class="text-muted">
            Explore Africa’s wonders without leaving your home - from safaris
            and coastal escapes to cultural journeys and mountain adventures.
        </p>
    </div>

    <!-- Tours Grid -->
    <div class="row g-4">
        <!-- Masai Mara Safari -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-elephants.jpg" alt="Masai Mara Safari with elephants" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Masai Mara Virtual Safari</h5>
                    <p class="card-text">Experience the Big Five in real time with immersive 360° wildlife footage.</p>
                    <a href="<?= BASE_URL ?>tours/masai-mara-safari" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>

        <!-- Coastal Escape -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-coast-image.jpg" alt="Diani and Mombasa Coast beaches" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Diani & Mombasa Coast</h5>
                    <p class="card-text">Relax on white-sand beaches and discover Swahili culture in a virtual journey.
                    </p>
                    <a href="<?= BASE_URL ?>tours/diani-mombasa-coast" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>

        <!-- Mountain Summit -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-mountain-top.jpg" alt="Mount Kenya summit experience" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Mt. Kenya Virtual Trek</h5>
                    <p class="card-text">Climb to the peaks of Africa’s second highest mountain — online and free.</p>
                    <a href="<?= BASE_URL ?>tours/mt-kenya-adventure" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>

        <!-- Scenic Road Trip -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-car-in-mountain.jpg" alt="Scenic mountain road trip" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Great Rift Valley</h5>
                    <p class="card-text">Enjoy panoramic views, crater lakes, and breathtaking landscapes virtually.</p>
                    <a href="<?= BASE_URL ?>tours/rift-valley-road-trip" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>

        <!-- Historical Expedition -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epic-tours-historical-sites.jpg" alt="Historical and cultural sites tour"
                    class="card-img-top" style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Historical & Cultural Sites</h5>
                    <p class="card-text">Walk through museums, heritage landmarks and ruins in guided 360° tours.</p>
                    <a href="<?= BASE_URL ?>tours/historical-expedition" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>

        <!-- Lake & Boat Adventure -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-boat-in-water.jpg" alt="Boat ride adventure on Kenyan lakes"
                    class="card-img-top" style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Lake Naivasha Boat Ride</h5>
                    <p class="card-text">Glide past hippos and birds, then explore Crescent Island virtually.</p>
                    <a href="<?= BASE_URL ?>tours/lake-naivasha-boat" class="btn btn-primary mt-auto">Start Tour</a>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5">
        <a href="<?= BASE_URL ?>upcoming-tours" class="btn btn-outline-primary btn-lg">
            Browse All Free Virtual Tours
        </a>
    </div>
</div>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
