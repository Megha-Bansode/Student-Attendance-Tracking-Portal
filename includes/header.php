<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_index = ($current_page == 'index.php' || $current_page == '' || $current_page == '/');
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
    <title>Student Attendance Tracking Portal</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if (in_array($current_page, ['attendance-history.php', 'monthly-report.php', 'student-summary.php'])): ?>
    <link rel="stylesheet" href="assets/css/reports.css">
    <?php endif; ?>
</head>
<body>
    <!-- Background glow particles -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Reusable Navbar Header -->
    <header class="header-glass fixed-top py-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-0">
            <div class="container">
                <!-- Branding / Logo -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $is_index ? '#home' : 'index.php'; ?>" id="nav-brand">
                    <img src="assets/images/logo.svg" alt="Portal Logo" width="36" height="36">
                    <span>Attend<span class="brand-gradient">Ease</span></span>
                </a>
                
                <!-- Mobile Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Links -->
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
                            <a class="nav-link dropdown-toggle <?php echo in_array($current_page, ['attendance-history.php', 'monthly-report.php', 'student-summary.php']) ? 'active' : ''; ?>" href="#" id="navbarDropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bar-chart-line-fill text-primary"></i> Faculty Reports
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg" aria-labelledby="navbarDropdownReports">
                                <li><a class="dropdown-item <?php echo $current_page == 'attendance-history.php' ? 'active' : ''; ?>" href="attendance-history.php"><i class="bi bi-clock-history me-2"></i> Attendance History</a></li>
                                <li><a class="dropdown-item <?php echo $current_page == 'monthly-report.php' ? 'active' : ''; ?>" href="monthly-report.php"><i class="bi bi-calendar-month me-2"></i> Monthly Attendance Report</a></li>
                                <li><a class="dropdown-item <?php echo $current_page == 'student-summary.php' ? 'active' : ''; ?>" href="student-summary.php"><i class="bi bi-person-lines-fill me-2"></i> Student-wise Summary</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $contact_link; ?>" id="nav-link-contact">Contact</a>
                        </li>
                        <!-- Login Button -->
                        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                            <a href="login.php" class="btn-premium px-4" id="nav-login-btn">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
