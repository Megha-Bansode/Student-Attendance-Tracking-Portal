<?php
/**
 * AttendEase - Super Admin Department Management
 * Professional Bootstrap 5 layout with zero button overlaps & clean table controls
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

// Handle Add/Edit Department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_department') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $code = strtoupper(trim($_POST['code']));
    $name = trim($_POST['name']);
    $hod = trim($_POST['hod']);
    $established = intval($_POST['established']);
    $intake = intval($_POST['intake']);
    $status = trim($_POST['status'] ?? 'Active');

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE departments SET code = ?, name = ?, hod = ?, established = ?, intake = ?, status = ? WHERE id = ?");
        $stmt->execute([$code, $name, $hod, $established, $intake, $status, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO departments (code, name, hod, established, intake, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$code, $name, $hod, $established, $intake, $status]);
    }
    header("Location: admin-departments.php");
    exit;
}

// Handle Delete Department
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: admin-departments.php");
    exit;
}

// Fetch all departments
$departments = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

// Count stats
$total_departments = count($departments);

// Count total faculty members across all departments
$total_faculty = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'faculty'")->fetchColumn();

// Count total students across all departments
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

// Overall average attendance
$total_records = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
$present_records = $pdo->query("SELECT COUNT(*) FROM attendance WHERE status = 'Present'")->fetchColumn();
$avg_attendance = $total_records > 0 ? round(($present_records / $total_records) * 100, 1) : 100;

$page_title       = 'Department Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Departments';
$page_icon        = 'bi-building';

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

            <!-- Topbar Page Title Band -->
            <?php include '../../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-building"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_departments; ?></div>
                                <div class="faculty-stat-lbl">Active Departments</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_faculty; ?></div>
                                <div class="faculty-stat-lbl">Total Faculty</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo number_format($total_students); ?></div>
                                <div class="faculty-stat-lbl">Total Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $avg_attendance; ?>%</div>
                                <div class="faculty-stat-lbl">Avg Attendance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Table Card -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-building" style="color:#60a5fa;"></i> Institution Departments</h3>
                            <p class="faculty-card-subtitle">Manage degree department configurations, HOD assignments, and student strength</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            <div class="input-group input-group-sm" style="width: 240px;">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-search"></i></span>
                                <input type="text" id="deptSearchInput" class="form-control bg-dark text-white border-secondary" placeholder="Search department...">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addDepartmentModal" onclick="prepareAddDept()">
                                <i class="bi bi-plus-lg"></i> Add Department
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table align-middle" id="departmentsTable">
                            <thead>
                                <tr>
                                    <th>Dept Code</th>
                                    <th>Department Name</th>
                                    <th>Head of Department (HOD)</th>
                                    <th>Faculty Count</th>
                                    <th>Students</th>
                                    <th>Established</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($departments)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-secondary py-4">No departments found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($departments as $dept): 
                                        $dept_code = $dept['code'];
                                        
                                        // Fetch dynamic counts
                                        $stmt_f = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'faculty' AND (department = ? OR department = ?)");
                                        $stmt_f->execute([$dept_code, $dept['name']]);
                                        $f_count = $stmt_f->fetchColumn();

                                        $stmt_s = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'student' AND (department = ? OR department = ?)");
                                        $stmt_s->execute([$dept_code, $dept['name']]);
                                        $s_count = $stmt_s->fetchColumn();
                                        
                                        // Choose a badge color class depending on code
                                        $badge_class = 'bg-primary-subtle text-primary';
                                        if ($dept_code === 'IT') {
                                            $badge_class = 'bg-info-subtle text-info';
                                        } elseif ($dept_code === 'ENTC') {
                                            $badge_class = 'bg-warning-subtle text-warning';
                                        } elseif ($dept_code === 'CS' || $dept_code === 'CSE') {
                                            $badge_class = 'bg-success-subtle text-success';
                                        }
                                        
                                        $status_class = ($dept['status'] === 'Active') ? 'badge-success-subtle' : 'badge-danger-subtle';
                                    ?>
                                    <tr>
                                        <td><span class="badge <?php echo $badge_class; ?> font-mono fw-bold px-2.5 py-1"><?php echo htmlspecialchars($dept['code']); ?></span></td>
                                        <td><div class="fw-semibold text-white"><?php echo htmlspecialchars($dept['name']); ?></div></td>
                                        <td><?php echo htmlspecialchars($dept['hod']); ?></td>
                                        <td><span class="faculty-badge badge-info-subtle"><?php echo $f_count; ?> Members</span></td>
                                        <td><span class="fw-bold text-white"><?php echo $s_count; ?></span></td>
                                        <td><?php echo htmlspecialchars($dept['established']); ?></td>
                                        <td><span class="faculty-badge <?php echo $status_class; ?>"><i class="bi bi-check-circle-fill me-1"></i><?php echo htmlspecialchars($dept['status']); ?></span></td>
                                        <td class="text-end">
                                            <div class="action-btn-group justify-content-end">
                                                <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Department" data-bs-toggle="modal" data-bs-target="#addDepartmentModal" onclick="prepareEditDept(<?php echo $dept['id']; ?>, '<?php echo addslashes($dept['code']); ?>', '<?php echo addslashes($dept['name']); ?>', '<?php echo addslashes($dept['hod']); ?>', <?php echo $dept['established']; ?>, <?php echo $dept['intake']; ?>, '<?php echo addslashes($dept['status']); ?>')"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                                <a class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Department" href="admin-departments.php?delete_id=<?php echo $dept['id']; ?>" onclick="return confirm('Are you sure you want to delete this department?')"><i class="bi bi-trash3 me-1"></i>Delete</a>
                                            </div>
                                        </td>
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

<!-- Modal: Add Department -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="addDepartmentModalLabel"><i class="bi bi-building me-2 text-primary"></i>Add New Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDeptForm" action="admin-departments.php" method="POST">
                <input type="hidden" name="action" value="save_department">
                <input type="hidden" name="id" id="modal_dept_id" value="">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Department Code</label>
                        <input type="text" name="code" id="modal_dept_code" class="form-control bg-dark text-white border-secondary" placeholder="e.g. CSE, IT, AI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Department Full Name</label>
                        <input type="text" name="name" id="modal_dept_name" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Computer Science & Engineering" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Head of Department (HOD)</label>
                        <input type="text" name="hod" id="modal_dept_hod" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Dr. Sarah Jenkins" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Established Year</label>
                            <input type="number" name="established" id="modal_dept_established" class="form-control bg-dark text-white border-secondary" value="2024" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Initial Intake</label>
                            <input type="number" name="intake" id="modal_dept_intake" class="form-control bg-dark text-white border-secondary" value="120" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Status</label>
                        <select name="status" id="modal_dept_status" class="form-select bg-dark text-white border-secondary" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareEditDept(id, code, name, hod, established, intake, status) {
    document.getElementById('modal_dept_id').value = id;
    document.getElementById('modal_dept_code').value = code;
    document.getElementById('modal_dept_name').value = name;
    document.getElementById('modal_dept_hod').value = hod;
    document.getElementById('modal_dept_established').value = established;
    document.getElementById('modal_dept_intake').value = intake;
    document.getElementById('modal_dept_status').value = status;
    document.getElementById('addDepartmentModalLabel').innerHTML = '<i class="bi bi-pencil-square me-2 text-info"></i>Edit Department';
}

function prepareAddDept() {
    document.getElementById('modal_dept_id').value = '';
    document.getElementById('modal_dept_code').value = '';
    document.getElementById('modal_dept_name').value = '';
    document.getElementById('modal_dept_hod').value = '';
    document.getElementById('modal_dept_established').value = '2024';
    document.getElementById('modal_dept_intake').value = '120';
    document.getElementById('modal_dept_status').value = 'Active';
    document.getElementById('addDepartmentModalLabel').innerHTML = '<i class="bi bi-building me-2 text-primary"></i>Add New Department';
}

// Client-side search filtration
document.getElementById('deptSearchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#departmentsTable tbody tr');
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
<?php include '../../includes/footer.php'; ?>
