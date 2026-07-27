<?php 
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Monthly Attendance Report | Faculty Reports";
include '../includes/header.php'; 

// Fetch dynamic monthly data from SQLite
$stmt_monthly = $pdo->query("
    SELECT u.zprn, u.name, u.department, u.class, s.name AS subject_name, s.id AS subject_id,
           COUNT(a.id) AS total_count,
           SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM users u
    CROSS JOIN subjects s
    LEFT JOIN attendance a ON u.id = a.student_id AND s.id = a.subject_id
    WHERE u.role = 'student'
    GROUP BY u.id, s.id
    ORDER BY u.name ASC, s.name ASC
");
$db_records = $stmt_monthly->fetchAll();

$monthly_records = [];
foreach ($db_records as $row) {
    $held = $row['total_count'];
    $attended = $row['present_count'];
    $percent = $held > 0 ? round(($attended / $held) * 100, 1) : 100.0;
    
    $status = 'Safe';
    if ($held > 0) {
        if ($percent < 75.0) {
            $status = 'Defaulter';
        } else if ($percent < 80.0) {
            $status = 'Warning';
        }
    }
    
    $monthly_records[] = [
        'roll_no' => $row['zprn'],
        'name' => $row['name'],
        'prn' => $row['zprn'],
        'dept' => $row['department'] ?? 'AI&ML',
        'sem' => ($row['class'] === 'First Year') ? 'Sem I' : (($row['class'] === 'Second Year') ? 'Sem III' : (($row['class'] === 'Third Year') ? 'Sem V' : 'Sem VII')),
        'course' => 'SUBJ-' . $row['subject_id'] . ' ' . $row['subject_name'],
        'held' => $held,
        'attended' => $attended,
        'percent' => $percent,
        'status' => $status
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
                <a href="reports/attendance-history.php" class="nav-link">
                    <i class="bi bi-clock-history"></i> Attendance History
                </a>
                <a href="reports/monthly-report.php" class="nav-link active">
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
        <div class="glass-card p-0 overflow-hidden mb-4">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                <h5 class="text-white mb-0"><i class="bi bi-table text-primary me-2"></i> Monthly Student Data</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-danger px-3 py-2" id="btn-export-pdf" style="font-size: 0.8rem;"><i class="bi bi-file-pdf-fill me-1"></i> Export PDF</button>
                    <button class="btn btn-sm btn-outline-success px-3 py-2" id="btn-export-excel" style="font-size: 0.8rem;"><i class="bi bi-file-excel-fill me-1"></i> Export Excel</button>
                </div>
            </div>
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

<!-- Include html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; text-transform: uppercase;">Monthly Attendance Report</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: 12px; color: #64748b;">Report Date</p>
                        <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 600; color: #0f172a;">${new Date().toLocaleDateString()}</p>
                    </div>
                </div>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
                    <thead>
                        <tr style="background-color: #0f172a; color: #ffffff;">
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Roll No</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Student Name</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Dept / Sem</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Classes Held</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Attended</th>
                            <th style="padding: 12px; text-align: left; font-size: 12px;">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Array.from(document.querySelectorAll('#monthlyTable tbody tr.data-row')).map(row => {
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
                                    <td style="padding: 12px; font-size: 12px;">${cells[5].innerText.replace(/[\\n\\r]+.*/g, '')}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;

            const opt = {
                margin:       10,
                filename:     'Monthly_Attendance_Report.pdf',
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
            csvContent += "Roll No,Student Name,Dept / Sem,Classes Held,Attended,Percentage,Status\\r\\n";
            
            Array.from(document.querySelectorAll('#monthlyTable tbody tr.data-row')).forEach(row => {
                if (row.style.display === 'none') return;
                const cells = row.querySelectorAll('td');
                if (cells.length < 7) return;
                
                const c0 = `"${cells[0].innerText.replace(/"/g, '""')}"`;
                const c1 = `"${cells[1].innerText.replace(/\\n/g, ' ').replace(/"/g, '""')}"`;
                const c2 = `"${cells[2].innerText.replace(/\\n/g, ' ').replace(/"/g, '""')}"`;
                const c3 = `"${cells[3].innerText.replace(/"/g, '""')}"`;
                const c4 = `"${cells[4].innerText.replace(/"/g, '""')}"`;
                const c5 = `"${cells[5].innerText.replace(/[\\n\\r]+.*/g, '').replace(/"/g, '""')}"`;
                const c6 = `"${cells[6].innerText.replace(/\\n/g, ' ').replace(/"/g, '""')}"`;
                
                csvContent += `${c0},${c1},${c2},${c3},${c4},${c5},${c6}\\r\\n`;
            });
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Monthly_Attendance_Report.csv");
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
