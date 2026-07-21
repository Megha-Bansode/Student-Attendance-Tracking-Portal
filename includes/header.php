<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_index = ($current_page == 'index.php' || $current_page == '' || $current_page == '/');
$is_admin = (strpos($current_page, 'admin-') === 0);
$is_faculty = (strpos($current_page, 'faculty-') === 0);
$is_portal = ($is_admin || $is_faculty);
$is_logged_in = !($is_index || $current_page == 'login.php');

$home_link = $is_index ? '#home' : 'index.php#home';
$about_link = $is_index ? '#about' : 'index.php#about';
$features_link = $is_index ? '#features' : 'index.php#features';
$contact_link = $is_index ? '#contact' : 'index.php#contact';
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
    <link rel="stylesheet" href="assets/css/style.css">
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
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $is_portal ? ($is_admin ? 'admin-dashboard.php' : 'faculty-dashboard.php') : 'index.php'; ?>" id="nav-brand">
                    <img src="assets/images/logo.svg" alt="Portal Logo" width="34" height="34">
                    <span class="fw-bold font-outfit" style="font-size:1.2rem; letter-spacing: -0.3px;">Attend<span class="brand-gradient">Ease</span></span>
                    <?php if ($is_admin): ?>
                        <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle ms-2 d-none d-sm-inline-block" style="font-size:0.72rem; font-weight:600; padding:0.3em 0.75em;">Super Admin Console</span>
                    <?php elseif ($is_faculty): ?>
                        <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle ms-2 d-none d-sm-inline-block" style="font-size:0.72rem; font-weight:600; padding:0.3em 0.75em;">Faculty Portal</span>
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
                            <a href="login.php" class="btn btn-sm btn-outline-danger px-3 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" id="nav-logout-btn" style="font-size:0.82rem; font-weight:600;">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </a>
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
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $contact_link; ?>" id="nav-link-contact">Contact</a>
                                </li>
                                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                                    <a href="login.php" class="btn-premium px-4" id="nav-login-btn">
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
