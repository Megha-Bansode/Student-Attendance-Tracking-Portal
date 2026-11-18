<?php
/**
 * reports/department-reports.php
 * Department-wise Reports page
 * Module: Attendance Reports & Trend Analysis
 */
include '../includes/header.php';
?>

<link rel="stylesheet" href="../assets/css/reports.css">

<div class="reports-page-wrapper">

    <!-- PAGE HERO -->
    <section class="reports-page-hero">
        <div class="container">
            <nav class="reports-breadcrumb" aria-label="breadcrumb">
                <a href="../index.php"><i class="bi bi-house-door"></i> Home</a>
                <span class="separator"><i class="bi bi-chevron-right"></i></span>
                <a href="index.php">Reports</a>
                <span class="separator"><i class="bi bi-chevron-right"></i></span>
                <span class="current">Department Reports</span>
            </nav>
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-diagram-3-fill"></i> Department Intelligence
                    </div>
                    <h1 class="reports-page-title">Department-wise Reports</h1>
                    <p class="reports-page-subtitle">
                        Compare attendance health, faculty coverage, and at-risk student counts across all departments in one view.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="index.php" class="btn-premium-outline">
                        <i class="bi bi-arrow-left"></i> Back to Reports
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container">

        <!-- FILTER PANEL -->
        <form class="filter-panel" id="report-filter-form" novalidate>
            <div class="filter-panel-title">
                <i class="bi bi-funnel-fill"></i> Filters &mdash; Refine Department Data
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-dept-dr">Department</label>
                    <select class="form-select report-form-select" id="filter-dept-dr">
                        <option value="">All</option>
                        <option>Computer Engineering</option>
                        <option>AI &amp; ML</option>
                        <option>Information Technology</option>
                        <option>ENTC</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-sem-dr">Semester</label>
                    <select class="form-select report-form-select" id="filter-sem-dr">
                        <option value="">All</option>
                        <option>Semester I</option>
                        <option>Semester II</option>
                        <option>Semester III</option>
                        <option>Semester IV</option>
                        <option>Semester V</option>
                        <option>Semester VI</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-year-dr">Academic Year</label>
                    <select class="form-select report-form-select" id="filter-year-dr">
                        <option>2024-25</option>
                        <option>2023-24</option>
                        <option>2022-23</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-date-from-dr">From Date</label>
                    <input type="date" class="form-control report-form-control" id="filter-date-from-dr">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-date-to-dr">To Date</label>
                    <input type="date" class="form-control report-form-control" id="filter-date-to-dr">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn-report-apply" id="btn-filter-apply">
                            <i class="bi bi-search"></i> Apply
                        </button>
                        <button type="button" class="btn-report-reset" id="btn-filter-reset">
                            <i class="bi bi-x-lg"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <button type="button" class="btn-report-export" id="btn-export-pdf">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </button>
                        <button type="button" class="btn-report-excel" id="btn-export-excel">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- DEPARTMENT CARDS -->
        <div class="section-divider mb-3">
            <span class="section-divider-label"><i class="bi bi-building me-1"></i>Department Overview</span>
            <div class="section-divider-line"></div>
        </div>

        <div class="row g-4 mb-4">

            <!-- Computer Engineering -->
            <div class="col-sm-6 col-xl-3">
                <div class="dept-card dept-ce">
                    <div class="dept-card-icon ce-icon">
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div class="dept-card-name">Comp. Engg.</div>
                    <div class="dept-card-full-name">Computer Engineering</div>
                    <div class="dept-attendance-ring">
                        <span class="dept-attendance-percent">88</span>
                        <span class="dept-attendance-label">% Attendance</span>
                    </div>
                    <div class="dept-meta-row">
                        <div class="dept-meta-item">
                            <i class="bi bi-people-fill" style="color:var(--primary-light);"></i>
                            <span>420 Students</span>
                        </div>
                        <div class="dept-meta-item">
                            <i class="bi bi-person-badge-fill" style="color:var(--accent-light);"></i>
                            <span>18 Faculty</span>
                        </div>
                    </div>
                    <div class="dept-progress-wrap">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;color:var(--text-muted);">
                            <span>Attendance</span><span>88%</span>
                        </div>
                        <div class="dept-progress">
                            <div class="dept-progress-bar ce-bar" data-width="88%" style="width:0;"></div>
                        </div>
                    </div>
                    <button class="btn-report-apply w-100 btn-view-report justify-content-center" id="btn-view-ce">
                        <i class="bi bi-eye"></i> View Report
                    </button>
                </div>
            </div>

            <!-- AI & ML -->
            <div class="col-sm-6 col-xl-3">
                <div class="dept-card dept-aiml">
                    <div class="dept-card-icon aiml-icon">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="dept-card-name">AI &amp; ML</div>
                    <div class="dept-card-full-name">Artificial Intelligence &amp; Machine Learning</div>
                    <div class="dept-attendance-ring">
                        <span class="dept-attendance-percent">92</span>
                        <span class="dept-attendance-label">% Attendance</span>
                    </div>
                    <div class="dept-meta-row">
                        <div class="dept-meta-item">
                            <i class="bi bi-people-fill" style="color:var(--accent-light);"></i>
                            <span>380 Students</span>
                        </div>
                        <div class="dept-meta-item">
                            <i class="bi bi-person-badge-fill" style="color:var(--primary-light);"></i>
                            <span>15 Faculty</span>
                        </div>
                    </div>
                    <div class="dept-progress-wrap">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;color:var(--text-muted);">
                            <span>Attendance</span><span>92%</span>
                        </div>
                        <div class="dept-progress">
                            <div class="dept-progress-bar aiml-bar" data-width="92%" style="width:0;"></div>
                        </div>
                    </div>
                    <button class="btn-report-apply w-100 btn-view-report justify-content-center" id="btn-view-aiml">
                        <i class="bi bi-eye"></i> View Report
                    </button>
                </div>
            </div>

            <!-- Information Technology -->
            <div class="col-sm-6 col-xl-3">
                <div class="dept-card dept-it">
                    <div class="dept-card-icon it-icon">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                    <div class="dept-card-name">Info Tech</div>
                    <div class="dept-card-full-name">Information Technology</div>
                    <div class="dept-attendance-ring">
                        <span class="dept-attendance-percent">85</span>
                        <span class="dept-attendance-label">% Attendance</span>
                    </div>
                    <div class="dept-meta-row">
                        <div class="dept-meta-item">
                            <i class="bi bi-people-fill" style="color:#a78bfa;"></i>
                            <span>360 Students</span>
                        </div>
                        <div class="dept-meta-item">
                            <i class="bi bi-person-badge-fill" style="color:var(--accent-light);"></i>
                            <span>14 Faculty</span>
                        </div>
                    </div>
                    <div class="dept-progress-wrap">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;color:var(--text-muted);">
                            <span>Attendance</span><span>85%</span>
                        </div>
                        <div class="dept-progress">
                            <div class="dept-progress-bar it-bar" data-width="85%" style="width:0;"></div>
                        </div>
                    </div>
                    <button class="btn-report-apply w-100 btn-view-report justify-content-center" id="btn-view-it">
                        <i class="bi bi-eye"></i> View Report
                    </button>
                </div>
            </div>

            <!-- ENTC -->
            <div class="col-sm-6 col-xl-3">
                <div class="dept-card dept-entc">
                    <div class="dept-card-icon entc-icon">
                        <i class="bi bi-broadcast-pin"></i>
                    </div>
                    <div class="dept-card-name">ENTC</div>
                    <div class="dept-card-full-name">Electronics &amp; Telecom Engineering</div>
                    <div class="dept-attendance-ring">
                        <span class="dept-attendance-percent">79</span>
                        <span class="dept-attendance-label">% Attendance</span>
                    </div>
                    <div class="dept-meta-row">
                        <div class="dept-meta-item">
                            <i class="bi bi-people-fill" style="color:#34d399;"></i>
                            <span>380 Students</span>
                        </div>
                        <div class="dept-meta-item">
                            <i class="bi bi-person-badge-fill" style="color:var(--primary-light);"></i>
                            <span>16 Faculty</span>
                        </div>
                    </div>
                    <div class="dept-progress-wrap">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;color:var(--text-muted);">
                            <span>Attendance</span><span>79%</span>
                        </div>
                        <div class="dept-progress">
                            <div class="dept-progress-bar entc-bar" data-width="79%" style="width:0;"></div>
                        </div>
                    </div>
                    <button class="btn-report-apply w-100 btn-view-report justify-content-center" id="btn-view-entc">
                        <i class="bi bi-eye"></i> View Report
                    </button>
                </div>
            </div>

        </div><!-- /row dept-cards -->

        <!-- DEPARTMENT TABLE + DOUGHNUT CHART -->
        <div class="row g-4 mb-5">

            <!-- Responsive Table -->
            <div class="col-lg-7">
                <div class="table-panel" style="height:100%;">
                    <div class="table-panel-header">
                        <h2 class="table-panel-title"><i class="bi bi-table me-2 text-muted" style="font-size:0.95rem;"></i>Department Summary</h2>
                        <div class="table-search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="dept-search" class="table-search-input" placeholder="Search departments...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table report-table" id="dept-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Department</th>
                                    <th>Students</th>
                                    <th>Faculty</th>
                                    <th>Attendance %</th>
                                    <th>At-Risk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 = [
                                    ['01', 'Computer Engineering',                 420, 18, 88, 12, 'excellent'],
                                    ['02', 'AI &amp; Machine Learning',            380, 15, 92,  6, 'excellent'],
                                    ['03', 'Information Technology',               360, 14, 85, 18, 'good'],
                                    ['04', 'Electronics &amp; Telecom (ENTC)', 380, 16, 79, 11, 'average'],
                                ];

                                 = [
                                    'excellent' => 'badge-excellent',
                                    'good'      => 'badge-good',
                                    'average'   => 'badge-average',
                                    'critical'  => 'badge-critical',
                                ];

                                foreach ( as ) {
                                     = [[6]] ?? 'badge-good';
                                           = ucfirst([6]);
                                             = [4];
                                    echo "<tr>";
                                    echo "  <td><span style='color:var(--text-muted);font-size:0.8rem;'>{[0]}</span></td>";
                                    echo "  <td><strong>{[1]}</strong></td>";
                                    echo "  <td>{[2]}</td>";
                                    echo "  <td>{[3]}</td>";
                                    echo "  <td>";
                                    echo "    <div class='d-flex align-items-center gap-2'>";
                                    echo "      <div style='width:50px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;'>";
                                    echo "        <div style='width:{}%;height:100%;background:linear-gradient(90deg,#6366f1,#0ea5e9);border-radius:50px;'></div>";
                                    echo "      </div>";
                                    echo "      <strong>{}%</strong>";
                                    echo "    </div>";
                                    echo "  </td>";
                                    echo "  <td><span style='color:var(--danger);font-weight:600;'>{[5]}</span></td>";
                                    echo "  <td><span class='status-badge {}'>{}</span></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <span id="table-row-count">Showing 4 records</span>
                        <nav aria-label="Dept table pagination">
                            <ul class="pagination report-pagination mb-0">
                                <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Doughnut Chart -->
            <div class="col-lg-5">
                <div class="chart-card" style="height:100%;">
                    <div class="chart-card-title">
                        <i class="bi bi-pie-chart-fill me-2" style="color:var(--primary-light);"></i>
                        Department Attendance Distribution
                    </div>
                    <div class="chart-card-subtitle">
                        Proportional view of attendance across all four departments.
                    </div>
                    <div class="chart-wrapper d-flex justify-content-center">
                        <canvas id="deptDoughnutChart" style="max-width:320px;max-height:320px;"></canvas>
                    </div>
                </div>
            </div>

        </div><!-- /row table+chart -->

    </div><!-- /container -->
</div><!-- /page-wrapper -->

<!-- Toast -->
<div class="report-toast" id="report-toast">
    <div class="report-toast-icon"><i class="bi bi-info-circle"></i></div>
    <div>
        <div class="report-toast-title" id="report-toast-title">Notice</div>
        <p class="report-toast-body" id="report-toast-body"></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- Include html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="../assets/js/reports.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pdfBtn = document.getElementById('btn-export-pdf');
    const excelBtn = document.getElementById('btn-export-excel');

    if (pdfBtn) {
        pdfBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const reportContainer = document.createElement('div');
            reportContainer.style.padding = '40px';
            reportContainer.style.fontFamily = 'system-ui, -apple-system, sans-serif';
            reportContainer.style.color = '#1e293b';
            reportContainer.style.backgroundColor = '#ffffff';

            reportContainer.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                    <div>
                        <h1 style="margin: 0; font-size: 24px; color: #0f172a; font-weight: 700;">AttendEase</h1>
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; text-transform: uppercase;">Department Reports</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: 12px; color: #64748b;">Report Date</p>
                        <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 600; color: #0f172a;">${new Date().toLocaleDateString()}</p>
                    </div>
                </div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
                    <thead>
                        <tr style="background-color: #0f172a; color: #ffffff;">
                            <th style="padding: 12px; text-align: left; font-size: 12px;">ID</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Department</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">HOD</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Students</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Attendance</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Defaulters</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Array.from(document.querySelectorAll('#dept-table tbody tr')).map(row => {
                            if (row.style.display === 'none') return '';
                            const cells = row.querySelectorAll('td');
                            if (cells.length < 7) return '';
                            return `
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 12px; font-size: 12px;">${cells[0].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[1].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[2].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[3].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[4].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[5].innerText}</td>
                                    <td style="padding: 12px; font-size: 12px;">${cells[6].innerText}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;

            const opt = {
                margin:       10,
                filename:     'Department_Report.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(reportContainer).save();
        });
    }

    if (excelBtn) {
        excelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "ID,Department,HOD,Students,Attendance,Defaulters,Status\r\n";
            
            Array.from(document.querySelectorAll('#dept-table tbody tr')).forEach(row => {
                if (row.style.display === 'none') return;
                const cells = row.querySelectorAll('td');
                if (cells.length < 7) return;
                
                const c0 = `"${cells[0].innerText.replace(/"/g, '""')}"`;
                const c1 = `"${cells[1].innerText.replace(/"/g, '""')}"`;
                const c2 = `"${cells[2].innerText.replace(/"/g, '""')}"`;
                const c3 = `"${cells[3].innerText.replace(/"/g, '""')}"`;
                const c4 = `"${cells[4].innerText.replace(/"/g, '""')}"`;
                const c5 = `"${cells[5].innerText.replace(/"/g, '""')}"`;
                const c6 = `"${cells[6].innerText.replace(/"/g, '""')}"`;
                
                csvContent += `${c0},${c1},${c2},${c3},${c4},${c5},${c6}\r\n`;
            });
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Department_Report.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    // Auto-export logic triggered from Dashboard
    const urlParams = new URLSearchParams(window.location.search);
    const autoExport = urlParams.get('auto_export');
    if (autoExport === 'pdf' && pdfBtn) {
        setTimeout(() => pdfBtn.click(), 500);
    } else if (autoExport === 'csv' && excelBtn) {
        setTimeout(() => excelBtn.click(), 500);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
