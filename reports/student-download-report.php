<?php
/**
 * AttendEase - Student Download Report
 */
$page_title  = 'Download Report';
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

        <!-- Sidebar -->
        <?php include '../includes/student-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Report
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Reports</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Filters -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-sliders" style="color: #f59e0b;"></i> Customize Report Criteria</h3>
                    </div>
                    <div class="p-3">
                        <form id="reportFilterForm" class="row g-3">
                            <div class="col-md-3">
                                <label for="reportSem" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Semester</label>
                                <select class="form-select" id="reportSem" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                    <option value="5">Semester V (Current)</option>
                                    <option value="4">Semester IV</option>
                                    <option value="3">Semester III</option>
                                    <option value="2">Semester II</option>
                                    <option value="1">Semester I</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="reportSubject" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Subject</label>
                                <select class="form-select" id="reportSubject" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                    <option value="all">All Subjects</option>
                                    <option value="CS501">CS501 - Data Structures</option>
                                    <option value="CS502">CS502 - DBMS</option>
                                    <option value="CS503">CS503 - Web Tech Lab</option>
                                    <option value="CS504">CS504 - OOP</option>
                                    <option value="CS505">CS505 - Software Eng.</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="reportDateStart" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Start Date</label>
                                <input type="date" class="form-control" id="reportDateStart" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                            </div>
                            <div class="col-md-3">
                                <label for="reportDateEnd" class="form-label text-light-subtitle" style="font-size: 0.85rem;">End Date</label>
                                <input type="date" class="form-control" id="reportDateEnd" value="<?php echo date('Y-m-d'); ?>" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" id="btnSearchReport" class="btn btn-premium px-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;"><i class="bi bi-search me-1"></i> Generate Preview</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Table Container -->
                <div class="faculty-card mb-4" id="reportResultCard">
                    <div class="faculty-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-file-earmark-spreadsheet" style="color: #f59e0b;"></i> Report Preview Summary</h3>
                            <p class="faculty-card-subtitle">Generated preview for Aarav Mehta (125UAM1134)</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-danger px-3 py-2" id="btnDownloadPDF" style="font-size: 0.8rem;"><i class="bi bi-file-pdf-fill me-1"></i> Export PDF</button>
                            <button class="btn btn-sm btn-outline-success px-3 py-2" id="btnDownloadExcel" style="font-size: 0.8rem;"><i class="bi bi-file-excel-fill me-1"></i> Export Excel</button>
                        </div>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Conducted</th>
                                    <th>Attended</th>
                                    <th>Absent</th>
                                    <th>Current %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CS501</strong></td>
                                    <td>Data Structures &amp; Algorithms</td>
                                    <td>50</td>
                                    <td>44</td>
                                    <td>6</td>
                                    <td><span class="badge bg-success-subtle text-success">88.0%</span></td>
                                </tr>
                                <tr>
                                    <td><strong>CS502</strong></td>
                                    <td>Database Management Systems</td>
                                    <td>50</td>
                                    <td>40</td>
                                    <td>10</td>
                                    <td><span class="badge bg-success-subtle text-success">80.0%</span></td>
                                </tr>
                                <tr>
                                    <td><strong>CS503</strong></td>
                                    <td>Web Technology Lab</td>
                                    <td>40</td>
                                    <td>28</td>
                                    <td>12</td>
                                    <td><span class="badge bg-danger-subtle text-danger">70.0%</span></td>
                                </tr>
                                <tr>
                                    <td><strong>CS504</strong></td>
                                    <td>Object-Oriented Programming</td>
                                    <td>40</td>
                                    <td>34</td>
                                    <td>6</td>
                                    <td><span class="badge bg-success-subtle text-success">85.0%</span></td>
                                </tr>
                                <tr>
                                    <td><strong>CS505</strong></td>
                                    <td>Software Engineering</td>
                                    <td>20</td>
                                    <td>19</td>
                                    <td>1</td>
                                    <td><span class="badge bg-success-subtle text-success">95.0%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Simple interactive toast handler script for mock download triggers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSearch = document.getElementById('btnSearchReport');
    const pdfBtn = document.getElementById('btnDownloadPDF');
    const excelBtn = document.getElementById('btnDownloadExcel');

    btnSearch.addEventListener('click', function() {
        btnSearch.disabled = true;
        btnSearch.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Updating...`;
        setTimeout(() => {
            btnSearch.disabled = false;
            btnSearch.innerHTML = `<i class="bi bi-search me-1"></i> Generate Preview`;
            if (window.showFacultyToast) {
                showFacultyToast('Report preview refreshed successfully.', 'success');
            } else {
                alert('Report preview refreshed successfully.');
            }
        }, 600);
    });

    pdfBtn.addEventListener('click', function() {
        if (window.showFacultyToast) {
            showFacultyToast('Preparing PDF download for semester V report...', 'info');
            setTimeout(() => {
                showFacultyToast('PDF Report downloaded successfully.', 'success');
            }, 1000);
        } else {
            alert('PDF download started.');
        }
    });

    excelBtn.addEventListener('click', function() {
        if (window.showFacultyToast) {
            showFacultyToast('Preparing Excel sheet download...', 'info');
            setTimeout(() => {
                showFacultyToast('Excel sheet downloaded successfully.', 'success');
            }, 1000);
        } else {
            alert('Excel download started.');
        }
    });
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
