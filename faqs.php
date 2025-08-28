<?php
// -------------------
// Page Metadata
// -------------------
$pageTitle = "FAQs";
$pageDescription = "Find answers to frequently asked questions about EpikiTours.";
$pageSlug = "faqs";
$bannerImage = "images/epiki-tours-boat-in-water.jpg";

// -------------------
// Start capturing content
// -------------------
ob_start();
?>

<div class="container my-5">
    <p class="text-center mb-5">
        Below are the most common questions about <strong>EpikiTours</strong>.
    </p>

    <div class="accordion" id="faqAccordion">

        <!-- Question 1 -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="faqOneHeading">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne"
                    aria-expanded="true" aria-controls="faqOne">
                    1. How do I access a virtual tour on EpikiTours?
                </button>
            </h2>
            <div id="faqOne" class="accordion-collapse collapse show" aria-labelledby="faqOneHeading"
                data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Simply browse our <a href="top-tours">virtual tours</a>, select the one you want, and start
                    exploring instantly.
                    All EpikiTours experiences are <strong>100% free</strong> to enjoy.
                </div>
            </div>
        </div>

        <!-- Question 2 -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="faqTwoHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">
                    2. Do I need an account to use EpikiTours?
                </button>
            </h2>
            <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="faqTwoHeading"
                data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    No account is required to access our free tours. However, creating an account allows you to
                    save your favorite tours and receive updates on new experiences.
                    <a href="login">Sign up here</a>.
                </div>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="faqThreeHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                    3. What devices can I use for EpikiTours?
                </button>
            </h2>
            <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="faqThreeHeading"
                data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    EpikiTours works on desktops, laptops, tablets, and smartphones.
                    For an immersive experience, you can also enjoy our tours with most VR headsets.
                </div>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="faqFourHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                    4. Do I need to pay for any EpikiTours experiences?
                </button>
            </h2>
            <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="faqFourHeading"
                data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    No. All EpikiTours virtual tours are completely free to access and explore.
                    We believe in making travel experiences accessible to everyone.
                </div>
            </div>
        </div>

        <!-- Question 5 -->
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="faqFiveHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive">
                    5. Who do I contact for support?
                </button>
            </h2>
            <div id="faqFive" class="accordion-collapse collapse" aria-labelledby="faqFiveHeading"
                data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can reach us at <a href="mailto:info@epikitours.com">info@epikitours.com</a>,
                    call <strong>+254 726 790718</strong>, or visit our Nairobi office.
                    Our support team is ready to assist you.
                </div>
            </div>
        </div>

    </div>
</div>

<?php
// -------------------
// Capture page content into $pageContent and load template
// -------------------
$pageContent = ob_get_clean();
include 'layouts/page-template.php';
?>