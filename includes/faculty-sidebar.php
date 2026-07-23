<?php
/**
 * AttendEase - Faculty Portal Sidebar
 * Additive reusable component — does not modify any existing includes
 * Pages are at root level (faculty-dashboard.php, etc.)
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="faculty-sidebar" id="facultySidebar" role="navigation" aria-label="Faculty Portal Navigation">

    <!-- Sidebar Toggle -->
    <div class="faculty-sidebar-header" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem; display: flex; justify-content: flex-start;">
        <button class="faculty-sidebar-toggler" id="facultySidebarToggler" aria-label="Toggle sidebar menu">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <?php
    $display_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Prof. Rajesh Sharma';
    $display_dept = isset($_SESSION['department']) ? $_SESSION['department'] : 'Computer Engineering';
    $initials = '';
    if (!empty($display_name)) {
        // Strip prefixes like "Prof." or "Dr." for clean initials calculation
        $calc_name = $display_name;
        if (strpos(strtolower($calc_name), 'prof.') === 0) {
            $calc_name = trim(substr($calc_name, 5));
        } elseif (strpos(strtolower($calc_name), 'dr.') === 0) {
            $calc_name = trim(substr($calc_name, 3));
        }
        $parts = explode(' ', $calc_name);
        if (count($parts) >= 2) {
            $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        } else {
            $initials = strtoupper(substr($calc_name, 0, 2));
        }
    } else {
        $initials = 'RS';
    }
    ?>
    <!-- Faculty Identity Card -->
    <div class="faculty-profile-card">
        <div class="faculty-avatar" aria-hidden="true"><?php echo htmlspecialchars($initials); ?></div>
        <div class="faculty-profile-info">
            <p class="faculty-profile-name" title="<?php echo htmlspecialchars($display_name); ?>"><?php echo htmlspecialchars($display_name); ?></p>
            <p class="faculty-profile-dept"><i class="bi bi-building me-1"></i> <?php echo htmlspecialchars($display_dept); ?></p>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="faculty-nav-menu" role="list">

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/dashboard/faculty-dashboard.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-dashboard.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-dashboard.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/dashboard/admin-dashboard.php" class="faculty-nav-link">
                <i class="bi bi-shield-check text-primary"></i>
                <span>Admin View</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/dashboard/student-dashboard.php" class="faculty-nav-link">
                <i class="bi bi-mortarboard-fill text-warning"></i>
                <span>Student View</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/subjects/faculty-subject-allocation.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-subject-allocation.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-subject-allocation.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-diagram-3-fill"></i>
                <span>Subject Allocation</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-mark-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-mark-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-check-circle-fill"></i>
                <span>Mark Attendance</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/attendance/faculty-edit-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-edit-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-edit-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-pencil-square"></i>
                <span>Edit Attendance</span>
            </a>
        </li>

    </ul>

    <!-- Sign Out -->
    <div class="faculty-sidebar-footer">
        <a href="<?php echo $base_path; ?>modules/authentication/login.php?logout=1" class="faculty-logout-btn" title="Return to Login">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </div>

</aside>
