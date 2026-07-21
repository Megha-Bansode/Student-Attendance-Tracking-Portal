<?php
/**
 * AttendEase - Super Admin System Settings
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
$page_title       = 'System Settings';
$page_breadcrumb  = 'AttendEase / Super Admin / Settings';
$page_icon        = 'bi-gear-fill';

include 'includes/header.php';
?>
<link rel="stylesheet" href="assets/css/faculty.css">

<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <div class="faculty-portal-wrapper">

        <!-- Super Admin Sidebar -->
        <?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Band -->
            <?php include 'includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="faculty-card mb-4">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-sliders" style="color:#60a5fa;"></i> Institution Profile &amp; Portal Settings</h3>
                            </div>
                            <form onsubmit="event.preventDefault(); alert('System settings saved successfully!');">
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">Portal Title &amp; Institution Branding</label>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" value="AttendEase - Student Attendance Tracking Portal">
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-slate-300 font-semibold">Current Academic Year</label>
                                        <select class="form-select bg-dark text-white border-secondary">
                                            <option selected>2026 - 2027 (Current Active)</option>
                                            <option>2025 - 2026</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-slate-300 font-semibold">Minimum Attendance Threshold (%)</label>
                                        <input type="number" class="form-control bg-dark text-white border-secondary" value="75">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">Attendance Edit Window (Hours)</label>
                                    <input type="number" class="form-control bg-dark text-white border-secondary" value="48">
                                    <small style="color:#64748b;">Faculty members can edit submitted attendance up to 48 hours without admin approval.</small>
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-premium px-4"><i class="bi bi-save me-1"></i> Save Portal Settings</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="faculty-card mb-4">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-database-fill-gear" style="color:#34d399;"></i> Database &amp; Security</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 rounded-3" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);">
                                    <h6 class="fw-bold text-white mb-1"><i class="bi bi-shield-check text-success me-1"></i> Automated DB Backup</h6>
                                    <p class="small mb-2" style="color:#94a3b8;">Last backup executed today at 04:00 AM.</p>
                                    <button class="btn btn-sm btn-outline-light w-full" onclick="alert('Creating manual DB backup...')"><i class="bi bi-cloud-arrow-up me-1"></i> Create Manual Backup</button>
                                </div>

                                <div class="p-3 rounded-3 mt-3" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);">
                                    <h6 class="fw-bold text-white mb-1"><i class="bi bi-key-fill text-warning me-1"></i> Security Encryption</h6>
                                    <p class="small mb-0" style="color:#94a3b8;">AES-256 Bit SSL/TLS Data Encryption is Active across all API endpoints.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
