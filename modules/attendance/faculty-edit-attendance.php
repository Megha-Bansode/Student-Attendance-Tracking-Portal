<?php
/**
 * AttendEase - Faculty Edit Attendance Page
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

$selected_subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : (count($fac_subjects) > 0 ? $fac_subjects[0]['id'] : 0);
// Default classes
$default_class = 'First Year';
$default_div = 'B';

if ($selected_subject_id > 0) {
    foreach ($fac_subjects as $fs) {
        if ($fs['id'] == $selected_subject_id) {
            $default_class = $fs['class'];
            break;
        }
    }
}

// Fetch faculty profile for division default
$stmt_fac = $pdo->prepare("SELECT class, division FROM users WHERE id = ?");
$stmt_fac->execute([$faculty_id]);
$fac_info = $stmt_fac->fetch();
if ($fac_info && !empty($fac_info['division'])) {
    $default_div = $fac_info['division'];
}

$selected_div        = isset($_GET['div'])        ? $_GET['div']        : $default_div;
$selected_class      = isset($_GET['class'])      ? $_GET['class']      : $default_class;
$selected_date       = isset($_GET['date'])       ? $_GET['date']       : date('Y-m-d');

$is_search_active = ($selected_subject_id > 0 && !empty($selected_div));

// Fetch students and their current attendance records for selected slot/date
$students_att = [];
if ($is_search_active) {
    $stmt_att = $pdo->prepare("
        SELECT u.id AS student_id, u.name, u.zprn, a.status AS attendance_status
        FROM users u
        LEFT JOIN attendance a ON u.id = a.student_id AND a.date = ? AND a.subject_id = ?
        WHERE u.role = 'student' AND u.class = ? AND u.division = ?
        ORDER BY u.name ASC
    ");
    $stmt_att->execute([$selected_date, $selected_subject_id, $selected_class, $selected_div]);
    $students_att = $stmt_att->fetchAll();
}

// Process Edit Form Submission
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
        $status_capitalized = ($status === 'Present' || $status === 'present') ? 'Present' : 'Absent';
        $stmt_ins->execute([intval($student_id), $subject_id, $attendance_date, $status_capitalized, $faculty_id]);
    }
    header("Location: ../dashboard/faculty-dashboard.php?updated=1");
    exit;
}

$page_title = 'Edit Attendance';
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
                    <h1 class="faculty-page-band-title"><i class="bi bi-pencil-square"></i> Edit Attendance</h1>
                    <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Edit Attendance</p>
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
                        <h2 class="h5 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;"><i class="bi bi-pencil-square me-2" style="color:#38bdf8;"></i>Modify Previous Records</h2>
                        <p class="mb-0" style="color:#64748b;font-size:.875rem;">You can edit submitted attendance records within <strong style="color:#93c5fd;">48 hours</strong> of the original session time.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="faculty-badge badge-warning-subtle" style="font-size:.82rem;padding:.45rem .85rem;"><i class="bi bi-shield-lock me-1"></i> Audit Trail Active</span>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <form action="faculty-edit-attendance.php" method="GET" class="mb-4">
                <div class="faculty-card">
                    <div class="faculty-card-header mb-3 pb-2">
                        <h3 class="faculty-card-title"><i class="bi bi-search" style="color:#818cf8;"></i> Find Submitted Record</h3>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-xl-3 col-md-6">
                            <label for="editDateInput" class="faculty-form-label"><i class="bi bi-calendar3 me-1" style="color:#60a5fa;"></i> Date</label>
                            <input type="date" id="editDateInput" name="date" class="faculty-input" value="<?php echo htmlspecialchars($selected_date); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <label for="editSubjectSelect" class="faculty-form-label"><i class="bi bi-journal-text me-1" style="color:#60a5fa;"></i> Subject</label>
                            <select id="editSubjectSelect" name="subject_id" class="faculty-select" required>
                                <?php foreach ($fac_subjects as $fs): ?>
                                    <option value="<?php echo $fs['id']; ?>" <?php echo ($selected_subject_id == $fs['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($fs['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <label for="editDivisionSelect" class="faculty-form-label"><i class="bi bi-building me-1" style="color:#60a5fa;"></i> Division</label>
                            <select id="editDivisionSelect" name="div" class="faculty-select" required>
                                <option value="A" <?php echo ($selected_div==='A')?'selected':''; ?>>Division A</option>
                                <option value="B" <?php echo ($selected_div==='B')?'selected':''; ?>>Division B</option>
                            </select>
                        </div>
                        <input type="hidden" name="class" value="<?php echo htmlspecialchars($selected_class); ?>">
                        <div class="col-xl-2 col-md-6">
                            <button type="submit" class="btn btn-premium w-100"><i class="bi bi-search me-1"></i> Fetch</button>
                        </div>
                    </div>
                </div>
            </form>

            <?php if ($is_search_active): ?>
            <!-- Edit Form -->
            <form id="markAttendanceForm" action="faculty-edit-attendance.php" method="POST">
                <input type="hidden" name="attendance_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($selected_subject_id); ?>">
                <input type="hidden" name="class" value="<?php echo htmlspecialchars($selected_class); ?>">
                <input type="hidden" name="division" value="<?php echo htmlspecialchars($selected_div); ?>">

                <div class="faculty-card">
                    <!-- Sheet header with live counters -->
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,.06);">
                        <div>
                            <h3 class="faculty-card-title mb-1"><i class="bi bi-pencil-square" style="color:#38bdf8;"></i> Updating Record: Division <?php echo htmlspecialchars($selected_div); ?></h3>
                            <p class="faculty-card-subtitle">Originally marked on <?php echo date('M d, Y', strtotime($selected_date)); ?></p>
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
                                <?php if (empty($students_att)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary py-4">No student records found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students_att as $s): 
                                        $is_present = ($s['attendance_status'] === 'Present' || $s['attendance_status'] === null); // default to present if not marked
                                    ?>
                                        <tr>
                                            <td class="fw-bold" style="color:#f1f5f9;"><?php echo htmlspecialchars($s['zprn']); ?></td>
                                            <td style="color:#f1f5f9;font-weight:500;"><i class="bi bi-person-circle me-2" style="color:#475569;"></i><?php echo htmlspecialchars($s['name']); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $s['student_id']; ?>]" id="pres_<?php echo $s['student_id']; ?>" value="Present" <?php echo $is_present ? 'checked' : ''; ?>>
                                                        <label class="form-check-label text-success fw-bold" for="pres_<?php echo $s['student_id']; ?>">Present</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $s['student_id']; ?>]" id="abs_<?php echo $s['student_id']; ?>" value="Absent" <?php echo !$is_present ? 'checked' : ''; ?>>
                                                        <label class="form-check-label text-danger fw-bold" for="abs_<?php echo $s['student_id']; ?>">Absent</label>
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
                        <?php if (!empty($students_att)): ?>
                            <button type="submit" class="btn btn-premium px-4 py-2" id="btnSubmitAttendance">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save changes
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </form>
            <?php endif; ?>

        </main>

    </div>
</div>
</div>
<?php include '../../includes/footer.php'; ?>
