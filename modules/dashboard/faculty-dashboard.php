<?php
/**
 * AttendEase - Faculty Dashboard
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role'])) {
    header("Location: ../authentication/login.php");
    exit;
}

$faculty_id = $_SESSION['user_id'];
$faculty_name = $_SESSION['name'];

if ($_SESSION['role'] !== 'faculty') {
    // Fetch first faculty in database to populate dashboard for preview
    $stmt_f = $pdo->query("SELECT * FROM users WHERE role = 'faculty' LIMIT 1");
    $first_faculty = $stmt_f->fetch();
    if ($first_faculty) {
        $faculty_id = $first_faculty['id'];
        $faculty_name = $first_faculty['name'];
    }
}

// Assigned subjects count
$stmt_sub = $pdo->prepare("SELECT COUNT(*) FROM faculty_subjects WHERE faculty_id = ?");
$stmt_sub->execute([$faculty_id]);
$assigned_subjects_count = $stmt_sub->fetchColumn();

// Total students count
$total_students_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

// Weekly lectures count
$stmt_lectures = $pdo->prepare("SELECT COUNT(*) FROM schedules WHERE subject_id IN (SELECT subject_id FROM faculty_subjects WHERE faculty_id = ?)");
$stmt_lectures->execute([$faculty_id]);
$weekly_lectures_count = $stmt_lectures->fetchColumn();

// Todays completed and total count
$today_str = date('Y-m-d');
$stmt_comp = $pdo->prepare("SELECT COUNT(DISTINCT (subject_id || '-' || date)) FROM attendance WHERE marked_by = ? AND date = ?");
$stmt_comp->execute([$faculty_id, $today_str]);
$todays_completed_count = $stmt_comp->fetchColumn();

$todays_total_count = $weekly_lectures_count > 0 ? $weekly_lectures_count : 2;

$stmt_today_sched = $pdo->prepare("
    SELECT s.*, subj.name AS subject_name, subj.class AS subject_class
    FROM schedules s
    JOIN subjects subj ON s.subject_id = subj.id
    JOIN faculty_subjects fs ON subj.id = fs.subject_id
    WHERE fs.faculty_id = ?
");
$stmt_today_sched->execute([$faculty_id]);
$today_schedules = $stmt_today_sched->fetchAll();

$stmt_recent = $pdo->prepare("
    SELECT a.date, subj.name AS subject_name, u.class, u.division,
           SUM(CASE WHEN a.status = 'Present' THEN 1 ELSE 0 END) AS present_count,
           COUNT(a.id) AS total_count
    FROM attendance a
    JOIN subjects subj ON a.subject_id = subj.id
    JOIN users u ON a.student_id = u.id
    WHERE a.marked_by = ?
    GROUP BY a.date, a.subject_id, u.class, u.division
    ORDER BY a.date DESC
    LIMIT 5
");
$stmt_recent->execute([$faculty_id]);
$recent_submissions = $stmt_recent->fetchAll();

// Fetch pending condonations for alerts
$stmt_pending_cond = $pdo->query("
    SELECT c.*, u.name AS student_name, u.class AS student_class, u.division AS student_division
    FROM condonations c
    JOIN users u ON c.student_id = u.id
    WHERE c.status = 'Pending'
    ORDER BY c.submitted_at DESC
");
$pending_condonations = $stmt_pending_cond->fetchAll();

$page_title  = 'Faculty Dashboard';
include '../../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">
<style>
/* Interactive hover for stats */
.interactive-stat-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    cursor: pointer;
    text-decoration: none !important;
    display: flex;
    align-items: center;
    gap: 15px;
}
.interactive-stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(96, 165, 250, 0.3) !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25) !important;
}
</style>
<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <!-- ── Faculty Portal Layout ── -->
    <div class="faculty-portal-wrapper">

        <!-- Sidebar -->
        <?php include '../../includes/faculty-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">

                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-grid-1x2-fill"></i> Faculty Dashboard
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Overview</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <main class="faculty-content-body">

                <!-- Welcome Banner -->
                <div class="faculty-card welcome-banner-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i> Active Session</span>
                                <span class="faculty-badge badge-blue-subtle"><i class="bi bi-building"></i> CSE Department</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Welcome back, <?php echo htmlspecialchars($faculty_name); ?> 👋</h2>
                            <p class="mb-0" style="color:#94a3b8;font-size:.9rem;">
                                You have <strong style="color:#93c5fd;"><?php echo ($todays_total_count - $todays_completed_count); ?> class remaining</strong> to mark attendance for today.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                            <a href="<?php echo $base_path; ?>modules/attendance/faculty-daily-report.php" class="btn btn-outline-light rounded-pill px-3" style="font-size:.85rem;"><i class="bi bi-file-earmark-pdf me-1"></i> Generate Report</a>
                            <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php" class="btn btn-premium"><i class="bi bi-check2-square"></i> Mark Attendance</a>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <!-- Assigned Subjects Card -->
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/subjects/faculty-subject-allocation.php" class="faculty-stat-card interactive-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $assigned_subjects_count; ?></div><div class="faculty-stat-lbl">Assigned Subjects</div></div>
                        </a>
                    </div>
                    <!-- Total Students Card -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card interactive-stat-card" data-bs-toggle="modal" data-bs-target="#studentsModal">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-people-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $total_students_count; ?></div><div class="faculty-stat-lbl">Total Students</div></div>
                        </div>
                    </div>
                    <!-- Weekly Classes Card -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card interactive-stat-card" data-bs-toggle="modal" data-bs-target="#weeklyClassesModal">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-calendar-check-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $weekly_lectures_count; ?></div><div class="faculty-stat-lbl">Weekly Classes</div></div>
                        </div>
                    </div>
                    <!-- Today's Attendance Card -->
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php" class="faculty-stat-card interactive-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-clock-history"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $todays_completed_count; ?>/<?php echo $todays_total_count; ?></div><div class="faculty-stat-lbl">Today's Attendance</div></div>
                        </a>
                    </div>
                </div>

                <!-- Schedule & Quick Actions -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-calendar-event" style="color:#60a5fa;"></i> Today's Teaching Schedule</h3>
                                    <p class="faculty-card-subtitle">Lectures for <?php echo date('F j, Y'); ?></p>
                                </div>
                                <a href="<?php echo $base_path; ?>modules/subjects/faculty-subject-allocation.php" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:.8rem;">View All</a>
                            </div>
                            <div class="faculty-table-responsive">
                                <table class="faculty-table">
                                    <thead><tr><th>Time / Slot</th><th>Subject</th><th>Class</th><th>Status</th><th class="text-end">Action</th></tr></thead>
                                    <tbody>
                                        <?php if (empty($today_schedules)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-secondary py-4">No scheduled lectures today.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($today_schedules as $sched): ?>
                                            <tr>
                                                <td><div class="fw-semibold" style="color:#f1f5f9;"><?php echo htmlspecialchars($sched['start_time'] . ' – ' . $sched['end_time']); ?></div><small style="color:#64748b;"><?php echo htmlspecialchars($sched['day_of_week']); ?></small></td>
                                                <td><span style="color:#f1f5f9;font-weight:600;"><?php echo htmlspecialchars($sched['subject_name']); ?></span><br><small style="color:#64748b;">Lecture</small></td>
                                                <td><span class="faculty-badge badge-info-subtle"><?php echo htmlspecialchars($sched['class'] . ' – Div ' . $sched['division']); ?></span></td>
                                                <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Scheduled</span></td>
                                                <td class="text-end">
                                                    <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php?subject_id=<?php echo $sched['subject_id']; ?>&division=<?php echo urlencode($sched['division']); ?>&class=<?php echo urlencode($sched['class']); ?>" class="btn btn-sm btn-premium py-1 px-3" style="font-size:.78rem;"><i class="bi bi-check-lg me-1"></i>Mark Now</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="faculty-card mb-4">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-lightning-charge-fill" style="color:#fbbf24;"></i> Quick Actions</h3>
                            </div>
                            <div class="d-flex flex-column gap-3 p-3">
                                <a href="<?php echo $base_path; ?>modules/attendance/faculty-edit-attendance.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(14,165,233,.12);border:1px solid rgba(56,189,248,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="bi bi-pencil-square text-white"></i></div>
                                        <div class="text-start"><h6 class="mb-0 fw-semibold" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Edit Attendance</h6><small style="color:#64748b;">Modify within 48 hours</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Student Condonations Alerts -->
                        <div class="faculty-card mb-0">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-bell-fill" style="color:#f59e0b;"></i> Condonation Alerts</h3>
                                <p class="faculty-card-subtitle">Pending HOD/Admin approvals</p>
                            </div>
                            <div class="p-3" style="max-height: 260px; overflow-y: auto;" id="condonationAlertsScroll">
                                <?php if (empty($pending_condonations)): ?>
                                    <div class="text-center py-3 text-secondary" style="font-size: 0.82rem;">
                                        No pending student condonation applications.
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($pending_condonations as $p_cond): ?>
                                        <div class="p-2.5 rounded-3 mb-2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="fw-semibold text-white" style="font-size: 0.85rem;"><?php echo htmlspecialchars($p_cond['student_name']); ?></span>
                                                <span class="badge bg-warning-subtle text-warning" style="font-size: 0.7rem;">Pending</span>
                                            </div>
                                            <div style="font-size: 0.78rem; color: #94a3b8;">
                                                Applied for <strong class="text-info"><?php echo htmlspecialchars($p_cond['type']); ?> Leave</strong> on <?php echo htmlspecialchars($p_cond['date']); ?>.
                                            </div>
                                            <div class="text-muted mt-1" style="font-size: 0.72rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                                Reason: <?php echo htmlspecialchars($p_cond['reason']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($pending_condonations)): ?>
                            <div class="px-3 pb-3 pt-2 text-center" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                <a href="javascript:void(0)" onclick="document.getElementById('condonationAlertsScroll').scrollBy({top: 100, behavior: 'smooth'})" class="text-info text-decoration-none fw-semibold" style="font-size: 0.8rem; transition: all 0.2s;">See More <i class="bi bi-chevron-down ms-1"></i></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="faculty-card mb-0 mt-4">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-activity" style="color:#38bdf8;"></i> Recent Attendance Submissions</h3>
                        <span class="faculty-badge badge-secondary-subtle">Last 24 Hours</span>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead><tr><th>Time</th><th>Subject</th><th>Class</th><th>Present / Total</th><th>Rate</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php if (empty($recent_submissions)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-4">No recent attendance submissions.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_submissions as $rec): 
                                        $rate = $rec['total_count'] > 0 ? round(($rec['present_count'] / $rec['total_count']) * 100, 1) : 100;
                                    ?>
                                    <tr>
                                        <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($rec['date']); ?></td>
                                        <td><span style="color:#f1f5f9;font-weight:600;"><?php echo htmlspecialchars($rec['subject_name']); ?></span></td>
                                        <td><?php echo htmlspecialchars($rec['class'] . ' – Div ' . $rec['division']); ?></td>
                                        <td><span style="color:#34d399;font-weight:700;"><?php echo $rec['present_count']; ?></span> / <?php echo $rec['total_count']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;">
                                                    <div class="progress-bar bg-success" style="width:<?php echo $rate; ?>%;border-radius:3px;"></div>
                                                </div>
                                                <small class="fw-bold" style="color:#f1f5f9;"><?php echo $rate; ?>%</small>
                                            </div>
                                        </td>
                                        <td><span class="faculty-badge badge-success-subtle">Saved</span></td>
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

<!-- Modal: Total Students Details -->
<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(6, 182, 212, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="studentsModalLabel"><i class="bi bi-people-fill text-info me-2"></i> Enrolled Students Breakdown</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">TE CSE – Division A</span>
                        <span class="badge bg-info-subtle text-info">60 Students</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">TE CSE – Division B</span>
                        <span class="badge bg-info-subtle text-info">60 Students</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">BE CSE – Division A</span>
                        <span class="badge bg-info-subtle text-info">60 Students</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white fw-semibold">SE CSE – Division C</span>
                        <span class="badge bg-info-subtle text-info">60 Students</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Weekly Classes Details -->
<div class="modal fade" id="weeklyClassesModal" tabindex="-1" aria-labelledby="weeklyClassesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="weeklyClassesModalLabel"><i class="bi bi-calendar-check-fill text-success me-2"></i> Weekly Classes Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">Monday</span>
                        <span class="badge bg-success-subtle text-success">4 Lectures</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">Tuesday</span>
                        <span class="badge bg-success-subtle text-success">4 Lectures</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">Wednesday</span>
                        <span class="badge bg-success-subtle text-success">3 Lectures</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom border-light-subtle pb-2">
                        <span class="text-white fw-semibold">Thursday</span>
                        <span class="badge bg-success-subtle text-success">4 Lectures</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white fw-semibold">Friday</span>
                        <span class="badge bg-success-subtle text-success">3 Lectures</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === '1') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Attendance marked successfully!',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        window.history.replaceState(null, '', window.location.pathname);
    }
    if (urlParams.has('updated') && urlParams.get('updated') === '1') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Attendance updated successfully!',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        window.history.replaceState(null, '', window.location.pathname);
    }
});
</script>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
