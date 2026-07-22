<?php
// Calculate relative path to root dynamically
$root_dir = str_replace('\\', '/', realpath(dirname(__DIR__)));
$script_dir = str_replace('\\', '/', realpath(dirname($_SERVER['SCRIPT_FILENAME'])));
$base_path = '';
if ($script_dir !== $root_dir) {
    $diff = str_replace($root_dir, '', $script_dir);
    $parts = array_filter(explode('/', $diff));
    $base_path = str_repeat('../', count($parts));
}

$current_page = basename($_SERVER['PHP_SELF']);
$is_index = ($current_page == 'index.php' || $current_page == '' || $current_page == '/');
$is_admin = (strpos($current_page, 'admin-') === 0);
$is_faculty = (strpos($current_page, 'faculty-') === 0);
$is_student = (strpos($current_page, 'student-') === 0);
$is_portal = ($is_admin || $is_faculty || $is_student);
$is_logged_in = !($is_index || $current_page == 'login.php');

$home_link = $is_index ? '#home' : $base_path.'index.php#home';
$about_link = $is_index ? '#about' : $base_path.'index.php#about';
$features_link = $is_index ? '#features' : $base_path.'index.php#features';
$contact_link = $is_index ? '#contact' : $base_path.'index.php#contact';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Digitize, monitor, and automate student attendance with role-based dashboard access, automated alerts, and analytics.">
    <title>AttendEase — Student Attendance Tracking Portal</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
    <?php if (in_array($current_page, ['reports/attendance-history.php', 'reports/monthly-report.php', 'reports/student-summary.php'])): ?>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/reports.css">
    <?php endif; ?>
</head>
<body>
    <!-- Background glow particles -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Reusable Navbar Header -->
    <header class="header-glass fixed-top py-2">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-0">
            <div class="container-fluid px-lg-4">
                <!-- Branding / Logo -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $is_portal ? ($is_admin ? $base_path.'modules/dashboard/admin-dashboard.php' : ($is_faculty ? $base_path.'modules/dashboard/faculty-dashboard.php' : $base_path.'modules/dashboard/student-dashboard.php')) : $base_path.'index.php'; ?>" id="nav-brand">
                    <img src="<?php echo $base_path; ?>assets/images/logo/logo.svg" alt="Portal Logo" width="34" height="34">
                    <span class="fw-bold font-outfit" style="font-size:1.2rem; letter-spacing: -0.3px;">Attend<span class="brand-gradient">Ease</span></span>
                    <?php if ($is_admin): ?>
                        <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle ms-2 d-none d-sm-inline-block" style="font-size:0.72rem; font-weight:600; padding:0.3em 0.75em;">Super Admin Console</span>
                    <?php elseif ($is_faculty): ?>
                        <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle ms-2 d-none d-sm-inline-block" style="font-size:0.72rem; font-weight:600; padding:0.3em 0.75em;">Faculty Portal</span>
                    <?php elseif ($is_student): ?>
                        <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle ms-2 d-none d-sm-inline-block" style="font-size:0.72rem; font-weight:600; padding:0.3em 0.75em;">Student Portal</span>
                    <?php endif; ?>
                </a>
                
                <!-- Navigation Links / User Controls -->
                <div class="d-flex align-items-center ms-auto gap-3">
                    <?php if ($is_portal): ?>
                        <!-- Portal Topbar Controls -->
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success-subtle text-success d-none d-md-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-pill" style="font-size:0.75rem;">
                                <i class="bi bi-circle-fill" style="font-size:0.45rem;"></i> System Active
                            </span>
                        </div>
                    <?php else: ?>
                        <!-- Mobile Toggler -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="mainNavbar">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $is_index ? 'active' : ''; ?>" aria-current="page" href="<?php echo $home_link; ?>" id="nav-link-home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $about_link; ?>" id="nav-link-about">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $features_link; ?>" id="nav-link-features">Features</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['reports/attendance-history.php', 'reports/monthly-report.php', 'reports/student-summary.php']) ? 'active' : ''; ?>" href="#" id="navbarDropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-bar-chart-line-fill text-primary"></i> Faculty Reports
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" aria-labelledby="navbarDropdownReports">
                                        <li><a class="dropdown-item <?php echo $current_page == 'reports/attendance-history.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>reports/attendance-history.php"><i class="bi bi-clock-history me-2"></i> Attendance History</a></li>
                                        <li><a class="dropdown-item <?php echo $current_page == 'reports/monthly-report.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>reports/monthly-report.php"><i class="bi bi-calendar-month me-2"></i> Monthly Attendance Report</a></li>
                                        <li><a class="dropdown-item <?php echo $current_page == 'reports/student-summary.php' ? 'active' : ''; ?>" href="<?php echo $base_path; ?>reports/student-summary.php"><i class="bi bi-person-lines-fill me-2"></i> Student-wise Summary</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $contact_link; ?>" id="nav-link-contact">Contact</a>
                                </li>
                                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                                    <a href="<?php echo $base_path; ?>modules/authentication/login.php" class="btn-premium px-4" id="nav-login-btn">
                                        <i class="bi bi-box-arrow-in-right"></i> Login
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
                </div>
            </div>
        </nav>
    </header>
