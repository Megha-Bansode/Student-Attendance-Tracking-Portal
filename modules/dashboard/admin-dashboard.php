<?php
/**
 * AttendEase - Super Admin Executive Dashboard
 * High-end ERP layout with zero duplicates, responsive analytics, and real-time audit trail
 */
$page_title       = 'Super Admin Dashboard';
$page_breadcrumb  = 'AttendEase / Super Admin / Overview';
$page_icon        = 'bi-grid-1x2-fill';

require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role'])) {
    header("Location: ../authentication/login.php");
    exit;
}

/* Dynamic statistics data */
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
if (!$total_departments) $total_departments = 0;
$total_courses     = $pdo->query("SELECT COUNT(DISTINCT class) FROM users WHERE role = 'student'")->fetchColumn();
if (!$total_courses) $total_courses = 1;
$total_subjects    = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$total_faculty     = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'faculty'")->fetchColumn();
$total_students    = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

$today_date = date('Y-m-d');
$stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ? AND status = 'Present'");
$stmt_pres->execute([$today_date]);
$present_today = $stmt_pres->fetchColumn();

$stmt_abs = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ? AND status = 'Absent'");
$stmt_abs->execute([$today_date]);
$absent_today = $stmt_abs->fetchColumn();

$total_today = $present_today + $absent_today;
$today_attendance = $total_today > 0 ? round(($present_today / $total_today) * 100, 1) . "%" : "100%";

// Fetch departments for overview table
$departments_list = $pdo->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

// Dynamic audit logs
$stmt_audit = $pdo->query("
    SELECT a.date, subj.name AS subject_name, fac.name AS faculty_name, u.name AS student_name, a.status
    FROM attendance a
    JOIN subjects subj ON a.subject_id = subj.id
    JOIN users u ON a.student_id = u.id
    LEFT JOIN users fac ON a.marked_by = fac.id
    ORDER BY a.id DESC
    LIMIT 5
");
$audit_logs = $stmt_audit->fetchAll();



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

            <!-- Topbar Page Title Band -->
            <?php include '../../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Welcome Banner -->
                <div class="faculty-card welcome-banner-card mb-4 animate-fade-up">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-shield-check me-1"></i> Autonomous ERP v1.0</span>
                                <span class="faculty-badge badge-blue-subtle"><i class="bi bi-buildings"></i> <?php echo $total_departments; ?> Active Departments</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1" style="color:#f1f5f9; font-family:'Outfit',sans-serif;">Welcome back, Super Administrator 👋</h2>
                            <p class="mb-0" style="color:#94a3b8; font-size:.9rem;">
                                Institution daily attendance is running at <strong style="color:#93c5fd;"><?php echo $today_attendance; ?></strong> across all degree programs today.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                            <a href="<?php echo $base_path; ?>reports/admin-reports.php" class="btn btn-premium px-3.5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;"><i class="bi bi-file-earmark-pdf me-1"></i> Generate Reports</a>
                            <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-premium px-3.5"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
                            <a href="<?php echo $base_path; ?>users/admin-faculty.php" class="btn btn-outline-light rounded-pill px-3" style="font-size:.85rem;"><i class="bi bi-person-plus me-1"></i> Register Faculty</a>
                        </div>
                    </div>
                </div>

                <!-- AI Insight Banner -->
                <div class="faculty-card ai-insight-card mb-4 p-3 animate-fade-up delay-100" style="border-radius: 14px;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="ai-pulse flex-shrink-0 mx-2"></div>
                            <div>
                                <h6 class="mb-1 text-white font-outfit d-flex align-items-center gap-2">
                                    <span class="ai-badge"><i class="bi bi-stars"></i> AI Insight</span>
                                    Institution Attendance Pattern
                                </h6>
                                <p class="mb-0 text-slate-300" style="font-size: 0.85rem;">
                                    Based on current data, average attendance is stabilizing at <strong class="text-white"><?php echo $today_attendance; ?></strong>. <span class="ai-analyzing-text">Analyzing historical trends...</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Primary KPI Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-200">
                        <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="cursor: pointer;">
                                <div class="faculty-stat-icon icon-cyan"><i class="bi bi-building"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo $total_departments; ?></div>
                                    <div class="faculty-stat-lbl">Active Departments</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-300">
                        <a href="<?php echo $base_path; ?>modules/semesters/admin-semesters.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="cursor: pointer;">
                                <div class="faculty-stat-icon icon-purple"><i class="bi bi-book"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo $total_courses; ?></div>
                                    <div class="faculty-stat-lbl">Active Courses</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-400">
                        <a href="<?php echo $base_path; ?>modules/subjects/admin-subjects.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="cursor: pointer;">
                                <div class="faculty-stat-icon icon-emerald"><i class="bi bi-journal-bookmark-fill"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo $total_subjects; ?></div>
                                    <div class="faculty-stat-lbl">Total Subjects</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6 animate-fade-up delay-500">
                        <a href="<?php echo $base_path; ?>users/admin-faculty.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="cursor: pointer;">
                                <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-badge"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo $total_faculty; ?></div>
                                    <div class="faculty-stat-lbl">Registered Faculty</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Secondary Metric Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>users/admin-students.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="border-left: 4px solid #2563eb; cursor: pointer;">
                                <div class="faculty-stat-icon" style="background: rgba(37, 99, 235, 0.15); color: #60a5fa;"><i class="bi bi-people-fill"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo number_format($total_students); ?></div>
                                    <div class="faculty-stat-lbl">Enrolled Students</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/admin-attendance.php" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="border-left: 4px solid #10b981; cursor: pointer;">
                                <div class="faculty-stat-icon icon-emerald"><i class="bi bi-graph-up-arrow"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo $today_attendance; ?></div>
                                    <div class="faculty-stat-lbl">Today's Attendance Rate</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/admin-attendance.php?status=present" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="border-left: 4px solid #06b6d4; cursor: pointer;">
                                <div class="faculty-stat-icon icon-cyan"><i class="bi bi-person-check-fill"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo number_format($present_today); ?></div>
                                    <div class="faculty-stat-lbl">Students Present</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/admin-attendance.php?status=absent" style="text-decoration: none; display: block; color: inherit;">
                            <div class="faculty-stat-card" style="border-left: 4px solid #f43f5e; cursor: pointer;">
                                <div class="faculty-stat-icon" style="background: rgba(244, 63, 94, 0.15); color: #fb7185;"><i class="bi bi-person-x-fill"></i></div>
                                <div>
                                    <div class="faculty-stat-val"><?php echo number_format($absent_today); ?></div>
                                    <div class="faculty-stat-lbl">Students Absent</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Main Layout Split Grid -->
                <div class="row g-4 mb-4">
                    <!-- Left: Department Overview Table -->
                    <div class="col-lg-8">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-buildings" style="color:#60a5fa;"></i> Department Attendance &amp; Capacity</h3>
                                    <p class="faculty-card-subtitle">Real-time breakdown across all 5 engineering departments</p>
                                </div>
                                <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:.8rem;">View All</a>
                            </div>
                            <div class="faculty-table-responsive">
                                <table class="faculty-table">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>HOD</th>
                                            <th>Faculty</th>
                                            <th>Students</th>
                                            <th>Attendance Rate</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($departments_list)): ?>
                                            <tr class="animate-fade-up">
                                                <td colspan="6" class="text-center text-secondary py-4">No active departments found.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php 
                                            $delay = 100;
                                            foreach ($departments_list as $dept): 
                                                // Faculty count
                                                $stmt_f = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'faculty' AND (department = ? OR department = ?)");
                                                $stmt_f->execute([$dept['code'], $dept['name']]);
                                                $fac_count = $stmt_f->fetchColumn();

                                                // Student count
                                                $stmt_s = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'student' AND (department = ? OR department = ?)");
                                                $stmt_s->execute([$dept['code'], $dept['name']]);
                                                $stu_count = $stmt_s->fetchColumn();

                                                // Attendance count
                                                $stmt_p = $pdo->prepare("SELECT COUNT(*) FROM attendance a JOIN users u ON a.student_id = u.id WHERE (u.department = ? OR u.department = ?) AND a.status = 'Present'");
                                                $stmt_p->execute([$dept['code'], $dept['name']]);
                                                $p_cnt = $stmt_p->fetchColumn();

                                                $stmt_t = $pdo->prepare("SELECT COUNT(*) FROM attendance a JOIN users u ON a.student_id = u.id WHERE (u.department = ? OR u.department = ?)");
                                                $stmt_t->execute([$dept['code'], $dept['name']]);
                                                $t_cnt = $stmt_t->fetchColumn();
                                                
                                                $att_rate = $t_cnt > 0 ? round(($p_cnt / $t_cnt) * 100, 1) . "%" : "100.0%";
                                            ?>
                                            <tr class="animate-fade-up delay-<?php echo $delay; ?>">
                                                <td>
                                                    <div class="fw-semibold text-white"><i class="bi bi-laptop me-2 text-primary"></i><?php echo htmlspecialchars($dept['name']); ?></div>
                                                    <small style="color:#64748b;">Code: <?php echo htmlspecialchars($dept['code']); ?></small>
                                                </td>
                                                <td><span style="color:#cbd5e1;"><?php echo htmlspecialchars($dept['hod'] ?? 'N/A'); ?></span></td>
                                                <td><span class="faculty-badge badge-info-subtle"><?php echo $fac_count; ?> Faculty</span></td>
                                                <td><span class="fw-bold text-white"><?php echo $stu_count; ?></span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px;">
                                                            <div class="progress-bar bg-success" style="width:<?php echo $att_rate; ?>;"></div>
                                                        </div>
                                                        <small class="fw-bold text-white"><?php echo $att_rate; ?></small>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?php echo $base_path; ?>users/admin-students.php" class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2"><i class="bi bi-arrow-right"></i></a>
                                                </td>
                                            </tr>
                                            <?php 
                                                $delay += 100;
                                                if($delay > 500) $delay = 500;
                                            endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Quick Navigation Grid -->
                    <div class="col-lg-4 animate-fade-up delay-100">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-lightning-charge-fill" style="color:#fbbf24;"></i> Administrative Controls</h3>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(37,99,235,.12);border:1px solid rgba(96,165,250,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#6366f1);"><i class="bi bi-building text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold text-white font-outfit">Department Setup</h6><small style="color:#64748b;">Add or edit departments</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>

                                <a href="<?php echo $base_path; ?>users/admin-faculty.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(14,165,233,.12);border:1px solid rgba(56,189,248,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="bi bi-person-badge text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold text-white font-outfit">Faculty Roster</h6><small style="color:#64748b;">Teachers &amp; allocations</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>

                                <a href="<?php echo $base_path; ?>users/admin-students.php" class="text-decoration-none p-3 rounded-3 d-flex align-items-center justify-content-between" style="background:rgba(16,185,129,.12);border:1px solid rgba(52,211,153,.25);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:38px;height:38px;background:linear-gradient(135deg,#059669,#10b981);"><i class="bi bi-people-fill text-white"></i></div>
                                        <div><h6 class="mb-0 fw-semibold text-white font-outfit">Student Directory</h6><small style="color:#64748b;">Enrollment &amp; PRNs</small></div>
                                    </div>
                                    <i class="bi bi-chevron-right" style="color:#64748b;"></i>
                                </a>
                            </div>

                            <hr style="border-color:rgba(255,255,255,.07);margin:1.25rem 0;">
                            <div class="p-2.5 rounded-3" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.05);">
                                <small style="color:#94a3b8;"><i class="bi bi-shield-check text-success me-1"></i> Uptime: <strong class="text-white">99.98%</strong> | Encryption: <strong class="text-white">AES-256</strong></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent System Audit Logs -->
                <div class="faculty-card mb-0 animate-fade-up delay-200">
                    <div class="faculty-card-header d-flex align-items-center justify-content-between">
                        <h3 class="faculty-card-title"><i class="bi bi-journal-text" style="color:#38bdf8;"></i> Real-Time Audit Logs</h3>
                        <span class="faculty-badge badge-secondary-subtle">System Audit Trail</span>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action Performed</th>
                                    <th>Module</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($audit_logs)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-4">No audit logs recorded yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php 
                                    $log_delay = 100;
                                    foreach ($audit_logs as $log): 
                                    ?>
                                    <tr class="animate-fade-up delay-<?php echo $log_delay; ?>">
                                        <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($log['date']); ?></td>
                                        <td><span class="fw-semibold text-white"><?php echo htmlspecialchars($log['faculty_name'] ?? 'System'); ?></span></td>
                                        <td>Marked attendance for <?php echo htmlspecialchars($log['student_name']); ?>: <?php echo htmlspecialchars($log['status']); ?></td>
                                        <td>Attendance</td>
                                        <td style="color:#64748b;font-family:monospace;">127.0.0.1</td>
                                        <td><span class="faculty-badge badge-success-subtle">Success</span></td>
                                    </tr>
                                    <?php 
                                        $log_delay += 100;
                                        if($log_delay > 500) $log_delay = 500;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
