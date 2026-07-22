    <!-- Reusable Footer Section -->
    <footer class="footer-section" id="contact">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <!-- Column 1: Info and Description -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-logo d-flex align-items-center gap-2">
                        <img src="<?php echo $base_path; ?>assets/images/logo/logo.svg" alt="Portal Logo" width="30" height="30">
                        <span>Attend<span class="brand-gradient">Ease</span></span>
                    </div>
                    <p class="footer-desc">
                        A state-of-the-art attendance management system designed to eliminate paperwork, provide instant performance reports, and enhance communication between students, faculty, and administrative staff.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="social-icon" aria-label="Facebook" id="footer-social-fb"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon" aria-label="Twitter" id="footer-social-tw"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-icon" aria-label="LinkedIn" id="footer-social-ln"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="social-icon" aria-label="GitHub" id="footer-social-gh"><i class="bi bi-github"></i></a>
                    </div>
                </div>

                <!-- Column 2: Navigation Links -->
                <div class="col-lg-2 col-md-6">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo $home_link; ?>" id="footer-link-home"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="<?php echo $about_link; ?>" id="footer-link-about"><i class="bi bi-chevron-right"></i> About Us</a></li>
                        <li><a href="<?php echo $features_link; ?>" id="footer-link-features"><i class="bi bi-chevron-right"></i> Key Features</a></li>
                        <li><a href="<?php echo $base_path; ?>modules/authentication/login.php" id="footer-link-login"><i class="bi bi-chevron-right"></i> Login Portal</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-lg-4 col-md-12">
                    <h3 class="footer-title">Contact Support</h3>
                    <div class="footer-contact-info" id="footer-contact-addr">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>123 University Campus, Academic Block A, Mumbai, India</span>
                    </div>
                    <div class="footer-contact-info" id="footer-contact-phone">
                        <i class="bi bi-telephone-fill"></i>
                        <span>+91 98765 43210</span>
                    </div>
                    <div class="footer-contact-info" id="footer-contact-email">
                        <i class="bi bi-envelope-fill"></i>
                        <span>support@attendease.edu</span>
                    </div>
                    <div class="footer-contact-info" id="footer-contact-time">
                        <i class="bi bi-clock-fill"></i>
                        <span>Monday - Saturday: 9:00 AM - 5:00 PM</span>
                    </div>
                </div>
            </div>

            <!-- Column 4: Bottom bar -->
            <div class="footer-bottom">
                <div class="row align-items-center justify-content-center text-center g-2">
                    <div class="col-12">
                        <p class="mb-2">&copy; <?php echo date('Y'); ?> AttendEase. All Rights Reserved.</p>
                    </div>
                    <div class="col-12">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="#" class="text-decoration-none text-muted" id="footer-link-privacy">Privacy Policy</a></li>
                            <li class="list-inline-item ms-3"><a href="#" class="text-decoration-none text-muted" id="footer-link-terms">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Main Script -->
    <script src="<?php echo $base_path; ?>assets/js/app.js"></script>
    <?php if (in_array($current_page, ['attendance-history.php', 'monthly-report.php', 'student-summary.php'])): ?>
    <!-- Chart.js CDN for Analytics Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Faculty Reports JavaScript -->
    <script src="<?php echo $base_path; ?>assets/js/reports.js"></script>
    <?php endif; ?>
</body>
</html>
