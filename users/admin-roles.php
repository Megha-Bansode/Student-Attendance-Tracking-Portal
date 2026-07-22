<?php
/**
 * AttendEase - Super Admin User Role Management
 */
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
                            <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#assignRoleModal"><i class="bi bi-plus-circle me-1"></i> Add / Assign Role</button>
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
                            <input type="text" id="searchInput" class="faculty-input" placeholder="Search by name, email, or department...">
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
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-role="admin">
                                    <td>
                                        <div class="fw-semibold text-white">Super Administrator</div>
                                        <small style="color:#64748b;">SysAdmin Account</small>
                                    </td>
                                    <td><span style="color:#cbd5e1;">admin@attendease.edu</span></td>
                                    <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Super Admin</span></td>
                                    <td><span class="small" style="color:#cbd5e1;">Full system read, write, security, HOD settings</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Cannot edit master admin role')"><i class="bi bi-pencil"></i></button>
                                    </td>
                                </tr>
                                <tr data-role="faculty">
                                    <td>
                                        <div class="fw-semibold text-white">Prof. Rajesh Sharma</div>
                                        <small style="color:#64748b;">Computer Engineering</small>
                                    </td>
                                    <td><span style="color:#cbd5e1;">rajesh.sharma@attendease.edu</span></td>
                                    <td><span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">Faculty</span></td>
                                    <td><span class="small" style="color:#cbd5e1;">Mark/edit student attendance, view class reports</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing permissions for Prof. Rajesh Sharma')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2 ms-1" onclick="if(confirm('Revoke role from this user?')) { alert('Role revoked!'); }"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr data-role="student">
                                    <td>
                                        <div class="fw-semibold text-white">Aarav Sharma</div>
                                        <small style="color:#64748b;">CSE Student (TE)</small>
                                    </td>
                                    <td><span style="color:#cbd5e1;">aarav.sharma@attendease.edu</span></td>
                                    <td><span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 rounded-pill">Student</span></td>
                                    <td><span class="small" style="color:#cbd5e1;">View personal attendance history, receive shortages</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2" onclick="alert('Editing permissions for Aarav Sharma')"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2 ms-1" onclick="if(confirm('Revoke role from this user?')) { alert('Role revoked!'); }"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
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
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit" id="assignRoleModalLabel">Assign User Role</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Role assigned successfully!'); bootstrap.Modal.getInstance(document.getElementById('assignRoleModal')).hide();">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Select User</label>
                        <select class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Choose User Account --</option>
                            <option value="1">Dr. Sarah Jenkins (Faculty)</option>
                            <option value="2">Ananya Deshmukh (Student)</option>
                            <option value="3">Dr. Amit Patel (Faculty)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold text-slate-300">Role Assignment</label>
                        <select class="form-select bg-dark text-white border-secondary" required>
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
