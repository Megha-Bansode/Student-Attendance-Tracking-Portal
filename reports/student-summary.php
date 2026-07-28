<?php 
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Student-wise Attendance Summary | Faculty Reports";
include '../includes/header.php'; 

// Fetch all students and dynamic stats
$students_db = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY name ASC")->fetchAll();
$students = [];

$defaulter_count = 0;
$high_performers_count = 0;
$total_held_overall = 0;
$total_attended_overall = 0;

foreach ($students_db as $stud) {
    $s_id = $stud['id'];
    
    // Fetch overall held vs attended
    $stmt_tot = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ?");
    $stmt_tot->execute([$s_id]);
    $total_held = $stmt_tot->fetchColumn();

    $stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND status = 'Present'");
    $stmt_pres->execute([$s_id]);
    $total_attended = $stmt_pres->fetchColumn();

    $percent = $total_held > 0 ? round(($total_attended / $total_held) * 100, 1) : 100.0;
    
    $status = 'Safe';
    if ($total_held > 0) {
        if ($percent < 75.0) {
            $status = 'Defaulter';
            $defaulter_count++;
        } else if ($percent < 80.0) {
            $status = 'Warning';
        }
    }
    
    if ($percent >= 90.0) {
        $high_performers_count++;
    }

    $total_held_overall += $total_held;
    $total_attended_overall += $total_attended;
    
    // Fetch subject-wise details
    $subjects_db = $pdo->query("SELECT * FROM subjects ORDER BY name ASC")->fetchAll();
    $subject_summaries = [];
    foreach ($subjects_db as $subj) {
        $subj_id = $subj['id'];
        
        $stmt_s_tot = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ?");
        $stmt_s_tot->execute([$s_id, $subj_id]);
        $s_held = $stmt_s_tot->fetchColumn();
        
        $stmt_s_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ? AND status = 'Present'");
        $stmt_s_pres->execute([$s_id, $subj_id]);
        $s_attended = $stmt_s_pres->fetchColumn();
        
        $s_percent = $s_held > 0 ? round(($s_attended / $s_held) * 100, 1) : 100.0;
        
        $subject_summaries[] = [
            'code' => 'SUBJ-' . $subj_id,
            'name' => $subj['name'],
            'held' => $s_held,
            'attended' => $s_attended,
            'percent' => $s_percent
        ];
    }
    
    $students[] = [
        'roll_no' => $stud['zprn'],
        'name' => $stud['name'],
        'prn' => $stud['zprn'],
        'dept' => $stud['department'] ?? 'Unassigned',
        'sem' => ($stud['class'] === 'First Year') ? 'Sem I' : (($stud['class'] === 'Second Year') ? 'Sem III' : (($stud['class'] === 'Third Year') ? 'Sem V' : 'Sem VII')),
        'email' => $stud['username'] . '@college.edu',
        'total_held' => $total_held,
        'total_attended' => $total_attended,
        'percent' => $percent,
        'status' => $status,
        'subjects' => $subject_summaries
    ];
}

$total_students = count($students);
$average_attendance = $total_held_overall > 0 ? round(($total_attended_overall / $total_held_overall) * 100, 1) : 100.0;
?>

<main class="main-content style-pt">
    <!-- Reports Hero Section -->
    <section class="reports-hero-header">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-person-bounding-box"></i> Student Profile Analytics
                    </div>
                    <h1 class="h2 mb-1 text-white">Student-wise Attendance Summary</h1>
                    <p class="text-muted mb-0">Search and audit individual student attendance track records, subject breakdowns, and risk statuses.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="exportTableToCSV('studentTable', 'Student_Attendance_Summary.csv')">
                        <i class="bi bi-download me-1"></i> Export Summary CSV
                    </button>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="printReport()">
                        <i class="bi bi-printer me-1"></i> Print Student Index
                    </button>
                </div>
            </div>

            <!-- Module Sub-Navigation Tabs -->
            <div class="reports-nav-tabs">
                <a href="reports/attendance-history.php" class="nav-link">
                    <i class="bi bi-clock-history"></i> Attendance History
                </a>
                <a href="reports/monthly-report.php" class="nav-link">
                    <i class="bi bi-calendar-month"></i> Monthly Attendance Report
                </a>
                <a href="reports/student-summary.php" class="nav-link active">
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
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-value"><?php echo $total_students; ?></div>
                    <div class="stat-label">Registered Students</div>
                    <div class="stat-trend text-muted">Across all branches</div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon success">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <div class="stat-value"><?php echo $average_attendance; ?>%</div>
                    <div class="stat-label">Cumulative Average</div>
                    <div class="stat-trend text-success">
                        Overall semester rate
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon danger">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <div class="stat-value"><?php echo $defaulter_count; ?></div>
                    <div class="stat-label">Defaulter Students</div>
                    <div class="stat-trend text-danger">
                        Cumulative &lt; 75%
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon accent">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="stat-value"><?php echo $high_performers_count; ?></div>
                    <div class="stat-label">High Performers</div>
                    <div class="stat-trend text-info">
                        Attendance &gt; 90%
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <div class="filter-box">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6">
                    <label for="searchStudentInput"><i class="bi bi-search me-1"></i> Search Student</label>
                    <input type="text" id="searchStudentInput" class="form-control report-search-input" placeholder="Type Student Name, Roll No, or PRN...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterDept"><i class="bi bi-building me-1"></i> Department / Branch</label>
                    <select id="filterDept" class="form-select report-filter-select">
                        <option value="ALL">All Departments</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Tech">Information Technology</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12">
                    <label for="filterStudentStatus"><i class="bi bi-shield-check me-1"></i> Attendance Status</label>
                    <select id="filterStudentStatus" class="form-select report-filter-select">
                        <option value="ALL">All Risk Levels</option>
                        <option value="safe">Safe (>= 75%)</option>
                        <option value="defaulter">Defaulter Risk (< 75%)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Student Summary Roster Table -->
        <div class="glass-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="custom-report-table" id="studentTable">
                    <thead>
                        <tr>
                            <th>Student Info</th>
                            <th>Roll Number</th>
                            <th>Branch & Semester</th>
                            <th>Total Classes Held</th>
                            <th>Attended</th>
                            <th>Overall %</th>
                            <th>Risk Category</th>
                            <th class="text-end no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <?php foreach ($students as $st): ?>
                        <tr class="data-row" 
                            data-dept="<?php echo $st['dept']; ?>" 
                            data-percent="<?php echo $st['percent']; ?>">
                            
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="student-avatar" style="width: 40px; height: 40px; font-size: 1rem;">
                                        <?php echo substr($st['name'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white"><?php echo $st['name']; ?></div>
                                        <div class="text-muted small"><?php echo $st['email']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="font-monospace text-primary-light fw-bold"><?php echo $st['roll_no']; ?></span></td>
                            <td>
                                <div class="text-white small"><?php echo $st['dept']; ?></div>
                                <div class="text-muted small"><?php echo $st['sem']; ?></div>
                            </td>
                            <td><span class="fw-bold text-white"><?php echo $st['total_held']; ?></span></td>
                            <td><span class="fw-bold text-info"><?php echo $st['total_attended']; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold <?php echo $st['percent'] < 75 ? 'text-danger' : ($st['percent'] < 85 ? 'text-warning' : 'text-success'); ?>">
                                        <?php echo $st['percent']; ?>%
                                    </span>
                                    <div class="progress progress-dark flex-grow-1" style="width: 50px;">
                                        <div class="progress-bar <?php echo $st['percent'] < 75 ? 'bg-danger' : ($st['percent'] < 85 ? 'bg-warning' : 'bg-success'); ?>" 
                                             role="progressbar" 
                                             style="width: <?php echo $st['percent']; ?>%" 
                                             aria-valuenow="<?php echo $st['percent']; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($st['percent'] < 75): ?>
                                    <span class="badge-status badge-status-defaulter">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Critical Defaulter
                                    </span>
                                <?php elseif ($st['percent'] < 85): ?>
                                    <span class="badge-status badge-status-warning">
                                        <i class="bi bi-exclamation-circle-fill"></i> Moderate Risk
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status badge-status-safe">
                                        <i class="bi bi-shield-check"></i> Safe Standing
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end no-export">
                                <button class="btn btn-action-icon me-1" 
                                        title="View Student Roster Card"
                                        onclick="showStudentDetails(
                                            '<?php echo addslashes($st['name']); ?>',
                                            '<?php echo $st['roll_no']; ?>',
                                            '<?php echo $st['prn']; ?>',
                                            '<?php echo $st['dept']; ?>',
                                            '<?php echo $st['sem']; ?>',
                                            '<?php echo $st['total_held']; ?>',
                                            '<?php echo $st['total_attended']; ?>',
                                            '<?php echo $st['percent']; ?>'
                                        )">
                                    <i class="bi bi-file-earmark-person"></i>
                                </button>
                                <button class="btn btn-action-icon" title="Print Progress Card" onclick="printReport()">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty Search State -->
            <div id="studentEmptyState" class="text-center py-5" style="display: none;">
                <i class="bi bi-person-x text-muted display-4 mb-3 d-block"></i>
                <h5 class="text-white">No students matched your search</h5>
                <p class="text-muted">Check your spelling or filter settings.</p>
            </div>
        </div>
    </div>
</main>

<!-- Comprehensive Student Detail Modal -->
<div class="modal fade modal-glass" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="studentDetailModalLabel">Comprehensive Student Attendance Card</h5>
                    <div class="text-muted small">Cumulative Academic Record</div>
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
                        <span class="badge-status badge-status-safe">Good Standing</span>
                    </div>
                </div>

                <!-- Stats summary -->
                <div class="row g-3 mb-4 text-center">
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Total Conducted</div>
                            <div class="h4 mb-0 text-white" id="modalStudentTotal">120</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Total Attended</div>
                            <div class="h4 mb-0 text-info" id="modalStudentAttended">112</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Overall Attendance</div>
                            <div class="h4 mb-0 text-success" id="modalStudentPercent">93.3%</div>
                        </div>
                    </div>
                </div>

                <!-- Subject Breakdown Table -->
                <h6 class="text-muted uppercase mb-3">All Subjects Performance Overview</h6>
                <div class="table-responsive">
                    <table class="custom-report-table">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Held</th>
                                <th>Attended</th>
                                <th>Percentage %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-101</td>
                                <td>Data Structures & Algorithms</td>
                                <td>35</td>
                                <td>33</td>
                                <td><span class="text-success fw-bold">94.3%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-102</td>
                                <td>Database Management Systems</td>
                                <td>30</td>
                                <td>28</td>
                                <td><span class="text-success fw-bold">93.3%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-103</td>
                                <td>Web Application Development</td>
                                <td>30</td>
                                <td>29</td>
                                <td><span class="text-success fw-bold">96.7%</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary-light">CS-104</td>
                                <td>Artificial Intelligence & ML</td>
                                <td>25</td>
                                <td>22</td>
                                <td><span class="text-success fw-bold">88.0%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill" onclick="printReport()">Print Full Report</button>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
