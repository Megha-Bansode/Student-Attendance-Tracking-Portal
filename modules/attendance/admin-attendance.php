<?php
/**
 * AttendEase - Super Admin Attendance Audit & Management
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit;
}

// Fetch stats
$today_date = date('Y-m-d');
$stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ? AND status = 'Present'");
$stmt_pres->execute([$today_date]);
$pres_today = $stmt_pres->fetchColumn();

$stmt_tot = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ?");
$stmt_tot->execute([$today_date]);
$tot_today = $stmt_tot->fetchColumn();

$today_presence_rate = $tot_today > 0 ? round(($pres_today / $tot_today) * 100, 1) . "%" : "100%";
$lectures_conducted = $pdo->query("SELECT COUNT(DISTINCT (date || '-' || subject_id)) FROM attendance")->fetchColumn();

// Fetch dynamic stream records
$stmt_stream = $pdo->query("
    SELECT a.date, subj.name AS subject_name, u.class, u.division, u.department,
           fac.name AS faculty_name,
           SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_count,
           COUNT(a.id) AS total_count
    FROM attendance a
    JOIN subjects subj ON a.subject_id = subj.id
    JOIN users u ON a.student_id = u.id
    LEFT JOIN users fac ON a.marked_by = fac.id
    GROUP BY a.date, a.subject_id, u.class, u.division
    ORDER BY a.date DESC
");
$stream_records = $stmt_stream->fetchAll();

$page_title       = 'Attendance Audit & Records';
$page_breadcrumb  = 'AttendEase / Super Admin / Attendance Audit';
$page_icon        = 'bi-calendar-check-fill';

include '../../includes/header.php';
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
        <?php include '../../includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Band -->
            <?php include '../../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-calendar-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $today_presence_rate; ?></div>
                                <div class="faculty-stat-lbl">Today's Presence Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-journal-check"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $lectures_conducted; ?></div>
                                <div class="faculty-stat-lbl">Lectures Conducted</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-pencil-square"></i></div>
                            <div>
                                <div class="faculty-stat-val">0 Pending</div>
                                <div class="faculty-stat-lbl">Edit Approval Requests</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-shield-lock-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">48 Hours</div>
                                <div class="faculty-stat-lbl">Locking Window</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Request Approvals Card -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-pencil-square" style="color:#fbbf24;"></i> Pending Faculty Correction Requests</h3>
                            <p class="faculty-card-subtitle">Review &amp; approve historical attendance edit requests older than 48 hours</p>
                        </div>
                        <span class="faculty-badge badge-warning-subtle">0 Approvals Action Needed</span>
                    </div>

                    <div class="faculty-table-responsive text-center py-4 text-muted">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No pending faculty correction requests found.</p>
                    </div>
                </div>

                <!-- Daily Audit Logs Table -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-clock-history" style="color:#38bdf8;"></i> Institution Daily Attendance Stream</h3>
                            <p class="faculty-card-subtitle">Real-time record of all class attendance submissions across departments</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" id="streamDateFilter" class="form-control form-control-sm text-light border-secondary" value="<?php echo date('Y-m-d'); ?>" style="background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="streamTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Subject</th>
                                    <th>Faculty</th>
                                    <th>Present / Total</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stream_records)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary py-4">No attendance streams logged yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stream_records as $rec): 
                                        $percentage = $rec['total_count'] > 0 ? round(($rec['present_count'] / $rec['total_count']) * 100, 1) : 100;
                                    ?>
                                    <tr data-date="<?php echo htmlspecialchars($rec['date']); ?>">
                                        <td><?php echo htmlspecialchars($rec['date']); ?></td>
                                        <td><?php echo htmlspecialchars($rec['department'] ?? 'Unassigned'); ?></td>
                                        <td><?php echo htmlspecialchars($rec['subject_name']); ?></td>
                                        <td><?php echo htmlspecialchars($rec['faculty_name'] ?? 'Not Assigned'); ?></td>
                                        <td><span class="fw-bold text-success"><?php echo $rec['present_count']; ?></span> / <?php echo $rec['total_count']; ?></td>
                                        <td><?php echo $percentage; ?>%</td>
                                        <td><span class="faculty-badge badge-success-subtle">Verified</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script>
// Filter table by Date
document.getElementById('streamDateFilter').addEventListener('change', function() {
    const selectedDate = this.value;
    const rows = document.querySelectorAll('#streamTable tbody tr');
    rows.forEach(row => {
        const rowDate = row.getAttribute('data-date');
        if (!selectedDate || rowDate === selectedDate) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
