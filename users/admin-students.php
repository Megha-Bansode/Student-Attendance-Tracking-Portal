<?php
/**
 * AttendEase - Super Admin Student Registration & Directory
 * Professional Bootstrap 5 layout with zero button overlaps & clean table controls
 */
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../modules/authentication/login.php");
    exit;
}

// Handle Add/Edit Student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_student') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $zprn = trim($_POST['zprn']);
    $name = trim($_POST['name']);
    $class = trim($_POST['class']);
    $department = trim($_POST['department']);
    $division = trim($_POST['division']);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, zprn = ?, username = ?, class = ?, department = ?, division = ? WHERE id = ? AND role = 'student'");
        $stmt->execute([$name, $zprn, $zprn, $class, $department, $division, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, name, zprn, class, department, division) VALUES (?, ?, 'student', ?, ?, ?, ?, ?)");
        $stmt->execute([$zprn, $zprn, $name, $zprn, $class, $department, $division]);
    }
    header("Location: admin-students.php");
    exit;
}

// Handle Delete Student
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
    $stmt->execute([$delete_id]);
    header("Location: admin-students.php");
    exit;
}

// Fetch Stats
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

$today_date = date('Y-m-d');
$stmt_today = $pdo->prepare("SELECT COUNT(DISTINCT student_id) FROM attendance WHERE date = ? AND status = 'Present'");
$stmt_today->execute([$today_date]);
$present_today = $stmt_today->fetchColumn();

// Overall average attendance
$total_records = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
$present_records = $pdo->query("SELECT COUNT(*) FROM attendance WHERE status = 'Present'")->fetchColumn();
$avg_attendance = $total_records > 0 ? round(($present_records / $total_records) * 100, 1) : 100;

// Fetch all students and compute stats
$students = $pdo->query("SELECT * FROM users WHERE role = 'student' ORDER BY name ASC")->fetchAll();
$departments_list = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
$student_stats = [];
$low_attendance_count = 0;

foreach ($students as $student) {
    $s_id = $student['id'];
    
    $stmt_tot = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ?");
    $stmt_tot->execute([$s_id]);
    $total_s = $stmt_tot->fetchColumn();

    $stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND status = 'Present'");
    $stmt_pres->execute([$s_id]);
    $present_s = $stmt_pres->fetchColumn();

    $percent = $total_s > 0 ? round(($present_s / $total_s) * 100, 1) : 100.0;
    if ($percent < 75.0 && $total_s > 0) {
        $low_attendance_count++;
    }

    $student_stats[$s_id] = [
        'percent' => $percent,
        'present' => $present_s,
        'total' => $total_s
    ];
}

$page_title       = 'Student Registration & Directory';
$page_breadcrumb  = 'AttendEase / Super Admin / Students';
$page_icon        = 'bi-people-fill';

include '../includes/header.php';
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
        <?php include '../includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Title Band -->
            <?php include '../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_students; ?></div>
                                <div class="faculty-stat-lbl">Total Registered Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-person-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $present_today; ?></div>
                                <div class="faculty-stat-lbl">Present Today</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-graph-up"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $avg_attendance; ?>%</div>
                                <div class="faculty-stat-lbl">Average Attendance</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $low_attendance_count; ?></div>
                                <div class="faculty-stat-lbl">Low Attendance (&lt;75%)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Directory Table Card -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-mortarboard-fill" style="color:#60a5fa;"></i> Student Directory &amp; Attendance Records</h3>
                            <p class="faculty-card-subtitle">Manage enrolled student profiles, ZPRN/roll numbers, course divisions, and attendance percentage</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            <!-- Premium Transparent Glassmorphism Search Bar -->
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text border-secondary text-secondary" style="background: rgba(255,255,255,0.06);"><i class="bi bi-search"></i></span>
                                <input type="text" id="directorySearchInput" class="form-control text-white border-secondary" placeholder="Search Roll No / Name..." style="background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addStudentModal" onclick="prepareAddStudent()">
                                <i class="bi bi-plus-lg"></i> Register Student
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table align-middle">
                            <thead>
                                <tr>
                                    <th>Roll / PRN</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Department</th>
                                    <th>Division</th>
                                    <th>Attendance %</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): 
                                    $s_id = $student['id'];
                                    $stats = $student_stats[$s_id];
                                    $pb_color = $stats['percent'] >= 75 ? 'bg-success' : 'bg-danger';
                                ?>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1"><?php echo htmlspecialchars($student['zprn']); ?></span></td>
                                    <td>
                                        <div class="fw-semibold text-white"><?php echo htmlspecialchars($student['name']); ?></div>
                                        <small style="color:#64748b;"><?php echo htmlspecialchars($student['username']); ?>@college.edu</small>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['class']); ?></td>
                                    <td><span class="faculty-badge badge-blue-subtle"><?php echo htmlspecialchars($student['department'] ?? 'AI&ML'); ?></span></td>
                                    <td>Division <?php echo htmlspecialchars($student['division']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px; width:70px;">
                                                <div class="progress-bar <?php echo $pb_color; ?>" style="width:<?php echo $stats['percent']; ?>%;"></div>
                                            </div>
                                            <small class="fw-bold text-white"><?php echo $stats['percent']; ?>%</small>
                                        </div>
                                    </td>
                                    <td><span class="faculty-badge badge-success-subtle"><?php echo htmlspecialchars($student['status']); ?></span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Student" data-bs-toggle="modal" data-bs-target="#addStudentModal" onclick="prepareEditStudent(<?php echo $s_id; ?>, '<?php echo addslashes($student['zprn']); ?>', '<?php echo addslashes($student['name']); ?>', '<?php echo addslashes($student['class']); ?>', '<?php echo addslashes($student['department'] ?? 'AI&ML'); ?>', '<?php echo addslashes($student['division']); ?>')"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <a class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Student" href="admin-students.php?delete_id=<?php echo $s_id; ?>" onclick="return confirm('Delete student record?')"><i class="bi bi-trash3 me-1"></i>Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Modal: Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="modal_title"><i class="bi bi-person-plus me-2 text-primary"></i>Register New Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin-students.php" method="POST">
                <input type="hidden" name="action" value="save_student">
                <input type="hidden" name="id" id="modal_student_id" value="">
                
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Roll Number / PRN</label>
                        <input type="text" name="zprn" id="modal_zprn" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 125UAM1209" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Full Student Name</label>
                        <input type="text" name="name" id="modal_name" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Tanay Shelar" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Class</label>
                        <select name="class" id="modal_class" class="form-select bg-dark text-white border-secondary" required>
                            <option value="First Year">First Year</option>
                            <option value="Second Year">Second Year</option>
                            <option value="Third Year">Third Year</option>
                            <option value="Fourth Year">Fourth Year</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Department</label>
                            <select name="department" id="modal_department" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- Select Dept --</option>
                                <?php foreach ($departments_list as $dept): ?>
                                    <option value="<?php echo htmlspecialchars($dept['code']); ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Division</label>
                            <select name="division" id="modal_division" class="form-select bg-dark text-white border-secondary" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareEditStudent(id, zprn, name, studentClass, department, division) {
    document.getElementById('modal_student_id').value = id;
    document.getElementById('modal_zprn').value = zprn;
    document.getElementById('modal_name').value = name;
    document.getElementById('modal_class').value = studentClass;
    document.getElementById('modal_department').value = department;
    document.getElementById('modal_division').value = division;
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-pencil-square me-2 text-info"></i>Edit Student Profile';
}

function prepareAddStudent() {
    document.getElementById('modal_student_id').value = '';
    document.getElementById('modal_zprn').value = '';
    document.getElementById('modal_name').value = '';
    document.getElementById('modal_class').value = 'First Year';
    document.getElementById('modal_department').value = 'AI&ML';
    document.getElementById('modal_division').value = 'A';
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-person-plus me-2 text-primary"></i>Register New Student';
}

// Client-side search filtration
document.getElementById('directorySearchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.faculty-table tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
