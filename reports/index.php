<?php
/**
 * reports/index.php
 * Attendance Reports & Analytics Dashboard
 * Module: Attendance Reports & Trend Analysis
 */
include '../includes/header.php';
?>

<!-- Load module CSS -->
<link rel="stylesheet" href="../assets/css/reports.css">

<div class="reports-page-wrapper">

    <!-- ===================== PAGE HERO ===================== -->
    <section class="reports-page-hero">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="reports-breadcrumb" aria-label="breadcrumb">
                <a href="../index.php"><i class="bi bi-house-door"></i> Home</a>
                <span class="separator"><i class="bi bi-chevron-right"></i></span>
                <span class="current">Reports & Analytics</span>
            </nav>

            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-bar-chart-line"></i> Attendance Intelligence
                    </div>
                    <h1 class="reports-page-title">Attendance Reports &amp; Analytics</h1>
                    <p class="reports-page-subtitle">
                        Visualize attendance trends and generate meaningful reports across departments, subjects, and time periods.
                    </p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <span class="status-badge badge-active">
                        <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Live Data Ready
                    </span>
                </div>
            </div>
        </div>
    </section>
    <!-- =================== END HERO ======================= -->

    <div class="container">

        <!-- ============== KPI STAT CARDS ================= -->
        <div class="row g-4 mb-4">

            <!-- Overall Attendance -->
            <div class="col-sm-6 col-xl-3">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-primary">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="report-stat-value">86.4<span style="font-size:1.1rem;color:var(--text-muted);">%</span></div>
                    <div class="report-stat-label">Overall Attendance</div>
                    <span class="report-stat-badge badge-up">
                        <i class="bi bi-arrow-up-short"></i> +2.1% vs last month
                    </span>
                </div>
            </div>

            <!-- Total Students -->
            <div class="col-sm-6 col-xl-3">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-accent">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="report-stat-value">1,540</div>
                    <div class="report-stat-label">Total Students</div>
                    <span class="report-stat-badge badge-neutral">
                        <i class="bi bi-dash"></i> Across all departments
                    </span>
                </div>
            </div>

            <!-- Departments -->
            <div class="col-sm-6 col-xl-3">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-purple">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="report-stat-value">4</div>
                    <div class="report-stat-label">Departments</div>
                    <span class="report-stat-badge badge-neutral">
                        <i class="bi bi-dash"></i> CE, AI&ML, IT, ENTC
                    </span>
                </div>
            </div>

            <!-- Low Attendance Students -->
            <div class="col-sm-6 col-xl-3">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="report-stat-value">47</div>
                    <div class="report-stat-label">Low Attendance Students</div>
                    <span class="report-stat-badge badge-down">
                        <i class="bi bi-arrow-down-short"></i> Below 75% threshold
                    </span>
                </div>
            </div>

        </div>
        <!-- =========== END KPI STAT CARDS ================ -->

        <!-- =========== QUICK NAVIGATION CARDS ============ -->
        <div class="section-divider mb-3">
            <span class="section-divider-label"><i class="bi bi-grid-3x3-gap me-1"></i>Report Modules</span>
            <div class="section-divider-line"></div>
        </div>

        <div class="row g-4 mb-5">

            <!-- Subject-wise Analysis -->
            <div class="col-md-4">
                <a href="subject-analysis.php" class="report-nav-card" id="nav-card-subject">
                    <div class="report-nav-card-icon icon-indigo">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div class="report-nav-card-title">Subject-wise Analysis</div>
                    <div class="report-nav-card-desc">
                        Deep-dive into attendance percentages per subject. Identify subjects with low participation and take proactive action.
                    </div>
                    <span class="report-nav-card-btn">
                        Open Report <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
            </div>

            <!-- Monthly Trends -->
            <div class="col-md-4">
                <a href="monthly-trends.php" class="report-nav-card" id="nav-card-monthly">
                    <div class="report-nav-card-icon icon-cyan">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="report-nav-card-title">Monthly Attendance Trends</div>
                    <div class="report-nav-card-desc">
                        Compare attendance across all 12 months. Spot seasonal dips, best and worst performing periods at a glance.
                    </div>
                    <span class="report-nav-card-btn">
                        Open Report <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
            </div>

            <!-- Department Reports -->
            <div class="col-md-4">
                <a href="department-reports.php" class="report-nav-card" id="nav-card-dept">
                    <div class="report-nav-card-icon icon-violet">
                        <i class="bi bi-diagram-3-fill"></i>
                    </div>
                    <div class="report-nav-card-title">Department Reports</div>
                    <div class="report-nav-card-desc">
                        Compare attendance health across all departments. View faculty coverage, student counts, and risk metrics side-by-side.
                    </div>
                    <span class="report-nav-card-btn">
                        Open Report <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
            </div>

        </div>
        <!-- ======== END QUICK NAVIGATION CARDS =========== -->

        <!-- ========= OVERVIEW SUMMARY TABLE ============== -->
        <div class="section-divider mb-3">
            <span class="section-divider-label"><i class="bi bi-table me-1"></i>Quick Overview</span>
            <div class="section-divider-line"></div>
        </div>

        <div class="table-panel mb-5">
            <div class="table-panel-header">
                <h2 class="table-panel-title">Department Snapshot</h2>
                <div class="table-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="overview-search" class="table-search-input" placeholder="Search departments...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table report-table" id="overview-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Students</th>
                            <th>Subjects</th>
                            <th>Attendance %</th>
                            <th>At-Risk</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01</td>
                            <td><strong>Computer Engineering</strong></td>
                            <td>420</td>
                            <td>12</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:60px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;">
                                        <div style="width:88%;height:100%;background:linear-gradient(90deg,#6366f1,#818cf8);border-radius:50px;"></div>
                                    </div>
                                    <span class="fw-semibold">88%</span>
                                </div>
                            </td>
                            <td>12</td>
                            <td><span class="status-badge badge-excellent">Excellent</span></td>
                            <td><a href="department-reports.php" class="btn-report-apply py-1 px-3 text-decoration-none" style="font-size:0.8rem;">View</a></td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td><strong>AI &amp; ML</strong></td>
                            <td>380</td>
                            <td>11</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:60px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;">
                                        <div style="width:92%;height:100%;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:50px;"></div>
                                    </div>
                                    <span class="fw-semibold">92%</span>
                                </div>
                            </td>
                            <td>6</td>
                            <td><span class="status-badge badge-excellent">Excellent</span></td>
                            <td><a href="department-reports.php" class="btn-report-apply py-1 px-3 text-decoration-none" style="font-size:0.8rem;">View</a></td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td><strong>Information Technology</strong></td>
                            <td>360</td>
                            <td>10</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:60px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;">
                                        <div style="width:85%;height:100%;background:linear-gradient(90deg,#8b5cf6,#a78bfa);border-radius:50px;"></div>
                                    </div>
                                    <span class="fw-semibold">85%</span>
                                </div>
                            </td>
                            <td>18</td>
                            <td><span class="status-badge badge-good">Good</span></td>
                            <td><a href="department-reports.php" class="btn-report-apply py-1 px-3 text-decoration-none" style="font-size:0.8rem;">View</a></td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td><strong>ENTC</strong></td>
                            <td>380</td>
                            <td>11</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:60px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;">
                                        <div style="width:79%;height:100%;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:50px;"></div>
                                    </div>
                                    <span class="fw-semibold">79%</span>
                                </div>
                            </td>
                            <td>11</td>
                            <td><span class="status-badge badge-average">Average</span></td>
                            <td><a href="department-reports.php" class="btn-report-apply py-1 px-3 text-decoration-none" style="font-size:0.8rem;">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span id="table-row-count">Showing 4 records</span>
                <nav>
                    <ul class="pagination report-pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- ====== END OVERVIEW SUMMARY TABLE ============= -->

    </div><!-- /container -->
</div><!-- /reports-page-wrapper -->

<!-- Toast Notification -->
<div class="report-toast" id="report-toast">
    <div class="report-toast-icon"><i class="bi bi-info-circle"></i></div>
    <div>
        <div class="report-toast-title" id="report-toast-title">Notice</div>
        <p class="report-toast-body" id="report-toast-body"></p>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- Module JS -->
<script src="../assets/js/reports.js"></script>
<!-- Page-specific search -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    (function () {
        var input = document.getElementById('overview-search');
        var table = document.getElementById('overview-table');
        if (!input || !table) return;
        input.addEventListener('input', function () {
            var q    = this.value.toLowerCase();
            var rows = table.querySelectorAll('tbody tr');
            var cnt  = 0;
            rows.forEach(function (r) {
                var show = q === '' || r.textContent.toLowerCase().includes(q);
                r.style.display = show ? '' : 'none';
                if (show) cnt++;
            });
            var lbl = document.getElementById('table-row-count');
            if (lbl) lbl.textContent = 'Showing ' + cnt + ' records';
        });
    })();
});
</script>

<?php include '../includes/footer.php'; ?>
