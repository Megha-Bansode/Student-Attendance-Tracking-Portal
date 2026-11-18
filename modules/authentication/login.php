<?php
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $username = '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($role === 'student') {
        $username = isset($_POST['zprn']) ? trim($_POST['zprn']) : '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'student' AND (zprn = ? OR username = ?)");
        $stmt->execute([$username, $username]);
    } elseif ($role === 'faculty') {
        $username = isset($_POST['faculty_id']) ? trim($_POST['faculty_id']) : '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'faculty' AND (username = ? OR id = ?)");
        $stmt->execute([$username, $username]);
    } elseif ($role === 'admin') {
        $username = isset($_POST['email']) ? trim($_POST['email']) : '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' AND username = ?");
        $stmt->execute([$username]);
    }

    if (isset($stmt)) {
        $user = $stmt->fetch();
        if ($user) {
            if ($password === $user['password'] || password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['zprn'] = $user['zprn'];
                $_SESSION['class'] = $user['class'];
                $_SESSION['division'] = $user['division'];
                $_SESSION['department'] = $user['department'];

                if ($role === 'student') {
                    header("Location: ../dashboard/student-dashboard.php");
                } elseif ($role === 'faculty') {
                    header("Location: ../dashboard/faculty-dashboard.php");
                } elseif ($role === 'admin') {
                    header("Location: ../dashboard/admin-dashboard.php");
                }
                exit;
            } else {
                header("Location: login.php?error=wrong_password&role=" . urlencode($role));
                exit;
            }
        } else {
            header("Location: login.php?error=not_found&role=" . urlencode($role));
            exit;
        }
    }
}
include '../../includes/header.php';
?>

<!-- Custom Modular Styles & Scripts for Split Login (Zero impact on index.php) -->
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/forms.css">

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
        <div class="split-left-panel theme-student">
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

            <!-- Form Content Tabs -->
            <div class="tab-content">

                <!-- 1. STUDENT FORM (DEFAULT) -->
                <div class="tab-fade-pane active" id="pane-student">
                    <form action="<?php echo $base_path; ?>modules/authentication/login.php" method="POST" class="split-login-form" id="form-student-login">
                        <input type="hidden" name="role" value="student">
                        <div class="compact-form-group">
                            <label for="studentPrn" class="compact-label">ZPRN (Roll Number)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-hash input-icon-left"></i>
                                <input type="text" name="zprn" class="custom-compact-input" id="studentPrn" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="forgot-password.php?role=student" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" name="password" class="custom-compact-input" id="studentPassword" placeholder="Password" required>
                                <button type="button" class="btn-toggle-eye" data-target="studentPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberStudent" checked>
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
                    <form action="<?php echo $base_path; ?>modules/authentication/login.php" method="POST" class="split-login-form" id="form-faculty-login">
                        <input type="hidden" name="role" value="faculty">
                        <div class="compact-form-group">
                            <label for="facultyId" class="compact-label">Faculty ID / Email</label>
                            <div class="input-with-icon">
                                <i class="bi bi-briefcase-fill input-icon-left"></i>
                                <input type="text" name="faculty_id" class="custom-compact-input" id="facultyId" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="forgot-password.php?role=faculty" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" name="password" class="custom-compact-input" id="facultyPassword" placeholder="Password" required>
                                <button type="button" class="btn-toggle-eye" data-target="facultyPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberFaculty" checked>
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
                    <form action="<?php echo $base_path; ?>modules/authentication/login.php" method="POST" class="split-login-form" id="form-admin-login">
                        <input type="hidden" name="role" value="admin">
                        <div class="compact-form-group">
                            <label for="adminEmail" class="compact-label">Email Address</label>
                            <div class="input-with-icon">
                                <i class="bi bi-envelope-fill input-icon-left"></i>
                                <input type="text" name="email" class="custom-compact-input" id="adminEmail" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="compact-form-group">
                            <div class="compact-label">
                                <span>Password</span>
                                <a href="forgot-password.php?role=admin" class="forgot-link-text">Forgot password?</a>
                            </div>
                            <div class="input-with-icon">
                                <i class="bi bi-lock-fill input-icon-left"></i>
                                <input type="password" name="password" class="custom-compact-input" id="adminPassword" placeholder="Password" required>
                                <button type="button" class="btn-toggle-eye" data-target="adminPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options-row">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberAdmin" checked>
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
                Protected by role-based access control • <a href="<?php echo $base_path; ?>index.php" id="login-back-home">Home</a>
            </div>

        </div>

    </div>

</section>

<!-- Scoped Interactivity JavaScript -->
<script src="<?php echo $base_path; ?>assets/js/validation.js"></script>

<?php include '../../includes/footer.php'; ?>
