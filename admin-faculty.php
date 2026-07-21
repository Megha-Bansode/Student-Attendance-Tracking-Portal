<?php
/**
 * AttendEase - Super Admin Faculty Directory
 * Professional Bootstrap 5 layout with zero button overlaps & clean table controls
 */
$page_title       = 'Faculty Directory & Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Faculty';
$page_icon        = 'bi-person-badge';

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
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="faculty-stat-val">24</div>
                                <div class="faculty-stat-lbl">Total Faculty Members</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-mortarboard"></i></div>
                            <div>
                                <div class="faculty-stat-val">8</div>
                                <div class="faculty-stat-lbl">Professors &amp; HODs</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-award-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">12</div>
                                <div class="faculty-stat-lbl">Associate Professors</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
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
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-person-badge" style="color:#60a5fa;"></i> Academic Faculty Directory</h3>
                            <p class="faculty-card-subtitle">Manage faculty profiles, credentials, subject allocations, and contact info</p>
                        </div>
                        <div class="d-flex align-items-center gap-2.5 ms-auto">
                            <div class="input-group input-group-sm" style="width: 240px;">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Search faculty member...">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                                <i class="bi bi-plus-lg"></i> Register Faculty
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table align-middle">
                            <thead>
                                <tr>
                                    <th>Faculty ID</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Allocated Subjects</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width:140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">FAC-1001</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#2563eb,#6366f1);font-size:.8rem;">RS</div>
                                            <div class="fw-semibold text-white">Prof. Rajesh Sharma</div>
                                        </div>
                                    </td>
                                    <td>Computer Engineering</td>
                                    <td><span class="faculty-badge badge-blue-subtle">Associate Professor</span></td>
                                    <td><span style="color:#94a3b8;">rajesh.sharma@attendease.edu</span></td>
                                    <td><span class="fw-bold text-white">4 Subjects</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Faculty" data-bs-toggle="modal" data-bs-target="#addFacultyModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Faculty" onclick="confirm('Delete faculty profile?') && alert('Faculty member deleted.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">FAC-1002</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#0ea5e9,#38bdf8);font-size:.8rem;">SJ</div>
                                            <div class="fw-semibold text-white">Dr. Sarah Jenkins</div>
                                        </div>
                                    </td>
                                    <td>Computer Engineering</td>
                                    <td><span class="faculty-badge badge-purple-subtle">Professor &amp; HOD</span></td>
                                    <td><span style="color:#94a3b8;">sarah.jenkins@attendease.edu</span></td>
                                    <td><span class="fw-bold text-white">3 Subjects</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Faculty" data-bs-toggle="modal" data-bs-target="#addFacultyModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Faculty" onclick="confirm('Delete faculty profile?') && alert('Faculty member deleted.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2.5 py-1">FAC-1003</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:34px;height:34px;background:linear-gradient(135deg,#059669,#10b981);font-size:.8rem;">AP</div>
                                            <div class="fw-semibold text-white">Dr. Amit Patel</div>
                                        </div>
                                    </td>
                                    <td>Electronics &amp; Telecom</td>
                                    <td><span class="faculty-badge badge-purple-subtle">Professor &amp; HOD</span></td>
                                    <td><span style="color:#94a3b8;">amit.patel@attendease.edu</span></td>
                                    <td><span class="fw-bold text-white">3 Subjects</span></td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Active</span></td>
                                    <td class="text-end">
                                        <div class="action-btn-group justify-content-end">
                                            <button class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-2" title="Edit Faculty" data-bs-toggle="modal" data-bs-target="#addFacultyModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-2" title="Delete Faculty" onclick="confirm('Delete faculty profile?') && alert('Faculty member deleted.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
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

<!-- Modal: Add Faculty -->
<div class="modal fade" id="addFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white"><i class="bi bi-person-plus me-2 text-primary"></i>Register Faculty Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Faculty registered successfully!'); bootstrap.Modal.getInstance(document.getElementById('addFacultyModal')).hide();">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Faculty ID</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. FAC-1005" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Full Name</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Dr. Ramesh Gupta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Email Address</label>
                        <input type="email" class="form-control bg-dark text-white border-secondary" placeholder="ramesh.gupta@attendease.edu" required>
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
                            <label class="form-label text-slate-300 font-semibold">Designation</label>
                            <select class="form-select bg-dark text-white border-secondary">
                                <option>Assistant Professor</option>
                                <option>Associate Professor</option>
                                <option>Professor & HOD</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Register Faculty</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
