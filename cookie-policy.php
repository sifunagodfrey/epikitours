<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "Cookie Policy";
$pageDescription = "Read about our cookie usage, types, and control settings.";
$pageSlug = "cookie-policy";
$bannerImage = "images/epiki-tours-car-in-mountain.jpg";

// -------------------
// Start Output Buffering
// -------------------
ob_start();
?>

<div class="container my-5">
    <p class="text-muted">Effective Date: <strong>30 May 2025</strong></p>

    <h5 class="mt-4">1. Introduction</h5>
    <p>This Cookie Policy explains how <strong>EpikiTours</strong> (“we”, “our”, or “us”) uses cookies and similar
        tracking
        technologies to enhance your experience on our website, improve performance, personalize content, and deliver
        relevant advertising.</p>
    <p>By continuing to use our website, you agree to our use of cookies in accordance with this policy.</p>

    <h5 class="mt-4">2. What Are Cookies?</h5>
    <p>Cookies are small text files stored on your device (computer, tablet, smartphone) by websites you visit. They
        help websites recognize your device, remember your preferences, and improve functionality.</p>

    <h5 class="mt-4">3. Types of Cookies We Use</h5>

    <ul>
        <li><strong>Essential Cookies:</strong> Required for website functionality (e.g., booking tours, secure login,
            account access).</li>
        <li><strong>Performance & Analytics Cookies:</strong> Help us understand how users interact with our site (e.g.,
            page speed, errors, traffic sources).</li>
        <li><strong>Functionality Cookies:</strong> Remember user preferences like language, region, or currency for a
            personalized browsing experience.</li>
        <li><strong>Advertising & Targeting Cookies:</strong> Deliver ads relevant to your interests, reduce ad
            repetition, and track campaign effectiveness.</li>
    </ul>

    <h5 class="mt-4">4. Third-Party Cookies</h5>
    <p>We may allow trusted third-party services to place cookies on your device to analyze site usage, enable social
        sharing, or deliver targeted ads. These include tools such as:</p>
    <ul>
        <li>Google Analytics</li>
        <li>Facebook Pixel</li>
        <li>Meta Ads, Google Ads, and similar platforms</li>
    </ul>
    <p>These third parties have their own cookie and privacy policies which you can review on their respective websites.
    </p>

    <h5 class="mt-4">5. How to Control Cookies</h5>
    <p>You can manage or delete cookies anytime via your browser settings. Below are links to instructions for common
        browsers:</p>

    <ul>
        <li><strong>Chrome:</strong> Settings &gt; Privacy and security &gt; Cookies and other site data</li>
        <li><strong>Firefox:</strong> Options &gt; Privacy & Security &gt; Cookies and Site Data</li>
        <li><strong>Safari:</strong> Preferences &gt; Privacy &gt; Manage Website Data</li>
        <li><strong>Microsoft Edge:</strong> Settings &gt; Site permissions &gt; Cookies and site data</li>
    </ul>

    <p><em>Note:</em> Disabling cookies may affect functionality and limit your experience on EpikiTours.</p>

    <h5 class="mt-4">6. Policy Updates</h5>
    <p>We may update this Cookie Policy from time to time to reflect changes in law, technology, or business practices.
        Updates will be posted here with the new effective date.</p>

    <h5 class="mt-4">7. Contact Us</h5>
    <p>If you have questions about this Cookie Policy or how we use cookies, please contact us:</p>

    <address>
        <strong>EpikiTours</strong><br>
        Nairobi, Kenya<br>
        Phone: +254 726 790718<br>
        Email: <a href="mailto:support@epikitours.com">support@epikitours.com</a>
    </address>
</div>

<?php
// -------------------
// End Buffer & Include Layout
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>