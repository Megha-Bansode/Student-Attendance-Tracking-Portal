<?php
/**
 * AttendEase - Super Admin Reports & Analytics
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
$page_title       = 'Reports & Compliance Analytics';
$page_breadcrumb  = 'AttendEase / Super Admin / Reports';
$page_icon        = 'bi-bar-chart-line-fill';

include '../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">

<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <div class="faculty-portal-wrapper">

        <!-- Super Admin Sidebar -->
        <?php include '../includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Band -->
            <?php include '../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">124</div>
                                <div class="faculty-stat-lbl">Reports Generated</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="faculty-stat-val">92.4%</div>
                                <div class="faculty-stat-lbl">Monthly Institution Avg</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-check-all"></i></div>
                            <div>
                                <div class="faculty-stat-val">100%</div>
                                <div class="faculty-stat-lbl">UGC Compliance</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-download"></i></div>
                            <div>
                                <div class="faculty-stat-val">CSV &amp; PDF</div>
                                <div class="faculty-stat-lbl">Export Formats</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Export Cards Grid -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 p-3 text-white" style="background:linear-gradient(135deg,#2563eb,#6366f1);"><i class="bi bi-people-fill fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold text-white mb-0 font-outfit">Student Attendance Report</h5>
                                    <small style="color:#94a3b8;">Detailed student presence records</small>
                                </div>
                            </div>
                            <p style="color:#cbd5e1; font-size:.85rem;">Generate class-wise and subject-wise student attendance reports with percentage analysis and shortage alerts.</p>
                            <div class="d-flex gap-2 mt-auto">
                                <button class="btn btn-sm btn-premium flex-grow-1" onclick="alert('Exporting PDF Student Report...')"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</button>
                                <button class="btn btn-sm btn-outline-light flex-grow-1" onclick="alert('Exporting CSV Student Data...')"><i class="bi bi-file-earmark-excel me-1"></i> CSV</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 p-3 text-white" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="bi bi-person-badge-fill fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold text-white mb-0 font-outfit">Faculty Performance Report</h5>
                                    <small style="color:#94a3b8;">Lecture delivery and submission speed</small>
                                </div>
                            </div>
                            <p style="color:#cbd5e1; font-size:.85rem;">Audit faculty class delivery punctuality, lecture completion rate, and attendance submission timeliness.</p>
                            <div class="d-flex gap-2 mt-auto">
                                <button class="btn btn-sm btn-premium flex-grow-1" onclick="alert('Exporting PDF Faculty Report...')"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</button>
                                <button class="btn btn-sm btn-outline-light flex-grow-1" onclick="alert('Exporting CSV Faculty Data...')"><i class="bi bi-file-earmark-excel me-1"></i> CSV</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 p-3 text-white" style="background:linear-gradient(135deg,#059669,#10b981);"><i class="bi bi-building fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold text-white mb-0 font-outfit">Department Monthly Report</h5>
                                    <small style="color:#94a3b8;">Inter-departmental comparison</small>
                                </div>
                            </div>
                            <p style="color:#cbd5e1; font-size:.85rem;">Comparative monthly analytics comparing attendance trends across all active engineering departments.</p>
                            <div class="d-flex gap-2 mt-auto">
                                <button class="btn btn-sm btn-premium flex-grow-1" onclick="alert('Exporting PDF Department Report...')"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</button>
                                <button class="btn btn-sm btn-outline-light flex-grow-1" onclick="alert('Exporting CSV Department Data...')"><i class="bi bi-file-earmark-excel me-1"></i> CSV</button>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
