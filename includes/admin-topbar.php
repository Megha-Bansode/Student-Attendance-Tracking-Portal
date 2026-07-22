<?php
/**
 * AttendEase - Super Admin Shared Topbar Band
 * Reusable page header title band for Super Admin module
 * Clean layout with title, breadcrumbs, and date pill (No duplicate buttons)
 */
$topbar_title = isset($page_title) ? $page_title : 'Super Admin Dashboard';
$topbar_breadcrumb = isset($page_breadcrumb) ? $page_breadcrumb : 'AttendEase / Super Admin / Overview';
?>
<!-- Super Admin Page Title Band -->
<div class="faculty-page-band">
    <div class="d-flex align-items-center flex-wrap gap-2">
        <div>
            <h1 class="faculty-page-band-title">
                <i class="<?php echo isset($page_icon) ? $page_icon : 'bi bi-shield-check'; ?>"></i> <?php echo htmlspecialchars($topbar_title); ?>
            </h1>
            <p class="faculty-page-band-breadcrumb"><?php echo htmlspecialchars($topbar_breadcrumb); ?></p>
        </div>
    </div>
    <div class="faculty-page-band-right">
        <div class="topbar-date-pill">
            <i class="bi bi-calendar3"></i>
            <span><?php echo date('D, M d, Y'); ?></span>
        </div>
    </div>
</div>
