<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Terms and Conditions";
$pageDescription = "Read the Terms and Conditions for using EpikiTours";
$pageSlug = "terms-and-conditions";
$bannerImage = "images/epiki-tours-car-in-mountain.jpg";

// -------------------
// Start output buffering to capture HTML content
// -------------------
ob_start();
?>

<!-- Terms and Conditions -->
<div class="container my-5">
    <h2 class="text-primary mb-4">Terms and Conditions</h2>
    <p>Welcome to <strong>EpikiTours</strong>. By accessing or using our free virtual tour platform, you agree to comply
        with these Terms and Conditions.
        Please read them carefully before using our services.</p>

    <h4 class="mt-4 text-primary">1. Use of Our Platform</h4>
    <p>EpikiTours provides free virtual tours for educational and entertainment purposes. You agree to use the platform
        lawfully and in accordance with these terms.
        Misuse, including attempting to disrupt the platform, reverse-engineer its code, or use it for illegal activity,
        is prohibited.</p>

    <h4 class="mt-4 text-primary">2. User Accounts</h4>
    <p>Most tours are accessible without registration. If you create an account (e.g., to save preferences or contact
        us), you are responsible for maintaining the confidentiality of your login credentials and for all activities
        under your account.</p>

    <h4 class="mt-4 text-primary">3. Intellectual Property</h4>
    <p>All content on EpikiTours, including text, images, videos, and software, is the intellectual property of
        EpikiTours or its content partners.
        You may view and share virtual tours for personal use, but you may not copy, distribute, or use our content for
        commercial purposes without prior written consent.</p>

    <h4 class="mt-4 text-primary">4. Third-Party Links and Services</h4>
    <p>Our platform may contain links to third-party websites, services, or embedded media. We are not responsible for
        the content, policies, or practices of third-party sites.</p>

    <h4 class="mt-4 text-primary">5. Disclaimer of Warranties</h4>
    <p>EpikiTours is provided “as is” and “as available.” While we strive to offer high-quality experiences, we make no
        guarantees that the platform will always be available, secure, or error-free.</p>

    <h4 class="mt-4 text-primary">6. Limitation of Liability</h4>
    <p>EpikiTours and its team shall not be held liable for any direct, indirect, incidental, or consequential damages
        arising from your use of our platform.
        Your use of the platform is at your own risk.</p>

    <h4 class="mt-4 text-primary">7. Termination of Use</h4>
    <p>We reserve the right to restrict or terminate access to EpikiTours if we believe you have violated these Terms
        and Conditions.</p>

    <h4 class="mt-4 text-primary">8. Governing Law</h4>
    <p>These Terms are governed by and construed in accordance with the laws of Kenya (or your applicable jurisdiction).
        Any disputes shall be subject to the jurisdiction of local courts.</p>

    <h4 class="mt-4 text-primary">9. Changes to These Terms</h4>
    <p>EpikiTours may update these Terms and Conditions at any time. Changes will be posted on this page with a revised
        date.
        Your continued use of the platform constitutes acceptance of the updated terms.</p>

    <h4 class="mt-4 text-primary">10. Contact Us</h4>
    <p>If you have any questions about these Terms and Conditions, please reach out via our <a href="contact-us">Contact
            Us</a> page.</p>
</div>

<?php
// -------------------
// Capture page content into $pageContent and load template
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>