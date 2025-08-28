<?php
session_start(); // Ensure session is started

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';

// -------------------
// Page Metadata
// -------------------
$pageTitle = "Top Destinations";
$pageDescription = "Explore EpikiTours’ top destinations.";
$pageSlug = "top-destinations";
$bannerImage = "images/historic-adventure-epikitours.webp";

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <!-- Intro -->
    <div class="text-center mb-5">
        <p class="text-muted">
            Discover breathtaking landscapes, rich cultures, and unforgettable experiences at these must-visit
            destinations.
        </p>
    </div>

    <!-- Destinations Grid -->
    <div class="row g-4">
        <!-- Maasai Mara -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-elephants.jpg" alt="Safari at Maasai Mara" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Maasai Mara</h5>
                    <p class="card-text">Witness the world-famous wildebeest migration and the Big Five in Kenya’s
                        premier safari destination.</p>
                    <a href="<?= BASE_URL ?>destinations/maasai-mara" class="btn btn-primary mt-auto">Explore Mara</a>
                </div>
            </div>
        </div>

        <!-- Mount Kenya -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-mountain-top.jpg" alt="Mount Kenya hiking adventure" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Mount Kenya</h5>
                    <p class="card-text">Climb Africa’s second-highest peak and enjoy hiking trails surrounded by alpine
                        scenery.</p>
                    <a href="<?= BASE_URL ?>destinations/mount-kenya" class="btn btn-primary mt-auto">Climb Mount
                        Kenya</a>
                </div>
            </div>
        </div>

        <!-- Diani Beach -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-coast-image.jpg" alt="White sandy beaches at Diani" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Diani Beach</h5>
                    <p class="card-text">Relax on pristine white sandy beaches, explore coral reefs, and enjoy coastal
                        cuisine.</p>
                    <a href="<?= BASE_URL ?>destinations/diani-beach" class="btn btn-primary mt-auto">Discover Diani</a>
                </div>
            </div>
        </div>

        <!-- Amboseli National Park -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-car-in-mountain.jpg"
                    alt="Elephants with Mount Kilimanjaro view in Amboseli" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Amboseli National Park</h5>
                    <p class="card-text">Capture iconic views of elephants with Mount Kilimanjaro as a backdrop.</p>
                    <a href="<?= BASE_URL ?>destinations/amboseli" class="btn btn-primary mt-auto">Visit Amboseli</a>
                </div>
            </div>
        </div>

        <!-- Lake Naivasha -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epiki-tours-boat-in-water.jpg" alt="Boat ride on Lake Naivasha" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Lake Naivasha</h5>
                    <p class="card-text">Enjoy boat rides, bird watching, and serene landscapes at this freshwater lake.
                    </p>
                    <a href="<?= BASE_URL ?>destinations/lake-naivasha" class="btn btn-primary mt-auto">See Naivasha</a>
                </div>
            </div>
        </div>

        <!-- Lamu Island -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="images/epic-tours-historical-sites.jpg" alt="Historical streets of Lamu" class="card-img-top"
                    style="height:220px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Lamu Island</h5>
                    <p class="card-text">Step back in time and explore Swahili heritage, narrow streets, and dhow
                        sailing adventures.</p>
                    <a href="<?= BASE_URL ?>destinations/lamu-island" class="btn btn-primary mt-auto">Experience
                        Lamu</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
