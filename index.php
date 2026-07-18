<?php include 'includes/header.php'; ?>

<!-- Home Section / Hero Banner -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Hero Text Content -->
            <div class="col-lg-6 text-center text-lg-start">
                <div class="tagline-badge">
                    <i class="bi bi-shield-check"></i> Smart Academic Automation
                </div>
                <h1 class="hero-title">
                    Digitizing Attendance <br>
                    <span class="gradient-title">Empowering Education</span>
                </h1>
                <p class="hero-desc">
                    AttendEase provides a seamless, secure, and modern portal to record attendance, track student history, and generate comprehensive compliance reports. 
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                    <a href="login.php" class="btn-premium" id="hero-cta-get-started">
                        Get Started <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#about" class="btn-premium-outline" id="hero-cta-learn-more">
                        Learn More <i class="bi bi-info-circle"></i>
                    </a>
                </div>
            </div>

            <!-- Hero Interactive Dashboard Mockup -->
            <div class="col-lg-6">
                <div class="dashboard-preview animation-float" id="hero-dashboard-mockup">
                    <div class="preview-header">
                        <div class="dot-group">
                            <span class="dot dot-red"></span>
                            <span class="dot dot-yellow"></span>
                            <span class="dot dot-green"></span>
                        </div>
                        <span class="preview-tab">Admin Dashboard View</span>
                    </div>
                    
                    <!-- Top stats columns -->
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <div class="stat-box">
                                <span class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Total Students</span>
                                <span class="stat-box-val text-white">1,540</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <span class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Present Today</span>
                                <span class="stat-box-val text-success">1,463</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-box">
                                <span class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Attendance %</span>
                                <span class="stat-box-val text-info">95.0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chart section -->
                    <div class="stat-box mb-3 text-start">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-white" style="font-size: 0.8rem; font-weight: 600;">Monthly Attendance Trends</span>
                            <span class="text-success" style="font-size: 0.75rem;"><i class="bi bi-graph-up"></i> +1.2% this month</span>
                        </div>
                        <div style="height: 100px; position: relative;">
                            <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="chart-glow" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M0 25 Q15 5 30 18 T60 8 T90 15 T100 8 L100 30 L0 30 Z" fill="url(#chart-glow)"/>
                                <path d="M0 25 Q15 5 30 18 T60 8 T90 15 T100 8" fill="none" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round"/>
                                
                                <circle cx="30" cy="18" r="1.5" fill="#0ea5e9"/>
                                <circle cx="60" cy="8" r="1.5" fill="#0ea5e9"/>
                                <circle cx="90" cy="15" r="1.5" fill="#0ea5e9"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Active Low Attendance Alerts -->
                    <div class="stat-box text-start p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-white" style="font-size: 0.8rem; font-weight: 600;">System Alerts</span>
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">Action Needed</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between border-bottom border-secondary pb-2 mb-2" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                <span>Aarav Mehta (IT - Sem IV)</span>
                            </div>
                            <span class="text-danger fw-semibold">68.5%</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                <span>Neha Sharma (CS - Sem VI)</span>
                            </div>
                            <span class="text-warning fw-semibold">72.1%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Section -->
<section class="stats-counter-section">
    <div class="container">
        <div class="row g-4">
            <!-- Counter 1 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="1500+" id="stat-students">1500+</div>
                    <div class="counter-label">Active Students</div>
                </div>
            </div>
            <!-- Counter 2 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="120+" id="stat-faculty">120+</div>
                    <div class="counter-label">Expert Faculty</div>
                </div>
            </div>
            <!-- Counter 3 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="98%" id="stat-attendance">98%</div>
                    <div class="counter-label">Daily Avg Rate</div>
                </div>
            </div>
            <!-- Counter 4 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="15+" id="stat-departments">15+</div>
                    <div class="counter-label">Departments</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="section-padding about-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- About Graphic Placeholder / Concept -->
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="about-img-container" id="about-concept-illustration">
                    <div class="about-img-placeholder">
                        <div class="animation-float-delayed mb-4">
                            <i class="bi bi-laptop text-primary" style="font-size: 4rem;"></i>
                            <i class="bi bi-arrow-left-right text-muted mx-3" style="font-size: 2rem;"></i>
                            <i class="bi bi-phone text-accent" style="font-size: 3rem;"></i>
                        </div>
                        <span class="about-stat-pill mb-2"><i class="bi bi-lightning-fill"></i> Lightning Fast Cloud Processing</span>
                        <h4 class="text-white mt-2">Responsive Across All Screens</h4>
                        <p class="text-muted small px-md-5">Use the portal on any device. Administrators, faculty, and students can view dashboards, update rolls, and export details on desktop, tablet, or phone.</p>
                    </div>
                </div>
            </div>

            <!-- About Text Content -->
            <div class="col-lg-6 order-1 order-lg-2">
                <span class="section-tagline">About the Portal</span>
                <h2 class="section-title">A Modern Solution to Class Attendance</h2>
                <p class="text-muted mb-4">
                    Paper-based attendance sheets are slow, error-prone, and require tedious manual entries for calculations. AttendEase bridges this gap by offering a central hub where attendance status is logged instantly, reports are auto-calculated, and compliance statistics are immediately transparent.
                </p>
                
                <!-- Highlights List -->
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex gap-3">
                        <div class="text-primary fs-3"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <h5 class="mb-1 text-white">Save Valuable Lecture Time</h5>
                            <p class="text-muted small mb-0">Record attendance in under a minute with auto-generated student lists categorized by course and semester.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div class="text-accent fs-3"><i class="bi bi-bell-fill"></i></div>
                        <div>
                            <h5 class="mb-1 text-white">Automated Low Attendance Alerts</h5>
                            <p class="text-muted small mb-0">System automatically tracks thresholds and alerts students with low percentages to prevent academic setbacks.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div class="text-success fs-3"><i class="bi bi-shield-lock-fill"></i></div>
                        <div>
                            <h5 class="mb-1 text-white">Role-Based Access Security</h5>
                            <p class="text-muted small mb-0">Granular authentication ensures students view only their records, faculty mark their subjects, and admins maintain absolute control.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section-padding">
    <div class="container text-center">
        <span class="section-tagline">Key Capabilities</span>
        <h2 class="section-title">Designed for Educational Institutions</h2>
        <p class="section-desc">
            Equipped with core features that meet the requirements of Administrators, Department Heads, Professors, and Students alike.
        </p>
        
        <div class="row g-4 text-start">
            <!-- Feature Card 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-rbac">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="feature-title text-white">Role-Based Dashboards</h3>
                    <p class="feature-desc">Dedicated dashboard workflows designed specifically for Super Admins, Faculty staff, and Students.</p>
                </div>
            </div>
            
            <!-- Feature Card 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-marking">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>
                    <h3 class="feature-title text-white">Rapid Attendance Marking</h3>
                    <p class="feature-desc">Load class list, mark present/absent toggles, add remarks, and commit records to the database instantly.</p>
                </div>
            </div>
            
            <!-- Feature Card 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-history">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h3 class="feature-title text-white">Attendance History</h3>
                    <p class="feature-desc">View historical logs and modify attendance entries within authorized modification windows.</p>
                </div>
            </div>
            
            <!-- Feature Card 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-analytics">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <h3 class="feature-title text-white">Live Graphs & Trends</h3>
                    <p class="feature-desc">Examine subject-wise percentages, monthly tendencies, and department efficiency rates visually.</p>
                </div>
            </div>
            
            <!-- Feature Card 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-alerts">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-envelope-exclamation-fill"></i>
                    </div>
                    <h3 class="feature-title text-white">Smart Threshold Alerts</h3>
                    <p class="feature-desc">Generate notifications for students falling below the mandatory 75% attendance threshold.</p>
                </div>
            </div>
            
            <!-- Feature Card 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card" id="feature-card-export">
                    <div class="feature-icon-wrapper">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                    </div>
                    <h3 class="feature-title text-white">PDF & Excel Reporting</h3>
                    <p class="feature-desc">Compile tabular semester-wise, student-wise, or division-wise logs and export directly to PDF or Excel.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action (Institutional Signup promo) -->
<section class="section-padding cta-section">
    <div class="container">
        <div class="cta-box" id="cta-invite-box">
            <h2 class="text-white mb-3">Ready to Modernize Your Campus?</h2>
            <p class="text-muted mb-4 mx-auto" style="max-width: 600px;">
                Bring transparency, save teachers' time, and fulfill regulatory compliance in just a few clicks. Request institutional setup instructions or check the portal out.
            </p>
            <a href="login.php" class="btn-premium px-5 py-3 fs-5" id="cta-login-btn">
                Access Portal Login <i class="bi bi-box-arrow-in-right"></i>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
