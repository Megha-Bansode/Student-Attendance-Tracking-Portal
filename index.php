<?php 
require_once 'config/database.php';
include 'includes/header.php'; 

// Fetch dynamic stats
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_faculty = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'faculty'")->fetchColumn();
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();

// Calculate average attendance % for the latest date
$latest_date = $pdo->query("SELECT MAX(date) FROM attendance")->fetchColumn();
$present_count = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ? AND status = 'Present'");
$present_count->execute([$latest_date]);
$present_val = $present_count->fetchColumn();

$total_att_count = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ?");
$total_att_count->execute([$latest_date]);
$total_val = $total_att_count->fetchColumn();

$avg_attendance = ($total_val > 0) ? round(($present_val / $total_val) * 100, 1) : 0;
?>

<!-- Splash Intro Screen -->
<div class="splash-container" id="splash-root">
    <div class="logo-exit d-flex align-items-center gap-4 z-50">
        <!-- SVG Cap Logo -->
        <div class="position-relative" style="width: 96px; height: 96px; display: flex; align-items: center; justify-content: center;">
            <svg width="96" height="96" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 0 15px rgba(0,245,255,0.3));">
                <path d="M10 40L50 20L90 40L50 60L10 40Z" stroke="#00F5FF" stroke-width="2.5" class="cap-trace"></path>
                <path d="M25 47.5V70C25 70 35 78 50 78C65 78 75 70 75 70V47.5" stroke="#00F5FF" stroke-width="2.5" class="cap-trace"></path>
                <g class="badge-pop">
                    <circle cx="50" cy="45" r="16" fill="#0B132B" stroke="#00F5FF" stroke-width="1.5" style="filter: drop-shadow(0 0 8px rgba(0, 245, 255, 0.6));"></circle>
                    <path d="M43 45L48 50L57 41" stroke="#00F5FF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
            </svg>
        </div>
        <!-- Branding Text -->
        <div class="position-relative d-flex align-items-center" style="height: 96px; overflow: hidden; padding-right: 1.5rem;">
            <h1 class="display-5 fw-extrabold m-0 text-white tracking-tight d-flex align-items-center" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 3.5rem;">
                <span class="text-reveal-attend text-white">Attend</span>
                <span class="text-reveal-ease" style="background: linear-gradient(90deg, #00F5FF, #00D1FF); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 12px rgba(0, 245, 255, 0.3)); margin-left: 0.35rem;">Ease</span>
            </h1>
            <div class="flare"></div>
        </div>
    </div>
</div>
<!-- Home Section / Hero Banner -->
<section id="home" class="hero-section">
    <!-- Interactive Background Canvas -->
    <canvas id="gravity-particle-canvas"></canvas>
    
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
                    <a href="<?php echo $base_path; ?>modules/authentication/login.php" class="btn-premium" id="hero-cta-get-started">
                        Get Started <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#about" class="btn-premium-outline" id="hero-cta-learn-more">
                        Learn More <i class="bi bi-info-circle"></i>
                    </a>
                </div>
            </div>

            <!-- Hero Video Mockup Frame (Now housing a pure SVG/CSS Dashboard Animation) -->
            <div class="col-lg-6">
                <div id="hero-video-player-container">
                    <div class="video-mockup-header">
                        <div class="dot-group">
                            <span class="dot dot-red"></span>
                            <span class="dot dot-yellow"></span>
                            <span class="dot dot-green"></span>
                        </div>
                        <span class="video-mockup-title">Live Attendance Console</span>
                    </div>
                    <div class="animated-svg-dashboard-wrapper p-3 text-start">
                        <!-- Stats Mini Row -->
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="stat-box-mini">
                                    <span class="lbl">Students</span>
                                    <span class="val"><?php echo number_format($total_students); ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box-mini">
                                    <span class="lbl">Present Today</span>
                                    <span class="val text-success"><?php echo number_format($present_val); ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box-mini">
                                    <span class="lbl">Attendance %</span>
                                    <span class="val text-info"><?php echo $avg_attendance; ?>%</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Auto-Drawing Graph -->
                        <div class="chart-box-mini mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.6rem; color: #94a3b8;">
                                <span>Attendance Trend</span>
                                <span class="text-success"><i class="bi bi-arrow-up-short"></i> +1.2%</span>
                            </div>
                            <svg viewBox="0 0 100 30" width="100%" height="80px">
                                <defs>
                                    <linearGradient id="mini-glow" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#6366f1" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <path class="graph-fill" d="M0 25 Q15 5 30 18 T60 8 T90 15 T100 8 L100 30 L0 30 Z" fill="url(#mini-glow)" />
                                <path class="graph-line" d="M0 25 Q15 5 30 18 T60 8 T90 15 T100 8" fill="none" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>

                        <!-- Typewriter Console Output -->
                        <div class="console-box-mini p-2">
                            <div class="console-line"><span class="text-success">[OK]</span> Syncing database...</div>
                            <div class="console-line"><span class="text-warning">[WARN]</span> Alert sent: Tanay Shelar (68.5%)</div>
                            <div class="console-line"><span class="text-info">[INFO]</span> Attendance updated for IT Sem-IV</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Section -->
<section class="stats-counter-section light-hybrid">
    <div class="container">
        <div class="row g-4">
            <!-- Counter 1 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="<?php echo $total_students; ?>+" id="stat-students"><?php echo $total_students; ?>+</div>
                    <div class="counter-label">Active Students</div>
                </div>
            </div>
            <!-- Counter 2 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="<?php echo $total_faculty; ?>+" id="stat-faculty"><?php echo $total_faculty; ?>+</div>
                    <div class="counter-label">Expert Faculty</div>
                </div>
            </div>
            <!-- Counter 3 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="<?php echo $avg_attendance; ?>%" id="stat-attendance"><?php echo $avg_attendance; ?>%</div>
                    <div class="counter-label">Daily Avg Rate</div>
                </div>
            </div>
            <!-- Counter 4 -->
            <div class="col-md-3 col-6">
                <div class="counter-item">
                    <div class="counter-number" data-target="<?php echo $total_departments; ?>+" id="stat-departments"><?php echo $total_departments; ?>+</div>
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
            <!-- About Graphic: Light Hybrid Multi-Device Simulator -->
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="device-simulator-card">
                    <!-- Device Navigation Tabs -->
                    <div class="device-switcher-nav">
                        <button class="device-nav-btn active" data-device="laptop">
                            <i class="bi bi-laptop"></i> Laptop
                        </button>
                        <button class="device-nav-btn" data-device="tablet">
                            <i class="bi bi-tablet"></i> Tablet
                        </button>
                        <button class="device-nav-btn" data-device="phone">
                            <i class="bi bi-phone"></i> Mobile
                        </button>
                        <button class="device-nav-btn" data-device="watch">
                            <i class="bi bi-smartwatch"></i> Watch
                        </button>
                    </div>

                    <!-- Rendered Simulator Container -->
                    <div class="device-mockup-wrapper">
                        <!-- Active device class will wrap this -->
                        <div id="deviceMockupFrame" class="device-mockup-laptop">
                            <div class="mockup-screen-content" id="mockupScreenContent">
                                <!-- Simulated interface content loaded dynamically -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Responsive Subtitle Info -->
                    <div class="text-center mt-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 mb-2" id="deviceFeatureTag">
                            <i class="bi bi-lightning-fill"></i> Full Admin Control
                        </span>
                        <p class="small text-muted mb-0" id="deviceFeatureDesc">
                            Access the complete attendance ledger, configure departments, manage faculty registers, and download overall reports in real-time.
                        </p>
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
            <a href="<?php echo $base_path; ?>modules/authentication/login.php" class="btn-premium px-5 py-3 fs-5" id="cta-login-btn">
                Access Portal Login <i class="bi bi-box-arrow-in-right"></i>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
