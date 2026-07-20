<?php include 'includes/header.php'; ?>

<!-- Custom Modular Styles & Scripts for Split Login (Zero impact on index.php) -->
<link rel="stylesheet" href="assets/css/login.css">

<!-- Scoped Split Login Section -->
<section class="split-login-section">

    <!-- Interactive Motion Dots / Starfield Canvas Background -->
    <canvas id="loginInteractiveBgCanvas"></canvas>

    <!-- Toast Alert Notification -->
    <div class="login-toast-notification" id="loginToastNotification">
        <i class="bi bi-check-circle-fill"></i>
        <span id="loginToastMsg">Credentials auto-filled!</span>
    </div>

    <!-- Main Split Login Container Card -->
    <div class="split-login-card">

        <!-- LEFT PANEL: Dark Creative Interactive Section -->
        <div class="split-left-panel">
            <!-- Animated Ambient Glow Blobs -->
            <div class="left-panel-orb left-panel-orb-1"></div>
            <div class="left-panel-orb left-panel-orb-2"></div>
            <div class="left-panel-orb left-panel-orb-3"></div>

            <div class="left-panel-content">
                <!-- Brand Header -->
                <div class="panel-brand-header">
                    <div class="brand-icon-box">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <div class="brand-text-title">Student Attendance</div>
                        <div class="brand-text-subtitle">Tracking Portal</div>
                    </div>
                </div>

                <!-- Main Hero Headline -->
                <h1 class="panel-hero-title">
                    Manage Attendance <br>
                    <span class="gradient-title-accent">Effortlessly</span>
                </h1>

                <!-- Subtitle Description -->
                <p class="panel-hero-desc">
                    A comprehensive college management system for tracking attendance, managing departments, courses, faculty, and students.
                </p>

                <!-- Interactive Floating Pill Badges -->
                <div class="panel-pills-wrapper">
                    <span class="panel-pill"><i class="bi bi-diagram-3-fill"></i> Department Management</span>
                    <span class="panel-pill"><i class="bi bi-person-plus-fill"></i> Student Registration</span>
                    <span class="panel-pill"><i class="bi bi-person-workspace"></i> Faculty Portal</span>
                    <span class="panel-pill"><i class="bi bi-bar-chart-line-fill"></i> Attendance Reports</span>
                    <span class="panel-pill"><i class="bi bi-shield-check"></i> Role-Based Access</span>
                </div>
            </div>

            <!-- Bottom Stats Counter Row -->
            <div class="panel-stats-row">
                <div>
                    <div class="stat-item-val">1,340+</div>
                    <div class="stat-item-lbl">Students</div>
                </div>
                <div>
                    <div class="stat-item-val">70+</div>
                    <div class="stat-item-lbl">Faculty</div>
                </div>
                <div>
                    <div class="stat-item-val">7</div>
                    <div class="stat-item-lbl">Departments</div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: White Compact Login Container -->
        <div class="split-right-panel">

            <!-- Interactive Role Selector Tabs (Swapped: Student first, Super Admin third) -->
            <div class="role-pills-container">
                <button type="button" class="role-pill-btn active" data-role="student">
                    <i class="bi bi-mortarboard-fill"></i> Student
                </button>
                <button type="button" class="role-pill-btn" data-role="faculty">
                    <i class="bi bi-briefcase-fill"></i> Faculty
                </button>
                <button type="button" class="role-pill-btn" data-role="admin">
                    <i class="bi bi-shield-check"></i> Super Admin
                </button>
            </div>

            <!-- Active Role Tag & Form Header (Default Student) -->
            <div>
                <span class="role-badge-tag" id="roleBadgeTag">
                    <i class="bi bi-mortarboard-fill me-1"></i> STUDENT PORTAL
                </span>
                <h2 class="form-header-title">Welcome back 👋</h2>
                <p class="form-header-subtitle">Sign in to access the management portal.</p>
            </div>

            <!-- Demo Credentials Interactive Box (Default Student) -->
            <div class="demo-banner-box">
                <span class="demo-banner-text" id="demoBannerText">Demo: 20241004 / Student@123</span>
                <button type="button" class="btn-autofill-demo" id="btnAutofillDemo" title="Click to auto-fill input fields">
                    <i class="bi bi-magic"></i> Auto-fill
                </button>
            </div>

            <!-- Form Content Tabs -->
            <div class="tab-content">

                <!-- 1. STUDENT FORM (DEFAULT) -->
                <div class="tab-fade-pane active" id="pane-student">
                    <form action="#" method="POST" class="split-login-form" id="form-student-login">
                        <div class="compact-form-group">
                            <label for="studentPrn" class="compact-label">PRN / Roll Number</label>
                            <div class="input-with-icon">
                                <i class="bi bi-hash input-icon-left"></i>
                                <input type="text" class="custom-compact-input" id="studentPrn" placeholder="20241004" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="#" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" class="custom-compact-input" id="studentPassword" placeholder="••••••••" required>
                                <button type="button" class="btn-toggle-eye" data-target="studentPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="rememberStudent" checked>
                                <label class="form-check-label custom-check-lbl" for="rememberStudent">
                                    Remember me for 30 days
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-split-submit" id="btn-student-submit">
                            Sign In <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- 2. FACULTY FORM -->
                <div class="tab-fade-pane" id="pane-faculty">
                    <form action="#" method="POST" class="split-login-form" id="form-faculty-login">
                        <div class="compact-form-group">
                            <label for="facultyId" class="compact-label">Faculty ID / Email</label>
                            <div class="input-with-icon">
                                <i class="bi bi-briefcase-fill input-icon-left"></i>
                                <input type="text" class="custom-compact-input" id="facultyId" placeholder="faculty@college.edu.in" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="#" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" class="custom-compact-input" id="facultyPassword" placeholder="••••••••" required>
                                <button type="button" class="btn-toggle-eye" data-target="facultyPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="rememberFaculty" checked>
                                <label class="form-check-label custom-check-lbl" for="rememberFaculty">
                                    Remember me for 30 days
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-split-submit" id="btn-faculty-submit">
                            Sign In <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- 3. ADMIN FORM -->
                <div class="tab-fade-pane" id="pane-admin">
                    <form action="#" method="POST" class="split-login-form" id="form-admin-login">
                        <div class="compact-form-group">
                            <label for="adminEmail" class="compact-label">Email Address</label>
                            <div class="input-with-icon">
                                <i class="bi bi-envelope-fill input-icon-left"></i>
                                <input type="email" class="custom-compact-input" id="adminEmail" placeholder="admin@college.edu.in" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="#" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" class="custom-compact-input" id="adminPassword" placeholder="••••••••" required>
                                <button type="button" class="btn-toggle-eye" data-target="adminPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="rememberAdmin" checked>
                                <label class="form-check-label custom-check-lbl" for="rememberAdmin">
                                    Remember me for 30 days
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-split-submit" id="btn-admin-submit">
                            Sign In <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>

            </div>

            <!-- Footer Link back home -->
            <div class="split-form-footer">
                Protected by role-based access control • <a href="index.php" id="login-back-home">Home</a>
            </div>

        </div>

    </div>

</section>

<!-- Scoped Interactivity JavaScript -->
<script src="assets/js/login.js"></script>

<?php include 'includes/footer.php'; ?>
