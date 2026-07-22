<?php 
$page_title = "Monthly Attendance Report | Faculty Reports";
include 'includes/header.php'; 

// Mock Monthly Aggregated Student Attendance Data
$monthly_records = [
    [
        'roll_no' => 'CS2024-001',
        'name' => 'Aarav Sharma',
        'prn' => 'PRN20249801',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 30,
        'percent' => 93.8,
        'status' => 'Safe'
    ],
    [
        'roll_no' => 'CS2024-002',
        'name' => 'Ananya Verma',
        'prn' => 'PRN20249802',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 26,
        'percent' => 81.3,
        'status' => 'Warning'
    ],
    [
        'roll_no' => 'CS2024-003',
        'name' => 'Rohan Mehta',
        'prn' => 'PRN20249803',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 21,
        'percent' => 65.6,
        'status' => 'Defaulter'
    ],
    [
        'roll_no' => 'CS2024-004',
        'name' => 'Priya Patel',
        'prn' => 'PRN20249804',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 31,
        'percent' => 96.9,
        'status' => 'Safe'
    ],
    [
        'roll_no' => 'CS2024-005',
        'name' => 'Devendra Singh',
        'prn' => 'PRN20249805',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 22,
        'percent' => 68.8,
        'status' => 'Defaulter'
    ],
    [
        'roll_no' => 'CS2024-006',
        'name' => 'Ishita Joshi',
        'prn' => 'PRN20249806',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 28,
        'percent' => 87.5,
        'status' => 'Safe'
    ],
    [
        'roll_no' => 'CS2024-007',
        'name' => 'Kabir Nair',
        'prn' => 'PRN20249807',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 25,
        'percent' => 78.1,
        'status' => 'Warning'
    ],
    [
        'roll_no' => 'CS2024-008',
        'name' => 'Neha Gupta',
        'prn' => 'PRN20249808',
        'dept' => 'Computer Science',
        'sem' => 'Sem IV',
        'course' => 'CS-101 Data Structures',
        'held' => 32,
        'attended' => 29,
        'percent' => 90.6,
        'status' => 'Safe'
    ]
];
?>

<main class="main-content style-pt">
    <!-- Reports Hero Section -->
    <section class="reports-hero-header">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-calendar-check-fill"></i> Monthly Academic Analytics
                    </div>
                    <h1 class="h2 mb-1 text-white">Monthly Attendance Report</h1>
                    <p class="text-muted mb-0">Generate monthly compliance summaries, track defaulters (< 75%), and visualize course performance trends.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="exportTableToCSV('monthlyTable', 'Monthly_Attendance_Report_July2026.csv')">
                        <i class="bi bi-download me-1"></i> Export Monthly CSV
                    </button>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="printReport()">
                        <i class="bi bi-printer me-1"></i> Print Report PDF
                    </button>
                </div>
            </div>

            <!-- Module Sub-Navigation Tabs -->
            <div class="reports-nav-tabs">
                <a href="attendance-history.php" class="nav-link">
                    <i class="bi bi-clock-history"></i> Attendance History
                </a>
                <a href="monthly-report.php" class="nav-link active">
                    <i class="bi bi-calendar-month"></i> Monthly Attendance Report
                </a>
                <a href="student-summary.php" class="nav-link">
                    <i class="bi bi-person-lines-fill"></i> Student-wise Summary
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="container mb-5">
        <!-- Analytics Metric Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon primary">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div class="stat-value">180</div>
                    <div class="stat-label">Total Enrolled Students</div>
                    <div class="stat-trend text-muted">Across 3 Batches</div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon success">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="stat-value">89.2%</div>
                    <div class="stat-label">Monthly Avg Attendance</div>
                    <div class="stat-trend text-success">
                        <i class="bi bi-arrow-up-short"></i> +2.1% higher than June
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon danger">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="stat-value">8</div>
                    <div class="stat-label">Flagged Defaulters</div>
                    <div class="stat-trend text-danger">
                        Attendance below 75%
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon accent">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="stat-value">July 19</div>
                    <div class="stat-label">Peak Attendance Day</div>
                    <div class="stat-trend text-info">
                        96.7% daily turn out
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls Panel -->
        <div class="filter-box">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label for="selectMonthYear"><i class="bi bi-calendar-fill me-1"></i> Month & Year</label>
                    <input type="month" id="selectMonthYear" class="form-control" value="2026-07">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterCourse"><i class="bi bi-book me-1"></i> Course / Subject</label>
                    <select id="filterCourse" class="form-select report-filter-select">
                        <option value="ALL">All Subjects</option>
                        <option value="CS-101">CS-101 Data Structures</option>
                        <option value="CS-102">CS-102 Database Systems</option>
                        <option value="CS-103">CS-103 Web Dev</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterThreshold"><i class="bi bi-funnel-fill me-1"></i> Compliance Status</label>
                    <select id="filterThreshold" class="form-select report-filter-select">
                        <option value="ALL">All Students</option>
                        <option value="defaulter">Defaulters Only (< 75%)</option>
                        <option value="warning">Warning Threshold (75% - 85%)</option>
                        <option value="good">High Attendance (> 85%)</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="searchMonthly"><i class="bi bi-search me-1"></i> Student Search</label>
                    <input type="text" id="searchMonthly" class="form-control report-search-input" placeholder="Search by name, roll no...">
                </div>
            </div>
        </div>

        <!-- Chart Section: Monthly Attendance Trend -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="text-white mb-1"><i class="bi bi-activity text-primary me-2"></i> Monthly Course Attendance Trends</h5>
                    <p class="text-muted small mb-0">Daily attendance percentage distribution for July 2026</p>
                </div>
                <span class="badge bg-primary bg-opacity-25 text-primary border border-primary">Live Interactive Graph</span>
            </div>
            <div style="height: 280px; position: relative;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Monthly Aggregated Data Table -->
        <div class="glass-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="custom-report-table" id="monthlyTable">
                    <thead>
                        <tr>
                            <th>Roll Number</th>
                            <th>Student Name</th>
                            <th>Department / Sem</th>
                            <th>Classes Held</th>
                            <th>Attended</th>
                            <th>Monthly %</th>
                            <th>Compliance Status</th>
                            <th class="text-end no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="monthlyTableBody">
                        <?php foreach ($monthly_records as $r): ?>
                        <tr class="data-row" 
                            data-course="<?php echo $r['course']; ?>" 
                            data-percent="<?php echo $r['percent']; ?>">
                            
                            <td><span class="font-monospace text-primary-light fw-bold"><?php echo $r['roll_no']; ?></span></td>
                            <td>
                                <div class="fw-bold text-white"><?php echo $r['name']; ?></div>
                                <div class="text-muted small"><?php echo $r['prn']; ?></div>
                            </td>
                            <td><?php echo $r['dept']; ?> <span class="text-muted">(<?php echo $r['sem']; ?>)</span></td>
                            <td><span class="fw-bold text-white"><?php echo $r['held']; ?></span></td>
                            <td><span class="fw-bold text-info"><?php echo $r['attended']; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold <?php echo $r['percent'] < 75 ? 'text-danger' : ($r['percent'] < 85 ? 'text-warning' : 'text-success'); ?>">
                                        <?php echo $r['percent']; ?>%
                                    </span>
                                    <div class="progress progress-dark flex-grow-1" style="width: 50px;">
                                        <div class="progress-bar <?php echo $r['percent'] < 75 ? 'bg-danger' : ($r['percent'] < 85 ? 'bg-warning' : 'bg-success'); ?>" 
                                             role="progressbar" 
                                             style="width: <?php echo $r['percent']; ?>%" 
                                             aria-valuenow="<?php echo $r['percent']; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($r['percent'] < 75): ?>
                                    <span class="badge-status badge-status-defaulter">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Defaulter (< 75%)
                                    </span>
                                <?php elseif ($r['percent'] < 85): ?>
                                    <span class="badge-status badge-status-warning">
                                        <i class="bi bi-exclamation-circle-fill"></i> Warning (75-85%)
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status badge-status-safe">
                                        <i class="bi bi-check-circle-fill"></i> Good (> 85%)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end no-export">
                                <button class="btn btn-action-icon me-1" 
                                        title="View Student Full Profile"
                                        onclick="showStudentDetails(
                                            '<?php echo addslashes($r['name']); ?>',
                                            '<?php echo $r['roll_no']; ?>',
                                            '<?php echo $r['prn']; ?>',
                                            '<?php echo $r['dept']; ?>',
                                            '<?php echo $r['sem']; ?>',
                                            '<?php echo $r['held']; ?>',
                                            '<?php echo $r['attended']; ?>',
                                            '<?php echo $r['percent']; ?>'
                                        )">
                                    <i class="bi bi-person-bounding-box"></i>
                                </button>
                                <?php if ($r['percent'] < 75): ?>
                                <button class="btn btn-action-icon text-danger" title="Send Defaulter Notice Alert" onclick="alert('Defaulter email notice dispatched to <?php echo addslashes($r['name']); ?>!')">
                                    <i class="bi bi-bell-fill"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty Search Results Message -->
            <div id="monthlyEmptyState" class="text-center py-5" style="display: none;">
                <i class="bi bi-funnel text-muted display-4 mb-3 d-block"></i>
                <h5 class="text-white">No students match your criteria</h5>
                <p class="text-muted">Try changing the compliance status or course filter.</p>
            </div>
        </div>
    </div>
</main>

<!-- Student Detail Modal -->
<div class="modal fade modal-glass" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="studentDetailModalLabel">Student Academic Performance Card</h5>
                    <div class="text-muted small">Monthly Compliance & Summary</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Profile Header -->
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-dark border border-secondary mb-4">
                    <div class="student-avatar" id="modalStudentAvatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="text-white mb-1" id="modalStudentName">Aarav Sharma</h5>
                        <div class="text-muted small">
                            <span id="modalStudentRoll">CS2024-001</span> | 
                            <span id="modalStudentPRN">PRN20249801</span> | 
                            <span id="modalStudentDept">Computer Science (Sem IV)</span>
                        </div>
                    </div>
                    <div id="modalStudentBadge">
                        <span class="badge-status badge-status-safe">Good (> 85%)</span>
                    </div>
                </div>

                <!-- Stats summary -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Classes Conducted</div>
                            <div class="h4 mb-0 text-white" id="modalStudentTotal">32</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Classes Attended</div>
                            <div class="h4 mb-0 text-info" id="modalStudentAttended">30</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Monthly Percentage</div>
                            <div class="h4 mb-0 text-success" id="modalStudentPercent">93.8%</div>
                        </div>
                    </div>
                </div>

                <!-- Subject-wise performance -->
                <h6 class="text-muted uppercase mb-3">Subject-wise Breakdown</h6>
                <div class="table-responsive">
                    <table class="custom-report-table">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Title</th>
                                <th>Held</th>
                                <th>Attended</th>
                                <th>Percentage %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-101</td>
                                <td>Data Structures & Algorithms</td>
                                <td>12</td>
                                <td>11</td>
                                <td><span class="text-success fw-bold">91.7%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-102</td>
                                <td>Database Management Systems</td>
                                <td>10</td>
                                <td>9</td>
                                <td><span class="text-success fw-bold">90.0%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-103</td>
                                <td>Web Application Development</td>
                                <td>10</td>
                                <td>10</td>
                                <td><span class="text-success fw-bold">100.0%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill" onclick="printReport()">Print Report Card</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
