<?php
/**
 * AttendEase - Super Admin Course Management
 * Professional layout with clean table controls & interactive modal
 */
$page_title       = 'Course Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Courses';
$page_icon        = 'bi-book';

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
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-book"></i></div>
                            <div>
                                <div class="faculty-stat-val">8</div>
                                <div class="faculty-stat-lbl">Degree Programs</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-diagram-3-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">5</div>
                                <div class="faculty-stat-lbl">Departments</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-journal-text"></i></div>
                            <div>
                                <div class="faculty-stat-val">32</div>
                                <div class="faculty-stat-lbl">Total Credits/Sem</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">100%</div>
                                <div class="faculty-stat-lbl">UGC Accredited</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Table Card -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-book" style="color:#60a5fa;"></i> Degree Courses &amp; Programs</h3>
                            <p class="faculty-card-subtitle">Manage degree programs, duration, credit structures, and department links</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="position-relative">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2.5 text-slate-400" style="font-size:0.8rem;"></i>
                                <input type="text" class="form-control form-control-sm bg-dark text-light border-secondary ps-4" placeholder="Search course..." style="width: 220px; font-size:0.82rem;">
                            </div>
                            <button type="button" class="btn btn-sm btn-premium d-inline-flex align-items-center gap-1 px-3 py-1.5" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                                <i class="bi bi-plus-lg"></i> Add Course
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Department</th>
                                    <th>Duration</th>
                                    <th>Semesters</th>
                                    <th>Total Credits</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold px-2 py-1">BTECH-CSE</span></td>
                                    <td><div class="fw-semibold text-white">B.Tech Computer Science &amp; Eng.</div></td>
                                    <td>Computer Engineering</td>
                                    <td>4 Years</td>
                                    <td>8 Semesters</td>
                                    <td><span class="fw-bold text-white">160</span></td>
                                    <td><span class="faculty-badge badge-success-subtle">Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 me-1 rounded-2" title="Edit Course" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2" title="Delete Course" onclick="confirm('Are you sure you want to delete this course?') && alert('Course removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info-subtle text-info font-mono fw-bold px-2 py-1">BTECH-IT</span></td>
                                    <td><div class="fw-semibold text-white">B.Tech Information Technology</div></td>
                                    <td>Information Technology</td>
                                    <td>4 Years</td>
                                    <td>8 Semesters</td>
                                    <td><span class="fw-bold text-white">160</span></td>
                                    <td><span class="faculty-badge badge-success-subtle">Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info py-1 px-2.5 me-1 rounded-2" title="Edit Course" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="bi bi-pencil-square me-1"></i>Edit</button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2.5 rounded-2" title="Delete Course" onclick="confirm('Are you sure you want to delete this course?') && alert('Course removed.')"><i class="bi bi-trash3 me-1"></i>Delete</button>
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

<!-- Modal: Add Course -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white"><i class="bi bi-book me-2 text-primary"></i>Add New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('Course saved successfully!'); bootstrap.Modal.getInstance(document.getElementById('addCourseModal')).hide();">
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Course Code</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. BTECH-AI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Course Name</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="e.g. B.Tech Artificial Intelligence" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Department</label>
                        <select class="form-select bg-dark text-white border-secondary">
                            <option>Computer Engineering</option>
                            <option>Information Technology</option>
                            <option>Electronics & Telecommunication</option>
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Duration (Years)</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" value="4" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Total Credits</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" value="160" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
