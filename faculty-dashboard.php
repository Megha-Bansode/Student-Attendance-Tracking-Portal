<?php
/**
 * AttendEase - Faculty Dashboard
 * Uses original header.php + footer.php for consistent site-wide nav.
 */
$page_title  = 'Faculty Dashboard';

/* ── Mock data (replace with DB query later) ── */
$assigned_subjects_count = 4;
$total_students_count    = 240;
$weekly_lectures_count   = 18;
$todays_completed_count  = 3;
$todays_total_count      = 4;
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
    <!-- ── Faculty Portal Layout ── -->
    <div class="faculty-portal-wrapper">

        <!-- Sidebar -->
        <?php include 'includes/faculty-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">

                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-grid-1x2-fill"></i> Faculty Dashboard
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Overview</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                    <a href="faculty-mark-attendance.php" class="btn btn-sm btn-premium d-none d-sm-inline-flex" style="padding:.4rem 1rem;font-size:.82rem;">
                        <i class="bi bi-plus-lg"></i> Mark Attendance
                    </a>
                </div>
            </div>

            <!-- Content -->
            <main class="faculty-content-body">

                <!-- Welcome Banner -->
                <div class="faculty-card mb-4" style="background:linear-gradient(135deg,rgba(37,99,235,.18) 0%,rgba(99,102,241,.12) 50%,rgba(15,23,42,.85) 100%);border-color:rgba(96,165,250,.3);">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i> Active Session</span>
                                <span class="faculty-badge badge-blue-subtle"><i class="bi bi-building"></i> CSE Department</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Welcome back, Prof. Rajesh Sharma 👋</h2>
                            <p class="mb-0" style="color:#94a3b8;font-size:.9rem;">
                                You have <strong style="color:#93c5fd;"><?php echo ($todays_total_count - $todays_completed_count); ?> class remaining</strong> to mark attendance for today.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="faculty-mark-attendance.php" class="btn btn-premium"><i class="bi bi-check2-square"></i> Mark Attendance</a>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $assigned_subjects_count; ?></div><div class="faculty-stat-lbl">Assigned Subjects</div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-people-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $total_students_count; ?></div><div class="faculty-stat-lbl">Total Students</div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-calendar-check-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $weekly_lectures_count; ?></div><div class="faculty-stat-lbl">Weekly Classes</div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-clock-history"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $todays_completed_count; ?>/<?php echo $todays_total_count; ?></div><div class="faculty-stat-lbl">Today's Attendance</div></div>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Quick Actions -->
                <div class="row g-4 mb-4">
                    <div class="col-lg-8">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-calendar-event" style="color:#60a5fa;"></i> Today's Teaching Schedule</h3>
                                    <p class="faculty-card-subtitle">Lectures for <?php echo date('F j, Y'); ?></p>
                                </div>
                                <a href="faculty-subject-allocation.php" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:.8rem;">View All</a>
                            </div>
                            <div class="faculty-table-responsive">
                                <table class="faculty-table">
                                    <thead><tr><th>Time / Slot</th><th>Subject</th><th>Class</th><th>Status</th><th class="text-end">Action</th></tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">09:00 – 10:00 AM</div><small style="color:#64748b;">Slot 1</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Data Structures &amp; Algorithms</span><br><small style="color:#64748b;">CS501 · Lecture</small></td>
                                            <td><span class="faculty-badge badge-info-subtle">TE CSE – Div A</span></td>
                                            <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Done</span></td>
                                            <td class="text-end"><a href="faculty-edit-attendance.php?subject=CS501&div=A" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size:.78rem;"><i class="bi bi-pencil me-1"></i>Edit</a></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">10:15 – 11:15 AM</div><small style="color:#64748b;">Slot 2</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Database Management Systems</span><br><small style="color:#64748b;">CS502 · Lecture</small></td>
                                            <td><span class="faculty-badge badge-info-subtle">TE CSE – Div B</span></td>
                                            <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Done</span></td>
                                            <td class="text-end"><a href="faculty-edit-attendance.php?subject=CS502&div=B" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size:.78rem;"><i class="bi bi-pencil me-1"></i>Edit</a></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">01:30 – 02:30 PM</div><small style="color:#64748b;">Slot 3</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Web Technology Lab</span><br><small style="color:#64748b;">CS503 · Practical</small></td>
                                            <td><span class="faculty-badge badge-info-subtle">BE CSE – Div A</span></td>
                                            <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Done</span></td>
                                            <td class="text-end"><a href="faculty-edit-attendance.php?subject=CS503&div=A" class="btn btn-sm btn-outline-light py-1 px-2" style="font-size:.78rem;"><i class="bi bi-pencil me-1"></i>Edit</a></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">03:00 – 04:00 PM</div><small style="color:#64748b;">Slot 4</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Object-Oriented Programming</span><br><small style="color:#64748b;">CS504 · Lecture</small></td>
                                            <td><span class="faculty-badge badge-info-subtle">SE CSE – Div C</span></td>
                                            <td><span class="faculty-badge badge-warning-subtle"><i class="bi bi-clock-history me-1"></i>Pending</span></td>
                                            <td class="text-end"><a href="faculty-mark-attendance.php?subject=CS504&div=C&slot=4" class="btn btn-sm btn-premium py-1 px-3" style="font-size:.78rem;"><i class="bi bi-check-lg me-1"></i>Mark Now</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-lightning-charge-fill" style="color:#fbbf24;"></i> Quick Actions</h3>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <a href="faculty-mark-attendance.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(37,99,235,.12);border:1px solid rgba(96,165,250,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#6366f1);"><i class="bi bi-check2-circle text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Mark Attendance</h6><small style="color:#64748b;">Record class attendance</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>
                                <a href="faculty-edit-attendance.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(14,165,233,.12);border:1px solid rgba(56,189,248,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="bi bi-pencil-square text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Edit Attendance</h6><small style="color:#64748b;">Modify within 48 hours</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>
                                <a href="faculty-subject-allocation.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(16,185,129,.12);border:1px solid rgba(52,211,153,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#059669,#10b981);"><i class="bi bi-diagram-3-fill text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Subject Allocations</h6><small style="color:#64748b;">View assigned courses</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>
                            </div>
                            <hr style="border-color:rgba(255,255,255,.07);margin:1.25rem 0;">
                            <p class="small mb-0" style="color:#64748b;line-height:1.5;"><i class="bi bi-info-circle me-1" style="color:#60a5fa;"></i>Editing is open for <strong style="color:#93c5fd;">48 hours</strong> after class completion. Locked entries need admin authorization.</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-activity" style="color:#38bdf8;"></i> Recent Attendance Submissions</h3>
                        <span class="faculty-badge badge-secondary-subtle">Last 24 Hours</span>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead><tr><th>Time</th><th>Subject</th><th>Class</th><th>Present / Total</th><th>Rate</th><th>Status</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i>Today, 01:45 PM</td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Web Technology Lab</span> <small style="color:#64748b;">(CS503)</small></td>
                                    <td>BE CSE – Div A</td>
                                    <td><span style="color:#34d399;font-weight:700;">58</span> / 60</td>
                                    <td><div class="d-flex align-items-center gap-2"><div class="progress flex-grow-1" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;"><div class="progress-bar bg-success" style="width:96.6%;border-radius:3px;"></div></div><small class="fw-bold" style="color:#f1f5f9;">96.6%</small></div></td>
                                    <td><span class="faculty-badge badge-success-subtle">Saved</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i>Today, 10:25 AM</td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Database Management Systems</span> <small style="color:#64748b;">(CS502)</small></td>
                                    <td>TE CSE – Div B</td>
                                    <td><span style="color:#34d399;font-weight:700;">55</span> / 60</td>
                                    <td><div class="d-flex align-items-center gap-2"><div class="progress flex-grow-1" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;"><div class="progress-bar bg-success" style="width:91.6%;border-radius:3px;"></div></div><small class="fw-bold" style="color:#f1f5f9;">91.6%</small></div></td>
                                    <td><span class="faculty-badge badge-success-subtle">Saved</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i>Today, 09:10 AM</td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Data Structures &amp; Algorithms</span> <small style="color:#64748b;">(CS501)</small></td>
                                    <td>TE CSE – Div A</td>
                                    <td><span style="color:#34d399;font-weight:700;">57</span> / 60</td>
                                    <td><div class="d-flex align-items-center gap-2"><div class="progress flex-grow-1" style="height:5px;background:rgba(255,255,255,.08);border-radius:3px;"><div class="progress-bar bg-success" style="width:95%;border-radius:3px;"></div></div><small class="fw-bold" style="color:#f1f5f9;">95.0%</small></div></td>
                                    <td><span class="faculty-badge badge-success-subtle">Saved</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
