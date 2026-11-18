<?php
/**
 * reports/subject-analysis.php
 * Subject-wise Attendance Analysis page
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
                <span class="current">Subject Analysis</span>
            </nav>
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-book-half"></i> Subject Intelligence
                    </div>
                    <h1 class="reports-page-title">Subject-wise Attendance Analysis</h1>
                    <p class="reports-page-subtitle">
                        Identify attendance trends per subject. Spot underperforming subjects and take corrective action early.
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
                <i class="bi bi-funnel-fill"></i> Filters &mdash; Refine Subject Data
            </div>
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-dept-sa">Department</label>
                    <select class="form-select report-form-select" id="filter-dept-sa">
                        <option value="">All</option>
                        <option>Computer Engineering</option>
                        <option>AI &amp; ML</option>
                        <option>Information Technology</option>
                        <option>ENTC</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-sem-sa">Semester</label>
                    <select class="form-select report-form-select" id="filter-sem-sa">
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
                    <label class="report-form-label" for="filter-year-sa">Academic Year</label>
                    <select class="form-select report-form-select" id="filter-year-sa">
                        <option>2024-25</option>
                        <option>2023-24</option>
                        <option>2022-23</option>
                    </select>
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-date-from-sa">From Date</label>
                    <input type="date" class="form-control report-form-control" id="filter-date-from-sa">
                </div>
                <div class="col-sm-6 col-lg-2">
                    <label class="report-form-label" for="filter-date-to-sa">To Date</label>
                    <input type="date" class="form-control report-form-control" id="filter-date-to-sa">
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
                    <div class="report-stat-icon icon-primary">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="report-stat-value">84.6<span style="font-size:1rem;color:var(--text-muted)">%</span></div>
                    <div class="report-stat-label">Average Attendance</div>
                    <span class="report-stat-badge badge-up"><i class="bi bi-arrow-up-short"></i> Across all subjects</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-success">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="report-stat-value">93<span style="font-size:1rem;color:var(--text-muted)">%</span></div>
                    <div class="report-stat-label">Highest Subject</div>
                    <span class="report-stat-badge badge-up"><i class="bi bi-star-fill"></i> Web Technologies</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="report-stat-card">
                    <div class="report-stat-icon icon-danger">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <div class="report-stat-value">71<span style="font-size:1rem;color:var(--text-muted)">%</span></div>
                    <div class="report-stat-label">Lowest Subject</div>
                    <span class="report-stat-badge badge-down"><i class="bi bi-arrow-down-short"></i> Theory of Computation</span>
                </div>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="table-panel mb-4">
            <div class="table-panel-header">
                <h2 class="table-panel-title"><i class="bi bi-table me-2 text-muted" style="font-size:0.95rem;"></i>Subject Attendance Records</h2>
                <div class="table-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="subject-search" class="table-search-input" placeholder="Search subjects...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table report-table" id="subject-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Faculty</th>
                            <th>Students</th>
                            <th>Total Classes</th>
                            <th>Avg Attendance</th>
                            <th>Attendance %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* Dummy data — replace with DB query when backend is ready */
                         = [
                            ['01', 'Data Structures & Algorithms', 'Prof. R. Sharma',  420, 48, '88%', 'excellent'],
                            ['02', 'Database Management Systems',  'Prof. S. Kulkarni', 380, 44, '91%', 'excellent'],
                            ['03', 'Operating Systems',            'Prof. A. Mehta',    420, 46, '82%', 'good'],
                            ['04', 'Computer Networks',            'Prof. P. Desai',    360, 44, '79%', 'average'],
                            ['05', 'Web Technologies',             'Prof. N. Joshi',    380, 48, '93%', 'excellent'],
                            ['06', 'Artificial Intelligence',      'Prof. V. Patil',    360, 42, '85%', 'good'],
                            ['07', 'Machine Learning',             'Prof. D. Kadam',    200, 44, '88%', 'excellent'],
                            ['08', 'Theory of Computation',       'Prof. M. Rane',     420, 46, '71%', 'critical'],
                            ['09', 'Software Engineering',         'Prof. K. More',     380, 40, '76%', 'average'],
                            ['10', 'Cloud Computing',              'Prof. R. Pawar',    360, 36, '83%', 'good'],
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
                            echo "<tr>";
                            echo "  <td><span style='color:var(--text-muted);font-size:0.8rem;'>{[0]}</span></td>";
                            echo "  <td><strong>{[1]}</strong></td>";
                            echo "  <td>{[2]}</td>";
                            echo "  <td>{[3]}</td>";
                            echo "  <td>{[4]}</td>";
                            echo "  <td>{[5]}</td>";
                            echo "  <td>";
                             = (int)[5];
                            echo "    <div class='d-flex align-items-center gap-2'>";
                            echo "      <div style='width:55px;height:5px;border-radius:50px;background:var(--bg-dark-800);overflow:hidden;'>";
                            echo "        <div style='width:{}%;height:100%;background:linear-gradient(90deg,#6366f1,#0ea5e9);border-radius:50px;'></div>";
                            echo "      </div>";
                            echo "      <span class='fw-semibold'>{[5]}</span>";
                            echo "    </div>";
                            echo "  </td>";
                            echo "  <td><span class='status-badge {}'>{}</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span id="table-row-count">Showing <?php echo count(); ?> records</span>
                <nav aria-label="Subject table pagination">
                    <ul class="pagination report-pagination mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item disabled"><a class="page-link" href="#" aria-label="Next"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- BAR CHART -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-card-title">
                        <i class="bi bi-bar-chart-fill me-2" style="color:var(--primary-light);"></i>
                        Attendance by Subject
                    </div>
                    <div class="chart-card-subtitle">Bar chart showing attendance % for each subject. Data sourced from dummy records.</div>
                    <div class="chart-wrapper">
                        <canvas id="subjectBarChart" height="90"></canvas>
                    </div>
                </div>
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
