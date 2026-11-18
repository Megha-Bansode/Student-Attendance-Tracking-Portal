<?php
/**
 * AttendEase - Super Admin User Role Management
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

// Handle role updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_role') {
    $user_id = intval($_POST['user_id']);
    $role = trim($_POST['role']);
    
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$role, $user_id]);
    header("Location: admin-roles.php");
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY name ASC")->fetchAll();

$page_title      = 'User Role Management';
$page_breadcrumb = 'AttendEase / Super Admin / User Roles';
$page_icon       = 'bi-person-lock';

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

                <!-- Welcome/Stats Banner -->
                <div class="faculty-card welcome-banner-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="h4 fw-bold text-white font-outfit mb-1">User Role Management</h2>
                            <p class="mb-0 text-slate-300 small">Manage user access configurations, edit system roles, and define feature permission levels.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#assignRoleModal" onclick="prepareAddRole()"><i class="bi bi-plus-circle me-1"></i> Add / Assign Role</button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header mb-3 pb-2">
                        <h3 class="faculty-card-title"><i class="bi bi-funnel" style="color:#818cf8;"></i> Filters</h3>
                    </div>
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-6">
                            <label class="faculty-form-label">Search Users</label>
                            <input type="text" id="searchInput" class="faculty-input text-white border-secondary" placeholder="Search by name, email, or department..." style="background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                        </div>
                        <div class="col-md-6">
                            <label class="faculty-form-label">Filter Role</label>
                            <select id="roleSelect" class="faculty-select">
                                <option value="">All Roles</option>
                                <option value="admin">Super Admin</option>
                                <option value="faculty">Faculty</option>
                                <option value="student">Student</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- User Roles Table -->
                <div class="faculty-card">
                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="rolesTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email / ID</th>
                                    <th>Assigned Role</th>
                                    <th>Permissions Scope</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): 
                                    $badge_class = ($u['role'] === 'admin') ? 'bg-danger-subtle text-danger border-danger-subtle' : (($u['role'] === 'faculty') ? 'bg-info-subtle text-info border-info-subtle' : 'bg-secondary-subtle text-secondary border-secondary');
                                    $permissions = ($u['role'] === 'admin') ? 'Full system control, edit students & faculty' : (($u['role'] === 'faculty') ? 'Mark/edit student attendance, view class reports' : 'View personal attendance history');
                                ?>
                                <tr data-role="<?php echo htmlspecialchars($u['role']); ?>">
                                    <td>
                                        <div class="fw-semibold text-white"><?php echo htmlspecialchars($u['name']); ?></div>
                                        <small style="color:#64748b;"><?php echo htmlspecialchars($u['class'] ?? 'ERP Role'); ?></small>
                                    </td>
                                    <td><span style="color:#cbd5e1;"><?php echo htmlspecialchars($u['username']); ?>@college.edu</span></td>
                                    <td><span class="badge <?php echo $badge_class; ?> px-3 py-1 rounded-pill"><?php echo htmlspecialchars(ucfirst($u['role'])); ?></span></td>
                                    <td><span class="small" style="color:#cbd5e1;"><?php echo $permissions; ?></span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> <?php echo htmlspecialchars($u['status']); ?></span></td>
                                    <td class="text-end">
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="prepareEditRole(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['role']); ?>')" data-bs-toggle="modal" data-bs-target="#assignRoleModal"><i class="bi bi-pencil"></i></button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Master admin credentials cannot be reassigned here.')"><i class="bi bi-pencil"></i></button>
                                        <?php endif; ?>
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

<!-- Assign Role Modal -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary" style="border-radius: 16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit" id="modal_title">Assign User Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin-roles.php" method="POST">
                <input type="hidden" name="action" value="save_role">
                <input type="hidden" name="user_id" id="modal_user_id" value="">
                
                <div class="modal-body">
                    <div class="mb-3" id="userSelectWrapper">
                        <label class="form-label font-semibold text-slate-300">Select User</label>
                        <select name="user_id_select" id="modal_user_select" class="form-select bg-dark text-white border-secondary">
                            <option value="">-- Choose User Account --</option>
                            <?php foreach ($users as $u): ?>
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name']) . " (" . htmlspecialchars($u['role']) . ")"; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Role Assignment</label>
                        <select name="role" id="modal_role" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Choose System Role --</option>
                            <option value="admin">Super Admin</option>
                            <option value="faculty">Faculty Portal Access</option>
                            <option value="student">Student Portal Access</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Role Scope &amp; Permissions</label>
                        <div class="p-3 rounded bg-dark border border-secondary" style="font-size:0.825rem; color:#94a3b8;">
                            Selecting a role applies default platform permissions automatically. You can edit permissions individually after creation.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-premium">Assign Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<script>
function prepareEditRole(userId, role) {
    document.getElementById('modal_user_id').value = userId;
    document.getElementById('modal_role').value = role;
    document.getElementById('userSelectWrapper').style.display = 'none';
    document.getElementById('modal_user_select').removeAttribute('required');
    document.getElementById('modal_title').innerHTML = 'Edit User Role';
}

function prepareAddRole() {
    document.getElementById('modal_user_id').value = '';
    document.getElementById('modal_role').value = '';
    document.getElementById('userSelectWrapper').style.display = '';
    document.getElementById('modal_user_select').setAttribute('required', 'required');
    document.getElementById('modal_title').innerHTML = 'Assign User Role';
    
    // Bind change listener to drop down
    document.getElementById('modal_user_select').addEventListener('change', function() {
        document.getElementById('modal_user_id').value = this.value;
    });
}

// Filter Functionality
const searchInput = document.getElementById('searchInput');
const roleSelect = document.getElementById('roleSelect');
const rows = document.querySelectorAll('#rolesTable tbody tr');

function applyFilters() {
    const query = searchInput.value.toLowerCase().trim();
    const role = roleSelect.value;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowRole = row.getAttribute('data-role');

        const matchesSearch = !query || text.includes(query);
        const matchesRole = !role || rowRole === role;

        if (matchesSearch && matchesRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('input', applyFilters);
roleSelect.addEventListener('change', applyFilters);
</script>
<?php include '../includes/footer.php'; ?>
