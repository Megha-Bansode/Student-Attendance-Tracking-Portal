<?php
/**
 * AttendEase - Student Portal Sidebar
 * Reusable component strictly matching Admin & Faculty Sidebar design & layout structure
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="faculty-sidebar" id="facultySidebar" role="navigation" aria-label="Student Portal Navigation">

    <!-- Sidebar Toggle -->
    <div class="faculty-sidebar-header" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem; display: flex; justify-content: flex-start;">
        <button class="faculty-sidebar-toggler" id="facultySidebarToggler" aria-label="Toggle sidebar menu">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Student Identity Card -->
    <div class="faculty-profile-card">
        <div class="faculty-avatar" aria-hidden="true" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: rgba(245, 158, 11, 0.4);">AM</div>
        <div class="faculty-profile-info">
            <p class="faculty-profile-name" title="Aarav Mehta">Aarav Mehta</p>
            <p class="faculty-profile-dept"><i class="bi bi-person-fill-badge me-1"></i> Roll: 125UAM1134</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="faculty-nav-menu" role="list">

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/dashboard/student-dashboard.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-dashboard.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-dashboard.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-speedometer2"></i>
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
            <a href="<?php echo $base_path; ?>modules/dashboard/faculty-dashboard.php" class="faculty-nav-link">
                <i class="bi bi-person-workspace text-info"></i>
                <span>Faculty View</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/attendance/student-daily-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-daily-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-daily-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-calendar-event"></i>
                <span>Daily Attendance</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/attendance/student-subject-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-subject-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-subject-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-journal-text"></i>
                <span>Subject-wise Attendance</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>modules/attendance/student-attendance-percentage.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-attendance-percentage.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-attendance-percentage.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-percent"></i>
                <span>Attendance Percentage</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>reports/student-download-report.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-download-report.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-download-report.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                <span>Download Report</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="<?php echo $base_path; ?>notifications/student-notifications.php"
               class="faculty-nav-link <?php echo ($current_page === 'student-notifications.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'student-notifications.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-bell-fill"></i>
                <span>Notifications</span>
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
