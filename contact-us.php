<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Contact Us";
$pageDescription = "Get in touch with EpikiTours for bookings, inquiries, and support";
$pageSlug = "contact-us";
$bannerImage = "images/mountain-top-adventure- epikitours.webp";

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <div class="row">
        <!-- Contact Information -->
        <div class="col-md-5 mb-4">
            <h4 class="text-primary mb-3">Get in Touch</h4>
            <p class="mb-4">We’d love to hear from you! Whether you’re planning a trip, have a question, or need
                assistance, our team is here for you.</p>

            <p><i class="fas fa-map-marker-alt text-primary me-2"></i> Nairobi, Kenya</p>
            <p><i class="fas fa-phone text-primary me-2"></i> +254 726 790718</p>
            <p><i class="fas fa-envelope text-primary me-2"></i>
                <a class="text-decoration-none" href="mailto:support@epikitours.com">support@epikitours.com</a>
            </p>
        </div>

        <!-- Contact Form -->
        <div class="col-md-7">
            <h4 class="text-primary mb-3">Send Us a Message</h4>
            <form action="process-contact.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>