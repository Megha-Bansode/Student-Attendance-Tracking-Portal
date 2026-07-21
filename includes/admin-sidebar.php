<?php
/**
 * AttendEase - Super Admin Sidebar
 * Reusable component strictly matching Faculty Sidebar design & layout structure
 * No style overlapping with fixed top navbar
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="faculty-sidebar" id="facultySidebar" role="navigation" aria-label="Super Admin Portal Navigation">

    <!-- Sidebar Toggle -->
    <div class="faculty-sidebar-header" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem; display: flex; justify-content: flex-start;">
        <button class="faculty-sidebar-toggler" id="facultySidebarToggler" aria-label="Toggle sidebar menu">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Super Admin Identity Card -->
    <div class="faculty-profile-card">
        <div class="faculty-avatar" aria-hidden="true" style="background: linear-gradient(135deg, #2563eb 0%, #6366f1 100%); border-color: rgba(96, 165, 250, 0.4);">SA</div>
        <div class="faculty-profile-info">
            <p class="faculty-profile-name" title="Super Administrator">Super Admin</p>
            <p class="faculty-profile-dept"><i class="bi bi-shield-check me-1 text-info"></i> System Admin</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="faculty-nav-menu" role="list">

        <li class="faculty-nav-item">
            <a href="admin-dashboard.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-dashboard.php' || $current_page === 'admin.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-dashboard.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-departments.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-departments.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-departments.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-building"></i>
                <span>Departments</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-courses.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-courses.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-courses.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-book"></i>
                <span>Courses</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-subjects.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-subjects.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-subjects.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Subjects</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-faculty.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-faculty.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-faculty.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-person-badge"></i>
                <span>Faculty Directory</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-students.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-students.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-students.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-people-fill"></i>
                <span>Students Directory</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-calendar-check-fill"></i>
                <span>Attendance Audit</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-reports.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-reports.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-reports.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-bar-chart-line-fill"></i>
                <span>Reports &amp; Analytics</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-settings.php"
               class="faculty-nav-link <?php echo ($current_page === 'admin-settings.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'admin-settings.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-gear-fill"></i>
                <span>System Settings</span>
            </a>
        </li>

    </ul>

    <!-- Sign Out Footer -->
    <div class="faculty-sidebar-footer">
        <a href="login.php" class="faculty-logout-btn" title="Return to Login">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </div>

</aside>
