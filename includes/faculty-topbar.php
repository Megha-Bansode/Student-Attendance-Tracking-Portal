<?php
/**
 * AttendEase - Faculty Portal Shared Topbar
 * Reusable header component for all Faculty Module pages
 */
$topbar_title = isset($page_title) ? $page_title : 'Faculty Dashboard';
$topbar_breadcrumb = isset($page_breadcrumb) ? $page_breadcrumb : 'AttendEase / Faculty';
?>
<!-- Faculty Topbar -->
<header class="faculty-topbar">
    
    <!-- Left Section: Mobile Toggler & Page Headings -->
    <div class="faculty-topbar-left">
        <button type="button" class="faculty-sidebar-toggler" id="facultySidebarToggler" aria-label="Toggle navigation menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h1 class="faculty-page-title"><?php echo htmlspecialchars($topbar_title); ?></h1>
            <p class="faculty-page-breadcrumb"><?php echo htmlspecialchars($topbar_breadcrumb); ?></p>
        </div>
    </div>

    <!-- Right Section: Date Badge & Actions -->
    <div class="faculty-topbar-right">
        <div class="topbar-date-pill">
            <i class="bi bi-calendar3 text-info"></i>
            <span><?php echo date('D, M d, Y'); ?></span>
        </div>
        
        <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php" class="btn btn-sm btn-premium d-none d-sm-inline-flex" style="padding: 0.45rem 1.1rem; font-size: 0.825rem;">
            <i class="bi bi-plus-lg"></i> Mark Attendance
        </a>
    </div>

</header>
