<?php
session_start();

// -------------------
// Load Config & Routes
// -------------------
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/routes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Title -->
    <title>EpikiTours - Free Virtual Tours of Africa</title>

    <!-- Meta -->
    <meta name="description"
        content="EpikiTours offers free virtual tours of Africa’s mountains, wildlife, history, and culture. Explore breathtaking destinations from anywhere in the world.">
    <meta name="keywords" content="EpikiTours, Virtual Tours, Africa, Online Safari, Virtual Travel">
    <meta name="author" content="EpikiTours">

    <!-- Open Graph -->
    <meta property="og:title" content="EpikiTours - Free Virtual Tours of Africa" />
    <meta property="og:description"
        content="Join free virtual tours and explore Africa’s landscapes, wildlife, and culture online." />
    <meta property="og:image" content="images/epiki-tours-logo.png" />
    <meta property="og:url" content="https://www.epikitours.com" />
    <meta property="og:type" content="website" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="EpikiTours - Free Virtual Tours of Africa" />
    <meta name="twitter:description" content="Experience free online adventures through our immersive virtual tours." />
    <meta name="twitter:image" content="images/epiki-tours-logo.png" />

    <!-- Favicon -->
    <link rel="icon" href="images/epiki-tours-logo.png" type="image/png" />

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- Header -->
    <?php include 'includes/main-header.php'; ?>

    <!-- Hero Banner -->
    <section class="hero-carousel-section">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Virtual Historical Expeditions -->
                <div class="carousel-item active">
                    <div class="d-flex align-items-center vh-100 text-white"
                        style="background: linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.35)), url('images/historic-adventure-epikitours.webp') center/cover no-repeat;">
                        <div class="container">
                            <div class="col-md-6">
                                <h1 class="display-4 fw-bold mb-3">Virtual Historical Expeditions</h1>
                                <p class="mb-4">Walk through centuries of culture and history without leaving home.</p>
                                <a href="top-tours" class="btn btn-secondary">Start Adventure</a>
                                <a href="create-account" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Virtual Mountain Adventures -->
                <div class="carousel-item">
                    <div class="d-flex align-items-center vh-100 text-white"
                        style="background: linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.35)), url('images/mountain-top-adventure- epikitours.webp') center/cover no-repeat;">
                        <div class="container">
                            <div class="col-md-6">
                                <h1 class="display-4 fw-bold mb-3">Virtual Mountain Adventures</h1>
                                <p class="mb-4">Experience breathtaking mountain landscapes from your screen.</p>
                                <a href="top-tours" class="btn btn-primary">Start Adventure</a>
                                <a href="create-account" class="btn btn-secondary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Virtual Wildlife Safari -->
                <div class="carousel-item">
                    <div class="d-flex align-items-center vh-100 text-white"
                        style="background: linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.35)), url('images/wildlife-adventure-epikitours.webp') center/cover no-repeat;">
                        <div class="container">
                            <div class="col-md-6">
                                <h1 class="display-4 fw-bold mb-3">Virtual Wildlife Safari</h1>
                                <p class="mb-4">See Africa’s majestic elephants and wildlife in stunning detail.</p>
                                <a href="top-tours" class="btn btn-secondary">Start Adventure</a>
                                <a href="create-account" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex align-items-center vh-100 text-white"
                        style="background: linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.35)), url('images/camping-adventure-epikitours.webp') center/cover no-repeat;">
                        <div class="container">
                            <div class="col-md-6">
                                <h1 class="display-4 fw-bold mb-3">Virtual Camping Experience</h1>
                                <p class="mb-4">Enjoy the sights and sounds of nature in a virtual campfire setting.</p>
                                <a href="top-tours" class="btn btn-primary">Start Adventure</a>
                                <a href="create-account" class="btn btn-secondary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="d-flex align-items-center vh-100 text-white"
                        style="background: linear-gradient(rgba(0,0,0,0.25), rgba(0,0,0,0.35)), url('images/coastal-adventure-epikitours.webp') center/cover no-repeat;">
                        <div class="container">
                            <div class="col-md-6">
                                <h1 class="display-4 fw-bold mb-3">Virtual Coastal Adventure</h1>
                                <p class="mb-4">Relax by Africa’s beautiful coastlines, virtually.</p>
                                <a href="top-tours" class="btn btn-secondary">Start Adventure</a>
                                <a href="create-account" class="btn btn-primary">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

            <!-- Indicators -->
            <div class="carousel-indicators position-absolute bottom-0 mb-4">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
            </div>
        </div>
    </section>
    <style>
        /* -------------------
        Hero Banner Height Adjustments
        ------------------- */
        @media (min-width: 992px) {
            .hero-carousel-section .carousel-item>div {
                height: 70vh !important;
            }
        }
    </style>
    <!-- Featured Virtual Tours -->
    <section class="container my-5">
        <h2 class="text-center mb-4 text-primary">Featured Virtual Tours</h2>
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="images/epiki-tours-elephants.jpg" class="card-img-top" alt="Virtual Safari">
                    <div class="card-body text-center">
                        <h5 class="card-title">Virtual Safari Adventure</h5>
                        <p class="card-text">See Africa’s Big 5 animals up close — no passport required.</p>
                        <a href="tour-details.php?id=1" class="btn btn-outline-primary">Start Tour</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="images/epiki-tours-mountain-top.jpg" class="card-img-top" alt="Virtual Hike">
                    <div class="card-body text-center">
                        <h5 class="card-title">Virtual Mountain Hike</h5>
                        <p class="card-text">Explore towering peaks and sweeping landscapes virtually.</p>
                        <a href="tour-details.php?id=2" class="btn btn-outline-primary">Start Tour</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="images/epiki-tours-boat-in-water.jpg" class="card-img-top" alt="Virtual Boat Tour">
                    <div class="card-body text-center">
                        <h5 class="card-title">Virtual Sunset Boat Tour</h5>
                        <p class="card-text">Relax and enjoy a stunning sunset boat ride online.</p>
                        <a href="tour-details.php?id=3" class="btn btn-outline-primary">Start Tour</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Newsletter Signup -->
    <?php include 'includes/subscribe-newsletter.php'; ?>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>