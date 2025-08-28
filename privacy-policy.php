<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Privacy Policy";
$pageDescription = "Learn how EpikiTours protects your privacy.";
$pageSlug = "privacy-policy";
$bannerImage = "images/epiki-tours-car-in-mountain.jpg";

// -------------------
// Start output buffering to capture HTML content
// -------------------
ob_start();
?>

<!-- Privacy Policy -->
<div class="container my-5">
    <h2 class="text-primary mb-4">Privacy Policy</h2>
    <p>At <strong>EpikiTours</strong>, we respect your privacy and are committed to protecting your personal
        information.
        This Privacy Policy explains how we collect, use, and safeguard your data when you use our free virtual tour
        platform.</p>

    <h4 class="mt-4 text-primary">1. Information We Collect</h4>
    <p>Since all our virtual tours are free, we collect very limited information. However, when you interact with our
        platform, we may collect:</p>
    <ul>
        <li>Basic contact details (if you contact us via forms or email).</li>
        <li>Technical data such as IP address, browser type, and device information (for analytics).</li>
        <li>Cookies and usage data to improve user experience.</li>
    </ul>

    <h4 class="mt-4 text-primary">2. How We Use Your Information</h4>
    <p>We use collected information to:</p>
    <ul>
        <li>Provide access to our virtual tours.</li>
        <li>Improve website performance and user experience.</li>
        <li>Respond to inquiries and provide customer support.</li>
        <li>Analyze usage trends to enhance our services.</li>
    </ul>

    <h4 class="mt-4 text-primary">3. Cookies and Tracking</h4>
    <p>EpikiTours may use cookies or similar technologies to personalize your browsing experience and gather analytics.
        You can choose to disable cookies through your browser settings, but this may affect some site functionality.
    </p>

    <h4 class="mt-4 text-primary">4. Data Sharing and Security</h4>
    <p>
        We do <strong>not sell, trade, or rent</strong> your personal information to third parties.
        Any data collected is used only to support your experience on EpikiTours.
        We implement appropriate technical and organizational measures to keep your data secure.
    </p>

    <h4 class="mt-4 text-primary">5. Third-Party Services</h4>
    <p>Our platform may include links to third-party websites or embed services (such as maps, videos, or analytics).
        Please note that we are not responsible for the privacy practices of these third parties.</p>

    <h4 class="mt-4 text-primary">6. Children’s Privacy</h4>
    <p>EpikiTours does not knowingly collect personal information from children under 13.
        If you believe a child has provided us with personal data, please contact us and we will remove it promptly.</p>

    <h4 class="mt-4 text-primary">7. Your Privacy Rights</h4>
    <p>You have the right to request access, correction, or deletion of your personal information.
        To exercise these rights, please <a href="contact-us">contact us</a>.</p>

    <h4 class="mt-4 text-primary">8. Changes to This Policy</h4>
    <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with a revised date.
    </p>

    <h4 class="mt-4 text-primary">9. Contact Us</h4>
    <p>If you have questions about this Privacy Policy, please reach out via our <a href="contact-us">Contact Us</a>
        page.</p>
</div>

<?php
// -------------------
// Capture page content into $pageContent and load template
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>