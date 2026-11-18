<?php 
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Attendance History | Faculty Reports";
include '../includes/header.php'; 

// Fetch history from attendance table
$stmt_sess = $pdo->query("
    SELECT a.date, s.id AS subject_id, s.name AS subject_name, u.class, u.division,
           COUNT(a.id) AS total_count,
           SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.id
    JOIN users u ON a.student_id = u.id
    GROUP BY a.date, a.subject_id, u.class, u.division
    ORDER BY a.date DESC, a.subject_id DESC
");
$db_sessions = $stmt_sess->fetchAll();

$sessions = [];
foreach ($db_sessions as $idx => $row) {
    $total = $row['total_count'];
    $present = $row['present_count'];
    $absent = $total - $present;
    $percent = $total > 0 ? round(($present / $total) * 100, 1) : 0.0;
    
    $sessions[] = [
        'id' => 'SES-' . (9000 + $idx),
        'date' => $row['date'],
        'time' => '10:00 AM - 11:00 AM', // Dummy slot time
        'subject_code' => 'SUBJ-' . $row['subject_id'],
        'subject_name' => $row['subject_name'],
        'class' => $row['class'] . ' Div ' . $row['division'],
        'topic' => 'Regular Lecture Session',
        'total' => $total,
        'present' => $present,
        'absent' => $absent,
        'percent' => $percent,
        'status' => 'Conducted'
    ];
}
?>

<main class="main-content style-pt">
    <!-- Reports Hero Section -->
    <section class="reports-hero-header">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <div class="tagline-badge mb-2">
                        <i class="bi bi-shield-lock-fill"></i> Faculty Analytics Portal
                    </div>
                    <h1 class="h2 mb-1 text-white">Attendance History Log</h1>
                    <p class="text-muted mb-0">Track, filter, and inspect past class session attendance records with instant export capabilities.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="exportTableToCSV('historyTable', 'Attendance_History_Report.csv')">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="printReport()">
                        <i class="bi bi-printer me-1"></i> Print Log
                    </button>
                </div>
            </div>

            <!-- Module Sub-Navigation Tabs -->
            <div class="reports-nav-tabs">
                <a href="reports/attendance-history.php" class="nav-link active">
                    <i class="bi bi-clock-history"></i> Attendance History
                </a>
                <a href="reports/monthly-report.php" class="nav-link">
                    <i class="bi bi-calendar-month"></i> Monthly Attendance Report
                </a>
                <a href="reports/student-summary.php" class="nav-link">
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
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div class="stat-value">148</div>
                    <div class="stat-label">Total Sessions</div>
                    <div class="stat-trend text-success">
                        <i class="bi bi-arrow-up-short"></i> 12 this week
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon success">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <div class="stat-value">91.4%</div>
                    <div class="stat-label">Avg Attendance Rate</div>
                    <div class="stat-trend text-success">
                        <i class="bi bi-arrow-up-short"></i> +1.8% vs last month
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon accent">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-value">8,420</div>
                    <div class="stat-label">Present Headcount</div>
                    <div class="stat-trend text-muted">
                        Across all courses
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card-widget">
                    <div class="stat-icon danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">Low Attendance Alerts</div>
                    <div class="stat-trend text-danger">
                        Sessions < 80% attendance
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Panel -->
        <div class="filter-box">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label for="searchQuery"><i class="bi bi-search me-1"></i> Search Log</label>
                    <input type="text" id="searchQuery" class="form-control report-search-input" placeholder="Search topic, subject, class...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterSubject"><i class="bi bi-book me-1"></i> Subject Code</label>
                    <select id="filterSubject" class="form-select report-filter-select">
                        <option value="ALL">All Subjects</option>
                        <option value="CS-101">CS-101 Data Structures</option>
                        <option value="CS-102">CS-102 Database Systems</option>
                        <option value="CS-103">CS-103 Web Dev</option>
                        <option value="CS-104">CS-104 AI & ML</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="filterStatus"><i class="bi bi-filter-circle me-1"></i> Status</label>
                    <select id="filterStatus" class="form-select report-filter-select">
                        <option value="ALL">All Statuses</option>
                        <option value="Conducted">Conducted</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="startDate"><i class="bi bi-calendar-event me-1"></i> Start Date</label>
                    <input type="date" id="startDate" class="form-control report-date-input">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="endDate"><i class="bi bi-calendar-event me-1"></i> End Date</label>
                    <input type="date" id="endDate" class="form-control report-date-input">
                </div>
            </div>
        </div>

        <!-- Attendance Sessions Table -->
        <div class="glass-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="custom-report-table" id="historyTable">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Date & Time</th>
                            <th>Subject</th>
                            <th>Class / Section</th>
                            <th>Topic Taught</th>
                            <th>Headcount</th>
                            <th>Attendance Rate</th>
                            <th>Status</th>
                            <th class="text-end no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody">
                        <?php foreach ($sessions as $s): ?>
                        <tr class="data-row" 
                            data-subject="<?php echo $s['subject_code']; ?>" 
                            data-status="<?php echo strtolower($s['status']); ?>" 
                            data-date="<?php echo $s['date']; ?>">
                            
                            <td><span class="font-monospace text-primary-light fw-bold"><?php echo $s['id']; ?></span></td>
                            <td>
                                <div class="fw-semibold text-white"><?php echo date('M d, Y', strtotime($s['date'])); ?></div>
                                <div class="text-muted small"><?php echo $s['time']; ?></div>
                            </td>
                            <td>
                                <div class="fw-bold text-white"><?php echo $s['subject_code']; ?></div>
                                <div class="text-muted small"><?php echo $s['subject_name']; ?></div>
                            </td>
                            <td><span class="badge bg-dark border border-secondary"><?php echo $s['class']; ?></span></td>
                            <td><span class="text-truncate d-inline-block" style="max-width: 200px;"><?php echo $s['topic']; ?></span></td>
                            <td>
                                <span class="text-success fw-bold"><?php echo $s['present']; ?></span>
                                <span class="text-muted">/ <?php echo $s['total']; ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold <?php echo $s['percent'] < 80 ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo $s['percent']; ?>%
                                    </span>
                                    <div class="progress progress-dark flex-grow-1" style="width: 60px;">
                                        <div class="progress-bar <?php echo $s['percent'] < 80 ? 'bg-danger' : 'bg-success'; ?>" 
                                             role="progressbar" 
                                             style="width: <?php echo $s['percent']; ?>%" 
                                             aria-valuenow="<?php echo $s['percent']; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status badge-status-present">
                                    <i class="bi bi-check-circle-fill"></i> <?php echo $s['status']; ?>
                                </span>
                            </td>
                            <td class="text-end no-export">
                                <button class="btn btn-action-icon me-1" 
                                        title="View Student List"
                                        onclick="showSessionDetails(
                                            '<?php echo date('F d, Y', strtotime($s['date'])); ?>',
                                            '<?php echo $s['subject_code'] . ' - ' . $s['subject_name']; ?>',
                                            '<?php echo addslashes($s['topic']); ?>',
                                            '<?php echo $s['total']; ?>',
                                            '<?php echo $s['present']; ?>',
                                            '<?php echo $s['absent']; ?>'
                                        )">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-action-icon" title="Download Session CSV" onclick="exportTableToCSV('historyTable', 'Session_<?php echo $s['id']; ?>.csv')">
                                    <i class="bi bi-download"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Empty Search Results Message -->
            <div id="historyEmptyState" class="text-center py-5" style="display: none;">
                <i class="bi bi-search text-muted display-4 mb-3 d-block"></i>
                <h5 class="text-white">No attendance records found</h5>
                <p class="text-muted">Try adjusting your filters or date range.</p>
            </div>
        </div>
    </div>
</main>

<!-- Session Details Modal -->
<div class="modal fade modal-glass" id="sessionDetailModal" tabindex="-1" aria-labelledby="sessionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title text-white" id="sessionDetailModalLabel">Class Session Attendance Log</h5>
                    <div class="text-muted small" id="modalSessionDate">Jul 22, 2026</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <div class="text-muted small">Subject & Topic</div>
                            <div class="fw-bold text-white" id="modalSessionSubject">CS-101 Data Structures</div>
                            <div class="text-info small" id="modalSessionTopic">Binary Search Trees</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-dark border border-secondary d-flex justify-content-around text-center">
                            <div>
                                <div class="text-muted small">Total</div>
                                <div class="h5 mb-0 text-white" id="modalSessionTotal">60</div>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <div class="text-muted small">Present</div>
                                <div class="h5 mb-0 text-success" id="modalSessionPresent">56</div>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <div class="text-muted small">Absent</div>
                                <div class="h5 mb-0 text-danger" id="modalSessionAbsent">4</div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="text-muted uppercase mb-3">Student Attendance Roster</h6>
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="custom-report-table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Check-in Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CS2024-001</td>
                                <td>Aarav Sharma</td>
                                <td>09:28 AM</td>
                                <td><span class="badge-status badge-status-present">Present</span></td>
                            </tr>
                            <tr>
                                <td>CS2024-002</td>
                                <td>Ananya Verma</td>
                                <td>09:31 AM</td>
                                <td><span class="badge-status badge-status-late">Late</span></td>
                            </tr>
                            <tr>
                                <td>CS2024-003</td>
                                <td>Rohan Mehta</td>
                                <td>--</td>
                                <td><span class="badge-status badge-status-absent">Absent</span></td>
                            </tr>
                            <tr>
                                <td>CS2024-004</td>
                                <td>Priya Patel</td>
                                <td>09:25 AM</td>
                                <td><span class="badge-status badge-status-present">Present</span></td>
                            </tr>
                            <tr>
                                <td>CS2024-005</td>
                                <td>Devendra Singh</td>
                                <td>09:29 AM</td>
                                <td><span class="badge-status badge-status-present">Present</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary rounded-pill" onclick="exportTableToCSV('historyTable', 'Session_Roster.csv')">Export Roster CSV</button>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
