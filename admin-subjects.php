<?php
/**
 * AttendEase - Super Admin Subject Management
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
$page_title       = 'Subject Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Subjects';
$page_icon        = 'bi-journal-bookmark-fill';
$page_btn_label   = 'Add Subject';
$page_btn_modal   = 'addSubjectModal';

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

            <!-- Topbar Page Band -->
            <?php include 'includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">32</div>
                                <div class="faculty-stat-lbl">Active Subjects</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-file-earmark-text"></i></div>
                            <div>
                                <div class="faculty-stat-val">24</div>
                                <div class="faculty-stat-lbl">Theory Subjects</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-laptop"></i></div>
                            <div>
                                <div class="faculty-stat-val">8</div>
                                <div class="faculty-stat-lbl">Practical Labs</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">24/24</div>
                                <div class="faculty-stat-lbl">Faculty Allocated</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subject Table Card -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-journal-bookmark-fill" style="color:#60a5fa;"></i> Curriculum Subject Repository</h3>
                            <p class="faculty-card-subtitle">Manage subject codes, semester allocation, course credits, and assigned faculty</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Search subject..." style="width: 220px;">
                            <button type="button" class="btn btn-sm btn-premium" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
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
                                    <th>Type</th>
                                    <th>Credits</th>
                                    <th>Semester</th>
                                    <th>Assigned Faculty</th>
                                    <th>Department</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold">CS501</span></td>
                                    <td><div class="fw-semibold text-white">Data Structures &amp; Algorithms</div></td>
                                    <td><span class="faculty-badge badge-blue-subtle">Theory</span></td>
                                    <td><span class="fw-bold text-white">4</span></td>
                                    <td>Sem V</td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td>Computer Engineering</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-light py-1 px-2 me-1"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold">CS502</span></td>
                                    <td><div class="fw-semibold text-white">Database Management Systems</div></td>
                                    <td><span class="faculty-badge badge-blue-subtle">Theory</span></td>
                                    <td><span class="fw-bold text-white">4</span></td>
                                    <td>Sem V</td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td>Computer Engineering</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-light py-1 px-2 me-1"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-emerald-subtle text-emerald font-mono fw-bold">CS503</span></td>
                                    <td><div class="fw-semibold text-white">Web Technology Lab</div></td>
                                    <td><span class="faculty-badge badge-success-subtle">Practical Lab</span></td>
                                    <td><span class="fw-bold text-white">2</span></td>
                                    <td>Sem V</td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td>Computer Engineering</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-light py-1 px-2 me-1"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2"><i class="bi bi-trash"></i></button>
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

<!-- Modal: Add Subject -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Add New Subject</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Subject added successfully!'); bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Subject Code</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. CS601" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Subject Title</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Artificial Intelligence & Machine Learning" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Type</label>
                            <select class="form-select bg-dark text-white border-secondary">
                                <option>Theory</option>
                                <option>Practical Lab</option>
                                <option>Elective</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Credits</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" value="4" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Assigned Faculty</label>
                        <select class="form-select bg-dark text-white border-secondary">
                            <option>Prof. Rajesh Sharma</option>
                            <option>Dr. Sarah Jenkins</option>
                            <option>Dr. Amit Patel</option>
                            <option>Prof. Vikram Malhotra</option>
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

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
