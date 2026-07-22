<?php
/**
 * AttendEase - Faculty Attendance Marking Page
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../authentication/login.php");
    exit;
}

$faculty_id = $_SESSION['user_id'];
$faculty_name = $_SESSION['name'];

// Fetch Assigned Subjects
$stmt_fac_sub = $pdo->prepare("
    SELECT s.* FROM subjects s
    JOIN faculty_subjects fs ON s.id = fs.subject_id
    WHERE fs.faculty_id = ?
");
$stmt_fac_sub->execute([$faculty_id]);
$fac_subjects = $stmt_fac_sub->fetchAll();

// Get input options
$selected_subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : (count($fac_subjects) > 0 ? $fac_subjects[0]['id'] : 0);
$selected_div        = isset($_GET['division'])   ? $_GET['division']   : 'A';
$selected_class      = isset($_GET['class'])      ? $_GET['class']      : 'AI&ML';
$selected_date       = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : date('Y-m-d');

// Process submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance_date = $_POST['attendance_date'];
    $subject_id      = intval($_POST['subject_id']);
    $division        = $_POST['division'];
    $class           = $_POST['class'];
    $attendance_data = isset($_POST['attendance']) ? $_POST['attendance'] : [];

    // Find students of this class and division
    $stmt_s = $pdo->prepare("SELECT id FROM users WHERE role = 'student' AND class = ? AND division = ?");
    $stmt_s->execute([$class, $division]);
    $student_ids = $stmt_s->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($student_ids)) {
        $in_query = implode(',', array_fill(0, count($student_ids), '?'));
        $stmt_del = $pdo->prepare("DELETE FROM attendance WHERE date = ? AND subject_id = ? AND student_id IN ($in_query)");
        $stmt_del->execute(array_merge([$attendance_date, $subject_id], $student_ids));
    }

    $stmt_ins = $pdo->prepare("INSERT INTO attendance (student_id, subject_id, date, status, marked_by) VALUES (?, ?, ?, ?, ?)");
    foreach ($attendance_data as $student_id => $status) {
        $status_capitalized = ($status === 'present' || $status === 'Present') ? 'Present' : 'Absent';
        $stmt_ins->execute([intval($student_id), $subject_id, $attendance_date, $status_capitalized, $faculty_id]);
    }
    header("Location: ../dashboard/faculty-dashboard.php?success=1");
    exit;
}

// Fetch Students to list
$stmt_students = $pdo->prepare("
    SELECT * FROM users
    WHERE role = 'student' AND class = ? AND division = ?
    ORDER BY name ASC
");
$stmt_students->execute([$selected_class, $selected_div]);
$students = $stmt_students->fetchAll();

$page_title = 'Mark Attendance';
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
    <?php include '../../includes/faculty-sidebar.php'; ?>

    <div class="faculty-main-content">

        <!-- Page Band -->
        <div class="faculty-page-band">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="faculty-page-band-title"><i class="bi bi-check-circle-fill"></i> Mark Attendance</h1>
                    <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Mark Attendance</p>
                </div>
            </div>
            <div class="faculty-page-band-right">
                <div class="topbar-date-pill"><i class="bi bi-calendar3"></i><span><?php echo date('D, M d, Y'); ?></span></div>
            </div>
        </div>

        <main class="faculty-content-body">

            <!-- Page Intro -->
            <div class="faculty-card mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="h5 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;"><i class="bi bi-check-circle-fill me-2" style="color:#34d399;"></i>Mark Class Attendance</h2>
                        <p class="mb-0" style="color:#64748b;font-size:.875rem;">Select session details, review student statuses, and submit attendance records.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="faculty-badge badge-blue-subtle" style="font-size:.82rem;padding:.45rem .85rem;"><i class="bi bi-clock-history me-1"></i> Real-time Marking Mode</span>
                    </div>
                </div>
            </div>

            <!-- Context Filter Form -->
            <form method="GET" action="faculty-mark-attendance.php" class="faculty-card mb-4">
                <div class="faculty-card-header mb-3 pb-2">
                    <h3 class="faculty-card-title"><i class="bi bi-funnel-fill" style="color:#818cf8;"></i> Step 1 — Select Class &amp; Subject</h3>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="faculty-form-label">Date</label>
                        <input type="date" name="attendance_date" class="faculty-input" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Subject</label>
                        <select name="subject_id" class="faculty-select" onchange="this.form.submit()">
                            <?php foreach ($fac_subjects as $fs): ?>
                                <option value="<?php echo $fs['id']; ?>" <?php echo ($selected_subject_id == $fs['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fs['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Class</label>
                        <input type="text" name="class" class="faculty-input" value="<?php echo htmlspecialchars($selected_class); ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Division</label>
                        <select name="division" class="faculty-select" onchange="this.form.submit()">
                            <option value="A" <?php echo ($selected_div === 'A') ? 'selected' : ''; ?>>Division A</option>
                            <option value="B" <?php echo ($selected_div === 'B') ? 'selected' : ''; ?>>Division B</option>
                        </select>
                    </div>
                </div>
            </form>

            <form id="markAttendanceForm" action="faculty-mark-attendance.php" method="POST">
                <input type="hidden" name="attendance_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($selected_subject_id); ?>">
                <input type="hidden" name="class" value="<?php echo htmlspecialchars($selected_class); ?>">
                <input type="hidden" name="division" value="<?php echo htmlspecialchars($selected_div); ?>">

                <!-- Step 2 – Student Sheet -->
                <div class="faculty-card">
                    <!-- Sheet header with live counters -->
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,.06);">
                        <div>
                            <h3 class="faculty-card-title mb-1"><i class="bi bi-people-fill" style="color:#38bdf8;"></i> Step 2 — Student Attendance Sheet</h3>
                            <p class="faculty-card-subtitle">Toggle Present / Absent for each student of Division <?php echo htmlspecialchars($selected_div); ?></p>
                        </div>
                        <div class="counter-box-group">
                            <div class="counter-pill">
                                <span style="color:#64748b;">Total</span>
                                <span class="counter-pill-val" style="color:#f1f5f9;" id="statTotalStudents"><?php echo count($students); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Student Table -->
                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="studentAttendanceTable">
                            <thead>
                                <tr>
                                    <th>Roll / PRN</th>
                                    <th>Student Name</th>
                                    <th class="text-center" style="width:210px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary py-4">No students registered in Division <?php echo htmlspecialchars($selected_div); ?>.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $s): ?>
                                        <tr>
                                            <td class="fw-bold" style="color:#f1f5f9;"><?php echo htmlspecialchars($s['zprn']); ?></td>
                                            <td style="color:#f1f5f9;font-weight:500;"><i class="bi bi-person-circle me-2" style="color:#475569;"></i><?php echo htmlspecialchars($s['name']); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $s['id']; ?>]" id="pres_<?php echo $s['id']; ?>" value="Present" checked>
                                                        <label class="form-check-label text-success fw-bold" for="pres_<?php echo $s['id']; ?>">Present</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $s['id']; ?>]" id="abs_<?php echo $s['id']; ?>" value="Absent">
                                                        <label class="form-check-label text-danger fw-bold" for="abs_<?php echo $s['id']; ?>">Absent</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Row -->
                    <div class="d-flex align-items-center justify-content-between pt-4 mt-2" style="border-top:1px solid rgba(255,255,255,.06);">
                        <a href="../dashboard/faculty-dashboard.php" class="btn btn-outline-light rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Back</a>
                        <?php if (!empty($students)): ?>
                            <button type="submit" class="btn btn-premium px-4 py-2" id="btnSubmitAttendance">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save &amp; Submit Attendance
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </form>

        </main>

    </div>
</div>
</div>
<?php include '../../includes/footer.php'; ?>
