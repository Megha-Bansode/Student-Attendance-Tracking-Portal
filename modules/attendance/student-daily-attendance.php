<?php
/**
 * AttendEase - Student Daily Attendance Log
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role'])) {
    header("Location: ../authentication/login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$student_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$student_zprn = isset($_SESSION['zprn']) ? $_SESSION['zprn'] : '';
$student_class = isset($_SESSION['class']) ? $_SESSION['class'] : '';
$student_division = isset($_SESSION['division']) ? $_SESSION['division'] : '';

if ($_SESSION['role'] !== 'student') {
    // Fetch first student in database to populate dashboard for preview
    $stmt_s = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
    $first_student = $stmt_s->fetch();
    if ($first_student) {
        $student_id = $first_student['id'];
        $student_name = $first_student['name'];
        $student_zprn = $first_student['zprn'];
        $student_class = $first_student['class'];
        $student_division = $first_student['division'];
    }
}

$filter_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Fetch schedules for the student's class and division for the given day of the week
$day_of_week = date('l', strtotime($filter_date)); 

$query = "
    SELECT s.*, subj.name AS subject_name, u.name AS faculty_name
    FROM schedules s
    JOIN subjects subj ON s.subject_id = subj.id
    LEFT JOIN faculty_subjects fs ON subj.id = fs.subject_id
    LEFT JOIN users u ON fs.faculty_id = u.id
    WHERE s.division = :division AND s.class = :class AND s.day_of_week = :day
    ORDER BY s.start_time ASC
";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'division' => $student_division,
    'class' => $student_class,
    'day' => $day_of_week
]);
$schedules = $stmt->fetchAll();

// Fetch attendance records for this date
$stmt_att = $pdo->prepare("SELECT subject_id, status FROM attendance WHERE student_id = ? AND date = ?");
$stmt_att->execute([$student_id, $filter_date]);
$attendance_records = [];
while ($row = $stmt_att->fetch()) {
    $attendance_records[$row['subject_id']] = $row['status'];
}

$page_title  = 'Daily Attendance';
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

        <!-- Sidebar -->
        <?php include '../../includes/student-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-calendar-event"></i> Daily Attendance
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Daily Log</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <main class="faculty-content-body">
                
                <!-- Filters Card -->
                <div class="faculty-card mb-4">
                    <div class="p-3">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="attendanceDate" class="form-label" style="color: #cbd5e1; font-weight: 500; font-size: 0.85rem;">Select Date</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8;"><i class="bi bi-calendar-week"></i></span>
                                    <input type="date" class="form-control" id="attendanceDate" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : date('Y-m-d'); ?>" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label" style="color: #cbd5e1; font-weight: 500; font-size: 0.85rem;">Status</label>
                                <select class="form-select" id="statusFilter" name="status" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                    <option value="all" <?php echo $filter_status == 'all' ? 'selected' : ''; ?>>All Statuses</option>
                                    <option value="present" <?php echo $filter_status == 'present' ? 'selected' : ''; ?>>Present</option>
                                    <option value="absent" <?php echo $filter_status == 'absent' ? 'selected' : ''; ?>>Absent</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-premium w-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;"><i class="bi bi-funnel-fill me-2"></i> Filter Records</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daily Attendance Records -->
                <div class="faculty-card">
                    <div class="faculty-card-header">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-clock-history" style="color:#f59e0b;"></i> Daily Check-in Logs</h3>
                            <p class="faculty-card-subtitle">Showing logs for <?php echo isset($_GET['date']) ? date('F j, Y', strtotime($_GET['date'])) : date('F j, Y'); ?></p>
                        </div>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Slot / Time</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($schedules)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">No lectures scheduled for this date.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php 
                                    $slot = 1;
                                    $has_records = false;
                                    foreach ($schedules as $sched): 
                                        $status = isset($attendance_records[$sched['subject_id']]) ? $attendance_records[$sched['subject_id']] : 'Scheduled';
                                        
                                        // Filter by status if needed
                                        if ($filter_status !== 'all' && strtolower($status) !== strtolower($filter_status)) {
                                            continue;
                                        }
                                        $has_records = true;

                                        $badge_class = ($status === 'Present') ? 'badge-success-subtle' : (($status === 'Absent') ? 'badge-danger-subtle' : 'badge-warning-subtle');
                                        $badge_icon = ($status === 'Present') ? 'bi-check-circle-fill' : (($status === 'Absent') ? 'bi-x-circle-fill' : 'bi-clock-history');
                                    ?>
                                    <tr>
                                        <td><div class="fw-semibold text-white"><?php echo htmlspecialchars($sched['start_time'] . ' – ' . $sched['end_time']); ?></div><small style="color:#64748b;">Slot <?php echo $slot++; ?></small></td>
                                        <td><span class="faculty-badge badge-blue-subtle">SUB<?php echo $sched['subject_id']; ?></span></td>
                                        <td><span style="color:#f1f5f9;font-weight:600;"><?php echo htmlspecialchars($sched['subject_name']); ?></span></td>
                                        <td><?php echo htmlspecialchars($sched['faculty_name'] ?? 'Not Assigned'); ?></td>
                                        <td><span class="faculty-badge <?php echo $badge_class; ?>"><i class="bi <?php echo $badge_icon; ?> me-1"></i><?php echo htmlspecialchars($status); ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (!$has_records): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-secondary py-4">No records match your filter criteria.</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
