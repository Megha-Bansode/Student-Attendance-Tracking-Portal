<?php
/**
 * AttendEase - Super Admin Student Registration & Directory
 * Professional Bootstrap 5 layout with zero button overlaps & clean table controls
 */
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
                                <div class="faculty-stat-val">1,450</div>
                                <div class="faculty-stat-lbl">Total Registered Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-person-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">1,340</div>
                                <div class="faculty-stat-lbl">Present Today</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-graph-up"></i></div>
                            <div>
                                <div class="faculty-stat-val">92.4%</div>
                                <div class="faculty-stat-lbl">Average Attendance</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">12</div>
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
                        <div class="d-flex align-items-center gap-2.5 ms-auto">
                            <div class="input-group input-group-sm" style="width: 240px;">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Search Roll No / Name...">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addStudentModal">
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
                                    <th>Course &amp; Class</th>
                                    <th>Department</th>
                                    <th>Avg Marks</th>
                                    <th>Attendance %</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">2026CS101</span></td>
                                    <td>
                                        <div class="fw-semibold text-white">Aarav Sharma</div>
                                        <small style="color:#64748b;">aarav.s@student.attendease.edu</small>
                                    </td>
                                    <td>TE CSE – Div A</td>
                                    <td>Computer Engineering</td>
                                    <td><span class="fw-bold text-white">92 / 100</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px; width:70px;">
                                                <div class="progress-bar bg-success" style="width:96.5%;"></div>
                                            </div>
                                            <small class="fw-bold text-white">96.5%</small>
                                        </div>
                                    </td>
                                    <td><span class="faculty-badge badge-success-subtle">Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Student" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Student" onclick="confirm('Delete student record?') && alert('Student record removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">2026CS102</span></td>
                                    <td>
                                        <div class="fw-semibold text-white">Ananya Verma</div>
                                        <small style="color:#64748b;">ananya.v@student.attendease.edu</small>
                                    </td>
                                    <td>TE CSE – Div A</td>
                                    <td>Computer Engineering</td>
                                    <td><span class="fw-bold text-white">88 / 100</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px; width:70px;">
                                                <div class="progress-bar bg-success" style="width:94.0%;"></div>
                                            </div>
                                            <small class="fw-bold text-white">94.0%</small>
                                        </div>
                                    </td>
                                    <td><span class="faculty-badge badge-success-subtle">Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Student" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Student" onclick="confirm('Delete student record?') && alert('Student record removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
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

<!-- Modal: Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white"><i class="bi bi-person-plus me-2 text-primary"></i>Register New Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Student registered successfully!'); bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Roll Number / PRN</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 2026CS109" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Full Student Name</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Emma Watson" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Department</label>
                            <select class="form-select bg-dark text-white border-secondary">
                                <option>Computer Engineering</option>
                                <option>Information Technology</option>
                                <option>Electronics & Telecommunication</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Class / Div</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="TE CSE - Div A" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Register Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
