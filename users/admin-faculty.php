<?php
/**
 * AttendEase - Super Admin Faculty Directory
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

// Handle Add/Edit Faculty
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_faculty') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $password = !empty($_POST['password']) ? trim($_POST['password']) : 'Faculty@123';
    $subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 0;
    $department = trim($_POST['department'] ?? '');
    $class_name = trim($_POST['class'] ?? '');
    $division = trim($_POST['division'] ?? '');

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, department = ?, class = ?, division = ? WHERE id = ? AND role = 'faculty'");
        $stmt->execute([$name, $username, $department, $class_name, $division, $id]);
        
        if (!empty($_POST['password'])) {
            $stmt_pw = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_pw->execute([$password, $id]);
        }
        
        if ($subject_id > 0) {
            // Delete old allocations
            $pdo->prepare("DELETE FROM faculty_subjects WHERE faculty_id = ?")->execute([$id]);
            $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)")->execute([$id, $subject_id]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, name, department, class, division) VALUES (?, ?, 'faculty', ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $name, $department, $class_name, $division]);
        $new_faculty_id = $pdo->lastInsertId();

        if ($subject_id > 0 && $new_faculty_id > 0) {
            $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)")->execute([$new_faculty_id, $subject_id]);
        }
    }
    header("Location: admin-faculty.php");
    exit;
}

// Handle Delete Faculty
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'faculty'")->execute([$delete_id]);
    $pdo->prepare("DELETE FROM faculty_subjects WHERE faculty_id = ?")->execute([$delete_id]);
    header("Location: admin-faculty.php");
    exit;
}

// Fetch Stats
$total_faculty = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'faculty'")->fetchColumn();

// Fetch Subjects for Assigning
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY name ASC")->fetchAll();

// Fetch all faculties with their assigned subjects
$faculties = $pdo->query("
    SELECT u.*, s.name as subject_name, s.id as subject_id, d.name AS dept_name 
    FROM users u 
    LEFT JOIN faculty_subjects fs ON u.id = fs.faculty_id 
    LEFT JOIN subjects s ON fs.subject_id = s.id 
    LEFT JOIN departments d ON u.department = d.code
    WHERE u.role = 'faculty' 
    ORDER BY u.name ASC
")->fetchAll();

$departments_list = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

$page_title       = 'Faculty Directory & Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Faculty';
$page_icon        = 'bi-person-badge';

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
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-100">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_faculty; ?></div>
                                <div class="faculty-stat-lbl">Total Faculty Members</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-200">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-mortarboard"></i></div>
                            <div>
                                <div class="faculty-stat-val">Active</div>
                                <div class="faculty-stat-lbl">Role-based Access</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-300">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-award-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">ERP Connected</div>
                                <div class="faculty-stat-lbl">SQLite Database</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-400">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">100%</div>
                                <div class="faculty-stat-lbl">Active Status</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Faculty Roster Table Card -->
                <div class="faculty-card mb-0 animate-fade-up delay-500">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-person-badge" style="color:#60a5fa;"></i> Academic Faculty Directory</h3>
                            <p class="faculty-card-subtitle">Manage faculty profiles, credentials, subject allocations, and contact info</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            <!-- Premium Transparent Glassmorphism Search Bar -->
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text border-secondary text-secondary" style="background: rgba(255,255,255,0.06);"><i class="bi bi-search"></i></span>
                                <input type="text" id="facultySearchInput" class="form-control text-white border-secondary" placeholder="Search Faculty..." style="background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addFacultyModal" onclick="prepareAddFaculty()">
                                <i class="bi bi-plus-lg"></i> Register Faculty
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table align-middle">
                            <thead>
                                <tr>
                                    <th>Faculty Username</th>
                                    <th>Full Name</th>
                                    <th>Role Status</th>
                                    <th>Allocated Subject</th>
                                    <th>Class & Div</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $fac_delay = 100;
                                foreach ($faculties as $faculty): 
                                    $f_id = $faculty['id'];
                                    $initials = strtoupper(substr($faculty['name'], 0, 2));
                                ?>
                                <tr class="animate-fade-up delay-<?php echo $fac_delay; ?>">
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1"><?php echo htmlspecialchars($faculty['username']); ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#2563eb,#6366f1);font-size:.8rem;"><?php echo $initials; ?></div>
                                            <div class="fw-semibold text-white"><?php echo htmlspecialchars($faculty['name']); ?></div>
                                        </div>
                                    </td>
                                    <td><span class="faculty-badge badge-blue-subtle">Faculty Member</span></td>
                                    <td><span class="fw-bold text-white"><?php echo htmlspecialchars($faculty['subject_name'] ?? 'None Assigned'); ?></span></td>
                                    <td>
                                        <?php if (!empty($faculty['class'])): ?>
                                            <span class="badge bg-warning-subtle text-warning"><?php echo htmlspecialchars($faculty['class'] . ' (' . $faculty['division'] . ')'); ?></span>
                                        <?php else: ?>
                                            <span class="text-secondary small">Not Assigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Faculty" data-bs-toggle="modal" data-bs-target="#addFacultyModal" onclick="prepareEditFaculty(<?php echo $f_id; ?>, '<?php echo addslashes($faculty['username']); ?>', '<?php echo addslashes($faculty['name']); ?>', <?php echo intval($faculty['subject_id'] ?? 0); ?>, '<?php echo addslashes($faculty['department'] ?? ''); ?>', '<?php echo addslashes($faculty['class'] ?? ''); ?>', '<?php echo addslashes($faculty['division'] ?? ''); ?>')"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <a class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Faculty" href="admin-faculty.php?delete_id=<?php echo $f_id; ?>" onclick="return confirm('Delete faculty profile?')"><i class="bi bi-trash3 me-1"></i>Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    $fac_delay += 50;
                                    if ($fac_delay > 500) $fac_delay = 500;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Modal: Add Faculty -->
<div class="modal fade" id="addFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="modal_title"><i class="bi bi-person-plus me-2 text-primary"></i>Register Faculty Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin-faculty.php" method="POST">
                <input type="hidden" name="action" value="save_faculty">
                <input type="hidden" name="id" id="modal_faculty_id" value="">
                
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Faculty Username / ID</label>
                        <input type="text" name="username" id="modal_username" class="form-control bg-dark text-white border-secondary" placeholder="Enter Faculty Username / ID" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Full Name</label>
                        <input type="text" name="name" id="modal_name" class="form-control bg-dark text-white border-secondary" placeholder="Enter Full Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Password</label>
                        <input type="password" name="password" id="modal_password" class="form-control bg-dark text-white border-secondary" placeholder="Enter Password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Assign Subject</label>
                        <select name="subject_id" id="modal_subject_id" class="form-select bg-dark text-white border-secondary">
                            <option value="0">-- None / Select Subject --</option>
                            <?php foreach ($subjects as $sub): ?>
                                <option value="<?php echo $sub['id']; ?>"><?php echo htmlspecialchars($sub['name']); ?> (<?php echo htmlspecialchars($sub['class']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Assign Department</label>
                        <select name="department" id="modal_department" class="form-select bg-dark text-white border-secondary">
                            <option value="">-- No Department --</option>
                            <?php foreach ($departments_list as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept['code']); ?>" <?php echo (isset($_GET['dept']) && $_GET['dept'] === $dept['code']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label text-slate-300 font-semibold">Class Coordinator</label>
                            <select name="class" id="modal_class" class="form-select bg-dark text-white border-secondary">
                                <option value="">-- None --</option>
                                <option value="First Year">First Year</option>
                                <option value="Second Year">Second Year</option>
                                <option value="Third Year">Third Year</option>
                                <option value="Fourth Year">Fourth Year</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-slate-300 font-semibold">Division</label>
                            <select name="division" id="modal_division" class="form-select bg-dark text-white border-secondary">
                                <option value="">-- None --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareEditFaculty(id, username, name, subject_id, department, facultyClass, division) {
    document.getElementById('modal_faculty_id').value = id;
    document.getElementById('modal_username').value = username;
    document.getElementById('modal_name').value = name;
    document.getElementById('modal_password').value = ''; 
    document.getElementById('modal_subject_id').value = subject_id;
    document.getElementById('modal_department').value = department;
    document.getElementById('modal_class').value = facultyClass;
    document.getElementById('modal_division').value = division;
    
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-pencil-square me-2 text-info"></i>Edit Faculty Profile';
}

function prepareAddFaculty() {
    document.getElementById('modal_faculty_id').value = '';
    document.getElementById('modal_username').value = '';
    document.getElementById('modal_name').value = '';
    document.getElementById('modal_password').placeholder = "Enter Password";
    document.getElementById('modal_subject_id').value = '0';
    document.getElementById('modal_class').value = '';
    document.getElementById('modal_division').value = '';
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-person-plus me-2 text-primary"></i>Register Faculty Member';
}

// Client-side search filtration
document.getElementById('facultySearchInput').addEventListener('input', function() {
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
