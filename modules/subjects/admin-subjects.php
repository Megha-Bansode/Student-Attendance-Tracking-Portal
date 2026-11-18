<?php
/**
 * AttendEase - Super Admin Subject Management
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

// Add/Edit Subject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_subject') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = trim($_POST['name']);
    $class = trim($_POST['class']);
    $faculty_id = isset($_POST['faculty_id']) ? intval($_POST['faculty_id']) : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE subjects SET name = ?, class = ? WHERE id = ?");
        $stmt->execute([$name, $class, $id]);
        
        if ($faculty_id > 0) {
            $pdo->prepare("DELETE FROM faculty_subjects WHERE subject_id = ?")->execute([$id]);
            $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)")->execute([$faculty_id, $id]);
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO subjects (name, class) VALUES (?, ?)");
        $stmt->execute([$name, $class]);
        $new_subject_id = $pdo->lastInsertId();

        if ($faculty_id > 0 && $new_subject_id > 0) {
            $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)")->execute([$faculty_id, $new_subject_id]);
        }
    }
    header("Location: admin-subjects.php");
    exit;
}

// Delete Subject
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $pdo->prepare("DELETE FROM subjects WHERE id = ?")->execute([$delete_id]);
    $pdo->prepare("DELETE FROM faculty_subjects WHERE subject_id = ?")->execute([$delete_id]);
    header("Location: admin-subjects.php");
    exit;
}

// Fetch all subjects and faculty assigned
$subjects = $pdo->query("
    SELECT s.*, u.name AS faculty_name, u.id AS faculty_id
    FROM subjects s
    LEFT JOIN faculty_subjects fs ON s.id = fs.subject_id
    LEFT JOIN users u ON fs.faculty_id = u.id AND u.role = 'faculty'
    ORDER BY s.id DESC
")->fetchAll();

$faculties = $pdo->query("SELECT * FROM users WHERE role = 'faculty' ORDER BY name ASC")->fetchAll();

$page_title       = 'Subject Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Subjects';
$page_icon        = 'bi-journal-bookmark-fill';

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
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-100">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo count($subjects); ?></div>
                                <div class="faculty-stat-lbl">Active Subjects</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-200">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <div class="faculty-stat-val">Theory</div>
                                <div class="faculty-stat-lbl">Curriculum Type</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-300">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-laptop"></i></div>
                            <div>
                                <div class="faculty-stat-val">Connected</div>
                                <div class="faculty-stat-lbl">Database State</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-400">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">ERP Sync</div>
                                <div class="faculty-stat-lbl">Roster Allocation</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Table Card -->
                <div class="faculty-card mb-0 animate-fade-up delay-500">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-journal-bookmark-fill" style="color:#60a5fa;"></i> Curriculum Subject Repository</h3>
                            <p class="faculty-card-subtitle">Manage subject codes, class allocation, and assigned faculty</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            <!-- Premium Transparent Glassmorphism Search Bar -->
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text border-secondary text-secondary" style="background: rgba(255,255,255,0.06);"><i class="bi bi-search"></i></span>
                                <input type="text" id="subjectSearchInput" class="form-control text-white border-secondary" placeholder="Search Subjects..." style="background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium" data-bs-toggle="modal" data-bs-target="#addSubjectModal" onclick="prepareAddSubject()">
                                <i class="bi bi-plus-lg"></i> Add Subject
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Target Class</th>
                                    <th>Assigned Faculty</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sub_delay = 100;
                                foreach ($subjects as $subj): 
                                ?>
                                <tr class="animate-fade-up delay-<?php echo $sub_delay; ?>">
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold">SUBJ-<?php echo $subj['id']; ?></span></td>
                                    <td><div class="fw-semibold text-white"><?php echo htmlspecialchars($subj['name']); ?></div></td>
                                    <td><?php echo htmlspecialchars($subj['class']); ?></td>
                                    <td><span class="fw-bold text-white"><?php echo htmlspecialchars($subj['faculty_name'] ?? 'None Assigned'); ?></span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-light py-1 px-2 me-1" onclick="prepareEditSubject(<?php echo $subj['id']; ?>, '<?php echo addslashes($subj['name']); ?>', '<?php echo addslashes($subj['class']); ?>', <?php echo intval($subj['faculty_id'] ?? 0); ?>)" data-bs-toggle="modal" data-bs-target="#addSubjectModal"><i class="bi bi-pencil"></i></button>
                                        <a href="admin-subjects.php?delete_id=<?php echo $subj['id']; ?>" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="return confirm('Delete this subject?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    $sub_delay += 50;
                                    if($sub_delay > 500) $sub_delay = 500;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Modal: Add Subject -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="modal_title"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Add New Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin-subjects.php" method="POST">
                <input type="hidden" name="action" value="save_subject">
                <input type="hidden" name="id" id="modal_subject_id" value="">
                
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Subject Title</label>
                        <input type="text" name="name" id="modal_name" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Probability and statistics" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Target Class</label>
                        <select name="class" id="modal_class" class="form-select bg-dark text-white border-secondary" required>
                            <option value="First Year">First Year</option>
                            <option value="Second Year">Second Year</option>
                            <option value="Third Year">Third Year</option>
                            <option value="Fourth Year">Fourth Year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Assigned Faculty</label>
                        <select name="faculty_id" id="modal_faculty_id" class="form-select bg-dark text-white border-secondary">
                            <option value="0">-- Select Faculty --</option>
                            <?php foreach ($faculties as $fac): ?>
                                <option value="<?php echo $fac['id']; ?>"><?php echo htmlspecialchars($fac['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareEditSubject(id, name, classVal, facultyId) {
    document.getElementById('modal_subject_id').value = id;
    document.getElementById('modal_name').value = name;
    document.getElementById('modal_class').value = classVal;
    document.getElementById('modal_faculty_id').value = facultyId;
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-pencil me-2 text-info"></i>Edit Subject Details';
}

function prepareAddSubject() {
    document.getElementById('modal_subject_id').value = '';
    document.getElementById('modal_name').value = '';
    document.getElementById('modal_class').value = 'First Year';
    document.getElementById('modal_faculty_id').value = '0';
    document.getElementById('modal_title').innerHTML = '<i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Add New Subject';
}

// Client-side search filtration
document.getElementById('subjectSearchInput').addEventListener('input', function() {
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
<?php include '../../includes/footer.php'; ?>
