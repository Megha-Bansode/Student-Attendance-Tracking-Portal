<?php
/**
 * AttendEase - Faculty Subject Allocation Page
 */
$page_title = 'Subject Allocation';

/* ── Mock Allocated Subjects (replace with DB query) ── */
$allocated_subjects = [
    ['code'=>'CS501','name'=>'Data Structures & Algorithms','department'=>'Computer Science & Engineering','semester'=>'5','division'=>'A','students_count'=>60,'weekly_hours'=>4,'type'=>'Theory','academic_year'=>'2025-2026'],
    ['code'=>'CS502','name'=>'Database Management Systems','department'=>'Computer Science & Engineering','semester'=>'5','division'=>'B','students_count'=>60,'weekly_hours'=>4,'type'=>'Theory','academic_year'=>'2025-2026'],
    ['code'=>'CS503','name'=>'Web Technology Lab','department'=>'Computer Science & Engineering','semester'=>'7','division'=>'A','students_count'=>60,'weekly_hours'=>6,'type'=>'Practical','academic_year'=>'2025-2026'],
    ['code'=>'CS504','name'=>'Object-Oriented Programming','department'=>'Computer Science & Engineering','semester'=>'3','division'=>'C','students_count'=>60,'weekly_hours'=>4,'type'=>'Theory','academic_year'=>'2025-2026'],
];

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
    <?php include '../../includes/faculty-sidebar.php'; ?>

    <div class="faculty-main-content">

        <!-- Page Band -->
        <div class="faculty-page-band">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="faculty-page-band-title"><i class="bi bi-diagram-3-fill"></i> Subject Allocation</h1>
                    <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Subject Allocation</p>
                </div>
            </div>
            <div class="faculty-page-band-right">
                <div class="topbar-date-pill"><i class="bi bi-calendar3"></i><span><?php echo date('D, M d, Y'); ?></span></div>
                <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php" class="btn btn-sm btn-premium d-none d-sm-inline-flex" style="padding:.4rem 1rem;font-size:.82rem;"><i class="bi bi-plus-lg"></i> Mark Attendance</a>
            </div>
        </div>

        <main class="faculty-content-body">

            <!-- Session Info Card -->
            <div class="faculty-card mb-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h2 class="h5 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Assigned Courses & Subject Load</h2>
                        <p class="mb-0" style="color:#64748b;font-size:.875rem;">View official allocations assigned by the department head for the current academic session.</p>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                        <span class="faculty-badge badge-blue-subtle" style="font-size:.85rem;padding:.45rem .9rem;">
                            <i class="bi bi-mortarboard-fill me-1"></i> Academic Session: 2025 – 2026
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="faculty-card mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="allocationSearchInput" class="faculty-form-label"><i class="bi bi-search me-1"></i> Search Subject</label>
                        <input type="text" id="allocationSearchInput" class="faculty-input" placeholder="Type subject name or code (e.g. CS501)...">
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label for="allocationSemFilter" class="faculty-form-label"><i class="bi bi-funnel me-1"></i> Semester</label>
                        <select id="allocationSemFilter" class="faculty-select">
                            <option value="">All Semesters</option>
                            <option value="3">Semester 3</option>
                            <option value="5">Semester 5</option>
                            <option value="7">Semester 7</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label for="allocationDivFilter" class="faculty-form-label"><i class="bi bi-building me-1"></i> Division</label>
                        <select id="allocationDivFilter" class="faculty-select">
                            <option value="">All Divisions</option>
                            <option value="A">Division A</option>
                            <option value="B">Division B</option>
                            <option value="C">Division C</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Subject Cards Grid -->
            <div class="row g-4">
                <?php foreach ($allocated_subjects as $subject): ?>
                    <div class="col-xl-6 allocation-card-item"
                         data-subject-name="<?php echo htmlspecialchars($subject['name']); ?>"
                         data-subject-code="<?php echo htmlspecialchars($subject['code']); ?>"
                         data-semester="<?php echo htmlspecialchars($subject['semester']); ?>"
                         data-division="<?php echo htmlspecialchars($subject['division']); ?>">

                        <div class="faculty-card h-100 mb-0" style="display:flex;flex-direction:column;justify-content:space-between;">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary fw-bold" style="font-size:.85rem;letter-spacing:.5px;"><?php echo htmlspecialchars($subject['code']); ?></span>
                                        <span class="faculty-badge badge-<?php echo $subject['type'] === 'Practical' ? 'warning-subtle' : 'info-subtle'; ?>"><?php echo htmlspecialchars($subject['type']); ?></span>
                                    </div>
                                    <span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                </div>
                                <h3 class="h5 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;"><?php echo htmlspecialchars($subject['name']); ?></h3>
                                <p class="small mb-3" style="color:#64748b;"><i class="bi bi-building me-1"></i><?php echo htmlspecialchars($subject['department']); ?></p>
                                <hr style="border-color:rgba(255,255,255,.06);margin:0.75rem 0;">
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.06);">
                                            <small style="color:#64748b;display:block;">Semester & Div</small>
                                            <span class="fw-semibold" style="color:#e2e8f0;">Sem <?php echo htmlspecialchars($subject['semester']); ?> · Div <?php echo htmlspecialchars($subject['division']); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.06);">
                                            <small style="color:#64748b;display:block;">Students</small>
                                            <span class="fw-semibold" style="color:#e2e8f0;"><i class="bi bi-people me-1" style="color:#38bdf8;"></i><?php echo htmlspecialchars($subject['students_count']); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.06);">
                                            <small style="color:#64748b;display:block;">Weekly Hours</small>
                                            <span class="fw-semibold" style="color:#e2e8f0;"><i class="bi bi-clock me-1" style="color:#fbbf24;"></i><?php echo htmlspecialchars($subject['weekly_hours']); ?> hrs/wk</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded" style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.06);">
                                            <small style="color:#64748b;display:block;">Academic Year</small>
                                            <span class="fw-semibold" style="color:#e2e8f0;"><?php echo htmlspecialchars($subject['academic_year']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo $base_path; ?>modules/attendance/faculty-mark-attendance.php?subject=<?php echo urlencode($subject['code']); ?>&div=<?php echo urlencode($subject['division']); ?>"
                               class="btn btn-premium w-100 text-center justify-content-center">
                                <i class="bi bi-check2-square me-1"></i> Mark Attendance
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty State -->
            <div class="faculty-card text-center py-5 mt-4" id="allocationEmptyState" style="display:none;">
                <i class="bi bi-search display-4" style="color:#334155;"></i>
                <h4 class="fw-bold mt-3 mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">No Matching Allocations</h4>
                <p class="mb-0" style="color:#64748b;">Try adjusting your search criteria or clear filters.</p>
            </div>

        </main>

    </div>
</div>
</div>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
