<footer class="custom-footer bg-secondary text-dark py-5 position-relative">
    <div class="container">
        <div class="row">
            <!-- About EpikiTours -->
            <div class="col-md-3">
                <a class="navbar-brand me-3" href="<?= BASE_URL ?>">
                    <img src="<?= BASE_URL ?>images/epiki-tours-logo-white.png" alt="EpikiTours Logo" height="65">
                </a>
                <!-- Social Icons -->
                <div class="social-icons mt-3 mb-5">
                    <!-- Facebook -->
                    <a href="https://facebook.com/epikitours" target="_blank" class="social-icon bg-primary me-2">
                        <i class="fab fa-facebook-f text-white"></i>
                    </a>
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/epikitours" target="_blank" class="social-icon bg-primary me-2">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@epikitours" target="_blank" class="social-icon bg-primary me-2">
                        <i class="fab fa-tiktok text-white"></i>
                    </a>
                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/company/epikitours" class="social-icon bg-primary">
                        <i class="fab fa-linkedin-in text-white"></i>
                    </a>
                </div>

            </div>

            <!-- Quick Links -->
            <div class="col-md-3">
                <h5 class="text-dark">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>tour-calendr" class="text-dark text-decoration-none">Tour Calender</a>
                    </li>
                    <li><a href="<?= BASE_URL ?>top-tours" class="text-dark text-decoration-none">Top Virtual Tours</a>
                    </li>

                    <li><a href="<?= BASE_URL ?>faqs" class="text-dark text-decoration-none">FAQs</a></li>
                    <li><a href="<?= BASE_URL ?>login" class="text-dark text-decoration-none">Login / Sign Up</a></li>
                    <li><a href="<?= BASE_URL ?>virtual-destinations" class="text-dark text-decoration-none">Virtual
                            Destinations</a></li>
                </ul>
            </div>

            <!-- Company Policies -->
            <div class="col-md-3">
                <h5 class="text-dark">Our Company</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>contact-us" class="text-dark text-decoration-none">Contact Us</a></li>

                    <li><a href="<?= BASE_URL ?>about-us" class="text-dark text-decoration-none">About Us</a></li>
                    <li><a href="<?= BASE_URL ?>privacy-policy" class="text-dark text-decoration-none">Privacy
                            Policy</a></li>
                    <li><a href="<?= BASE_URL ?>cookie-policy" class="text-dark text-decoration-none">Cookie Policy</a>
                    </li>
                    <li><a href="<?= BASE_URL ?>terms-and-conditions" class="text-dark text-decoration-none">Terms &
                            Conditions</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-3">
                <h5 class="text-dark">Contact Us</h5>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-envelope me-2 text-primary"></i>
                        <a href="mailto:info@epikitours.com"
                            class="text-dark text-decoration-none">info@epikitours.com</a>
                    </li>
                    <li>
                        <i class="fas fa-phone me-2 text-primary"></i>
                        +254 726 790718
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        Nairobi, Kenya
                    </li>
                </ul>


            </div>
        </div>
    </div>
    <!-- Copyright -->
    <div class="copyright bg-primary py-3 mt-4">
        <div class="container text-center">
            <p class="mb-0 text-white">
                &copy; <?= date('Y') ?> EpikiTours. All Rights Reserved.
            </p>
        </div>
    </div>
</footer>