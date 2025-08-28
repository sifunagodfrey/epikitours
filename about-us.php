<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "About Us";
$pageDescription = "EpikiTours is a virtual tour platform.";
$pageSlug = "about-us";
$bannerImage = "images/epiki-tours-car-in-mountain.jpg";

// -------------------
// Start output buffering to capture HTML content
// -------------------
ob_start();
?>

<!-- About EpikiTours -->
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <p>
                EpikiTours is a virtual tour platform that brings the beauty, culture,
                and adventures of Africa directly to your screen. Whether you’re at home, in the office,
                or on the go, our immersive experiences let you explore breathtaking destinations
                <strong>completely free</strong>.
            </p>

            <h4 class="text-primary mt-4">Our Vision</h4>
            <p>
                To make Africa’s wonders accessible to everyone, anywhere in the world, through
                interactive, inspiring, and educational virtual tours.
            </p>

            <h4 class="text-primary mt-4">What We Offer</h4>
            <p>
                Our curated collection of virtual experiences showcases Africa’s diversity,
                from world-famous landmarks to hidden treasures. You can explore:
            </p>
            <ul>
                <li>Wildlife safaris and game reserves</li>
                <li>Mountain and hiking adventures</li>
                <li>Cultural and historical landmarks</li>
                <li>Coastal and beach escapes</li>
                <li>Immersive city and heritage tours</li>
            </ul>
        </div>

        <div class="col-md-6">
            <img src="images/epiki-tours-elephants.jpg" class="img-fluid rounded shadow"
                alt="EpikiTours Virtual Safari">
        </div>
    </div>
</div>

<!-- Why Choose Us -->
<div class="container my-5">
    <h3 class="text-center mb-4 text-primary">Why Explore with EpikiTours</h3>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow p-4 h-100">
                <i class="fas fa-vr-cardboard fa-2x text-success mb-3"></i>
                <h5>Immersive Experiences</h5>
                <p>Enjoy high-quality virtual tours that let you feel like you’re truly there—without leaving your home.
                </p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow p-4 h-100">
                <i class="fas fa-globe-africa fa-2x text-warning mb-3"></i>
                <h5>Explore Africa Anywhere</h5>
                <p>From iconic safaris to cultural gems, discover Africa’s beauty from any device, anywhere in the
                    world.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow p-4 h-100">
                <i class="fas fa-hand-holding-heart fa-2x text-primary mb-3"></i>
                <h5>Completely Free</h5>
                <p>All EpikiTours virtual experiences are 100% free—making exploration accessible to everyone.</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="container my-5 text-center">
    <div class="p-5 bg-light rounded shadow">
        <h3 class="text-primary mb-3">Start Your Virtual Journey Today</h3>
        <p class="lead mb-4">
            Discover Africa’s landscapes, wildlife, and cultures with <strong>EpikiTours</strong>.
            All our virtual tours are <strong>free</strong>—just click and explore.
        </p>

        <a href="top-tours" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-vr-cardboard me-2"></i> Explore Virtual Tours
        </a>
        <a href="contact-us" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-envelope-open-text me-2"></i> Contact Us
        </a>
    </div>
</div>

<?php
// -------------------
// Capture page content into $pageContent and load template
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>