<?php
/**
 * reports/monthly-trends.php
 * Monthly Attendance Trends page
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
                <span class="current">Monthly Trends</span>
            </nav>
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-calendar3"></i> Temporal Analysis
                    </div>
                    <h1 class="reports-page-title">Monthly Attendance Trends</h1>
                    <p class="reports-page-subtitle">
                        Track attendance patterns across all 12 months. Identify seasonal dips and recognise best-performing periods.
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
                <i class="bi bi-funnel-fill"></i> Filters &mdash; Refine Monthly Data
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-dept-mt">Department</label>
                    <select class="form-select report-form-select" id="filter-dept-mt">
                        <option value="">All</option>
                        <option>Computer Engineering</option>
                        <option>AI &amp; ML</option>
                        <option>Information Technology</option>
                        <option>ENTC</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-subject-mt">Subject</label>
                    <select class="form-select report-form-select" id="filter-subject-mt">
                        <option value="">All Subjects</option>
                        <option>Data Structures</option>
                        <option>DBMS</option>
                        <option>Operating Systems</option>
                        <option>Computer Networks</option>
                        <option>Web Technologies</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-sem-mt">Semester</label>
                    <select class="form-select report-form-select" id="filter-sem-mt">
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
                    <label class="report-form-label" for="filter-year-mt">Academic Year</label>
                    <select class="form-select report-form-select" id="filter-year-mt">
                        <option>2024-25</option>
                        <option>2023-24</option>
                        <option>2022-23</option>
                    </select>
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

        <!-- SUMMARY KPI CARDS -->
        <div class="row g-4 mb-4">
            <div class="col-sm-4">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-success">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div class="report-stat-value">September</div>
                    <div class="report-stat-label">Best Month</div>
                    <span class="report-stat-badge badge-up"><i class="bi bi-star-fill"></i> 91% attendance</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-danger">
                        <i class="bi bi-calendar-x-fill"></i>
                    </div>
                    <div class="report-stat-value">July</div>
                    <div class="report-stat-label">Worst Month</div>
                    <span class="report-stat-badge badge-down"><i class="bi bi-arrow-down-short"></i> 75% attendance</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-accent">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="report-stat-value">84.8<span style="font-size:1rem;color:var(--text-muted)">%</span></div>
                    <div class="report-stat-label">Average Attendance</div>
                    <span class="report-stat-badge badge-neutral"><i class="bi bi-dash"></i> Annual average 2024-25</span>
                </div>
            </div>
        </div>

        <!-- LINE CHART -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-card-title">
                        <i class="bi bi-graph-up me-2" style="color:var(--primary-light);"></i>
                        Monthly Attendance Comparison — 2024-25
                    </div>
                    <div class="chart-card-subtitle">
                        Purple line = actual attendance. Blue dashed = target (85%). Hover for details.
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="monthlyLineChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- MONTHLY TABLE -->
        <div class="table-panel mb-5">
            <div class="table-panel-header">
                <h2 class="table-panel-title"><i class="bi bi-table me-2 text-muted" style="font-size:0.95rem;"></i>Monthly Attendance Details</h2>
                <div class="table-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="monthly-search" class="table-search-input" placeholder="Search months...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table report-table" id="monthly-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Month</th>
                            <th>Working Days</th>
                            <th>Classes Held</th>
                            <th>Avg Present</th>
                            <th>Attendance %</th>
                            <th>MoM Change</th>
                            <th>Trend</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Dummy monthly data */
                         = [
                            ['01', 'January',   22, 110, '88/100', '84%', '+0.0%', 'flat',  'good'],
                            ['02', 'February',  20, 100, '87/100', '87%', '+3.0%', 'up',    'good'],
                            ['03', 'March',     23, 115, '82/100', '82%', '-5.0%', 'down',  'good'],
                            ['04', 'April',     22, 110, '90/100', '90%', '+8.0%', 'up',    'excellent'],
                            ['05', 'May',       21, 105, '88/100', '88%', '-2.0%', 'down',  'good'],
                            ['06', 'June',      22, 110, '79/100', '79%', '-9.0%', 'down',  'average'],
                            ['07', 'July',      23, 115, '75/100', '75%', '-4.0%', 'down',  'average'],
                            ['08', 'August',    22, 110, '85/100', '85%', '+10.%', 'up',    'good'],
                            ['09', 'September', 21, 105, '91/100', '91%', '+6.0%', 'up',    'excellent'],
                            ['10', 'October',   22, 110, '89/100', '89%', '-2.0%', 'down',  'good'],
                            ['11', 'November',  21, 105, '86/100', '86%', '-3.0%', 'down',  'good'],
                            ['12', 'December',  18,  90, '83/100', '83%', '-3.0%', 'down',  'good'],
                        ];

                         = [
                            'excellent' => 'badge-excellent',
                            'good'      => 'badge-good',
                            'average'   => 'badge-average',
                            'critical'  => 'badge-critical',
                        ];

                         = [
                            'up'   => '<i class="bi bi-arrow-up-short"></i>',
                            'down' => '<i class="bi bi-arrow-down-short"></i>',
                            'flat' => '<i class="bi bi-dash"></i>',
                        ];

                        foreach ( as ) {
                             = [[8]] ?? 'badge-good';
                                   = ucfirst([8]);
                                    = [7];
                              =  === 'up' ? 'up' : ( === 'down' ? 'down' : 'flat');
                               =  === 'up' ? 'trend-up' : ( === 'down' ? 'trend-down' : 'trend-flat');
                                    = [] ?? '';
                            echo "<tr>";
                            echo "  <td><span style='color:var(--text-muted);font-size:0.8rem;'>{[0]}</span></td>";
                            echo "  <td><strong>{[1]}</strong></td>";
                            echo "  <td>{[2]}</td>";
                            echo "  <td>{[3]}</td>";
                            echo "  <td>{[4]}</td>";
                            echo "  <td><strong>{[5]}</strong></td>";
                            echo "  <td><span class='monthly-change-pill {}'>{} {[6]}</span></td>";
                            echo "  <td><span class='{}'>{}</span></td>";
                            echo "  <td><span class='status-badge {}'>{}</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span id="table-row-count">Showing 12 records</span>
                <nav aria-label="Monthly table pagination">
                    <ul class="pagination report-pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

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
<script src="../assets/js/reports.js"></script>

<?php include '../includes/footer.php'; ?>
