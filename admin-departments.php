<?php
/**
 * AttendEase - Super Admin Department Management
 * Professional Bootstrap 5 layout with zero button overlaps & clean table controls
 */
$page_title       = 'Department Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Departments';
$page_icon        = 'bi-building';

include 'includes/header.php';
?>
<link rel="stylesheet" href="assets/css/faculty.css">

<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <div class="faculty-portal-wrapper">

        <!-- Super Admin Sidebar -->
        <?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Title Band -->
            <?php include 'includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-building"></i></div>
                            <div>
                                <div class="faculty-stat-val">5</div>
                                <div class="faculty-stat-lbl">Active Departments</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="faculty-stat-val">24</div>
                                <div class="faculty-stat-lbl">Total Faculty</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">1,450</div>
                                <div class="faculty-stat-lbl">Total Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">92.4%</div>
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
                        <div class="d-flex align-items-center gap-2.5 ms-auto">
                            <div class="input-group input-group-sm" style="width: 240px;">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-search"></i></span>
                                <input type="text" id="deptSearchInput" class="form-control bg-dark text-white border-secondary" placeholder="Search department...">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
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
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">CSE</span></td>
                                    <td><div class="fw-semibold text-white">Computer Engineering</div></td>
                                    <td>Dr. Sarah Jenkins</td>
                                    <td><span class="faculty-badge badge-info-subtle">8 Members</span></td>
                                    <td><span class="fw-bold text-white">464</span></td>
                                    <td>2010</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Department" data-bs-toggle="modal" data-bs-target="#addDepartmentModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Department" onclick="confirm('Are you sure you want to delete this department?') && alert('Department removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info-subtle text-info font-mono fw-bold px-2.5 py-1">IT</span></td>
                                    <td><div class="fw-semibold text-white">Information Technology</div></td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="faculty-badge badge-info-subtle">6 Members</span></td>
                                    <td><span class="fw-bold text-white">348</span></td>
                                    <td>2012</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Department" data-bs-toggle="modal" data-bs-target="#addDepartmentModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Department" onclick="confirm('Are you sure you want to delete this department?') && alert('Department removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning-subtle text-warning font-mono fw-bold px-2.5 py-1">ENTC</span></td>
                                    <td><div class="fw-semibold text-white">Electronics &amp; Telecommunication</div></td>
                                    <td>Dr. Amit Patel</td>
                                    <td><span class="faculty-badge badge-info-subtle">4 Members</span></td>
                                    <td><span class="fw-bold text-white">261</span></td>
                                    <td>2008</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Department" data-bs-toggle="modal" data-bs-target="#addDepartmentModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Department" onclick="confirm('Are you sure you want to delete this department?') && alert('Department removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
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

<!-- Modal: Add Department -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="addDepartmentModalLabel"><i class="bi bi-building me-2 text-primary"></i>Add New Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDeptForm" onsubmit="event.preventDefault(); alert('Department saved successfully!'); bootstrap.Modal.getInstance(document.getElementById('addDepartmentModal')).hide();">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Department Code</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. CSE, IT, AI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Department Full Name</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Computer Science & Engineering" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Head of Department (HOD)</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Dr. Sarah Jenkins" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Established Year</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" value="2024" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Initial Intake</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" value="120" required>
                        </div>
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

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
