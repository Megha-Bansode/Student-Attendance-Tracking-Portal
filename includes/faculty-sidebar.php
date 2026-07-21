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

    <!-- Faculty Identity Card -->
    <div class="faculty-profile-card">
        <div class="faculty-avatar" aria-hidden="true">RS</div>
        <div class="faculty-profile-info">
            <p class="faculty-profile-name" title="Prof. Rajesh Sharma">Prof. Rajesh Sharma</p>
            <p class="faculty-profile-dept"><i class="bi bi-building me-1"></i> Computer Engineering</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="faculty-nav-menu" role="list">

        <li class="faculty-nav-item">
            <a href="faculty-dashboard.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-dashboard.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-dashboard.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="faculty-subject-allocation.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-subject-allocation.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-subject-allocation.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-diagram-3-fill"></i>
                <span>Subject Allocation</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="faculty-mark-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-mark-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-mark-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-check-circle-fill"></i>
                <span>Mark Attendance</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="faculty-edit-attendance.php"
               class="faculty-nav-link <?php echo ($current_page === 'faculty-edit-attendance.php') ? 'active' : ''; ?>"
               <?php echo ($current_page === 'faculty-edit-attendance.php') ? 'aria-current="page"' : ''; ?>>
                <i class="bi bi-pencil-square"></i>
                <span>Edit Attendance</span>
            </a>
        </li>

        <li class="faculty-nav-item">
            <a href="admin-dashboard.php"
               class="faculty-nav-link">
                <i class="bi bi-shield-check"></i>
                <span>Super Admin</span>
            </a>
        </li>

    </ul>

    <!-- Sign Out -->
    <div class="faculty-sidebar-footer">
        <a href="login.php" class="faculty-logout-btn" title="Return to Login">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
        </a>
    </div>

</aside>
