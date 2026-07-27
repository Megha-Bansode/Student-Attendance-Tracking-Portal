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
                                <button class="btn btn-sm btn-premium flex-grow-1" data-bs-toggle="modal" data-bs-target="#exportClassReportModal" onclick="setExportFormat('pdf')"><i class="bi bi-file-earmark-pdf me-1"></i> PDF</button>
                                <button class="btn btn-sm btn-outline-light flex-grow-1" data-bs-toggle="modal" data-bs-target="#exportClassReportModal" onclick="setExportFormat('csv')"><i class="bi bi-file-earmark-excel me-1"></i> CSV</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 p-3 text-white" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="bi bi-graph-up-arrow fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold text-white mb-0 font-outfit">Monthly Analytics</h5>
                                    <small style="color:#94a3b8;">Institution-wide monthly trends</small>
                                </div>
                            </div>
                            <p style="color:#cbd5e1; font-size:.85rem;">Audit monthly attendance performance across the entire institution, visualizing high-risk periods.</p>
                            <div class="d-flex gap-2 mt-auto">
                                <a href="monthly-report.php" class="btn btn-sm btn-premium flex-grow-1"><i class="bi bi-file-earmark-bar-graph me-1"></i> View Report</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 p-3 text-white" style="background:linear-gradient(135deg,#059669,#10b981);"><i class="bi bi-building fs-4"></i></div>
                                <div>
                                    <h5 class="fw-bold text-white mb-0 font-outfit">Department Report</h5>
                                    <small style="color:#94a3b8;">Inter-departmental comparison</small>
                                </div>
                            </div>
                            <p style="color:#cbd5e1; font-size:.85rem;">Comparative monthly analytics comparing attendance trends across all active engineering departments.</p>
                            <div class="d-flex gap-2 mt-auto">
                                <a href="department-reports.php" class="btn btn-sm btn-premium flex-grow-1"><i class="bi bi-file-earmark-bar-graph me-1"></i> View Report</a>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Modal: Export Class-wise Student Attendance Report -->
<div class="modal fade" id="exportClassReportModal" tabindex="-1" aria-labelledby="exportClassReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="exportClassReportModalLabel"><i class="bi bi-file-earmark-arrow-down me-2 text-primary"></i>Export Class Attendance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin-download-class-report.php" method="GET" target="_blank" onsubmit="setTimeout(() => { bootstrap.Modal.getInstance(document.getElementById('exportClassReportModal')).hide(); }, 500);">
                <input type="hidden" name="format" id="export_format" value="pdf">
                
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Select Class</label>
                        <select name="class" class="form-select bg-dark text-white border-secondary" required>
                            <option value="First Year">First Year</option>
                            <option value="Second Year">Second Year</option>
                            <option value="Third Year">Third Year</option>
                            <option value="Fourth Year">Fourth Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Select Division</label>
                        <select name="division" class="form-select bg-dark text-white border-secondary" required>
                            <option value="all">All Divisions</option>
                            <option value="A">Division A</option>
                            <option value="B">Division B</option>
                            <option value="C">Division C</option>
                            <option value="D">Division D</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Download Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setExportFormat(format) {
    document.getElementById('export_format').value = format;
    const titleLabel = format === 'pdf' ? 'Export Class Attendance (PDF)' : 'Export Class Attendance (CSV)';
    document.getElementById('exportClassReportModalLabel').innerHTML = `<i class="bi bi-file-earmark-${format === 'pdf' ? 'pdf' : 'excel'} me-2 text-primary"></i> ${titleLabel}`;
}
</script>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
