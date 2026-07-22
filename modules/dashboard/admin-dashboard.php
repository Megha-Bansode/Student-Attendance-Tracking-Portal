<?php
/**
 * AttendEase - Super Admin Executive Dashboard
 * High-end ERP layout with zero duplicates, responsive analytics, and real-time audit trail
 */
$page_title       = 'Super Admin Dashboard';
$page_breadcrumb  = 'AttendEase / Super Admin / Overview';
$page_icon        = 'bi-grid-1x2-fill';

/* Mock statistics data */
$total_departments = 5;
$total_courses     = 8;
$total_subjects    = 32;
$total_faculty     = 24;
$total_students    = 1450;
$today_attendance  = "92.4%";
$present_today     = 1340;
$absent_today      = 110;

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
                <div class="faculty-card welcome-banner-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-shield-check me-1"></i> Autonomous ERP v1.0</span>
                                <span class="faculty-badge badge-blue-subtle"><i class="bi bi-buildings"></i> 5 Active Departments</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1" style="color:#f1f5f9; font-family:'Outfit',sans-serif;">Welcome back, Super Administrator 👋</h2>
                            <p class="mb-0" style="color:#94a3b8; font-size:.9rem;">
                                Institution daily attendance is running at <strong style="color:#93c5fd;"><?php echo $today_attendance; ?></strong> across all degree programs today.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                            <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-premium px-3.5"><i class="bi bi-plus-circle me-1"></i> Add Department</a>
                            <a href="<?php echo $base_path; ?>users/admin-faculty.php" class="btn btn-outline-light rounded-pill px-3" style="font-size:.85rem;"><i class="bi bi-person-plus me-1"></i> Register Faculty</a>
                        </div>
                    </div>
                </div>

                <!-- Primary KPI Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-building"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_departments; ?></div>
                                <div class="faculty-stat-lbl">Active Departments</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-book"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_courses; ?></div>
                                <div class="faculty-stat-lbl">Active Courses</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_subjects; ?></div>
                                <div class="faculty-stat-lbl">Total Subjects</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-person-badge"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $total_faculty; ?></div>
                                <div class="faculty-stat-lbl">Registered Faculty</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Metric Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card" style="border-left: 4px solid #2563eb;">
                            <div class="faculty-stat-icon" style="background: rgba(37, 99, 235, 0.15); color: #60a5fa;"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo number_format($total_students); ?></div>
                                <div class="faculty-stat-lbl">Enrolled Students</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card" style="border-left: 4px solid #10b981;">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo $today_attendance; ?></div>
                                <div class="faculty-stat-lbl">Today's Attendance Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card" style="border-left: 4px solid #06b6d4;">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-person-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo number_format($present_today); ?></div>
                                <div class="faculty-stat-lbl">Students Present</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card" style="border-left: 4px solid #f43f5e;">
                            <div class="faculty-stat-icon" style="background: rgba(244, 63, 94, 0.15); color: #fb7185;"><i class="bi bi-person-x-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val"><?php echo number_format($absent_today); ?></div>
                                <div class="faculty-stat-lbl">Students Absent</div>
                            </div>
                        </div>
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
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-white"><i class="bi bi-laptop me-2 text-primary"></i>Computer Engineering</div>
                                                <small style="color:#64748b;">Code: CSE</small>
                                            </td>
                                            <td><span style="color:#cbd5e1;">Dr. Sarah Jenkins</span></td>
                                            <td><span class="faculty-badge badge-info-subtle">8 Faculty</span></td>
                                            <td><span class="fw-bold text-white">464</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px;">
                                                        <div class="progress-bar bg-success" style="width:95.2%;"></div>
                                                    </div>
                                                    <small class="fw-bold text-white">95.2%</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2"><i class="bi bi-arrow-right"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-white"><i class="bi bi-cpu me-2 text-info"></i>Information Technology</div>
                                                <small style="color:#64748b;">Code: IT</small>
                                            </td>
                                            <td><span style="color:#cbd5e1;">Prof. Rajesh Sharma</span></td>
                                            <td><span class="faculty-badge badge-info-subtle">6 Faculty</span></td>
                                            <td><span class="fw-bold text-white">348</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px;">
                                                        <div class="progress-bar bg-success" style="width:93.8%;"></div>
                                                    </div>
                                                    <small class="fw-bold text-white">93.8%</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2"><i class="bi bi-arrow-right"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-white"><i class="bi bi-broadcast me-2 text-warning"></i>Electronics &amp; Telecom</div>
                                                <small style="color:#64748b;">Code: ENTC</small>
                                            </td>
                                            <td><span style="color:#cbd5e1;">Dr. Amit Patel</span></td>
                                            <td><span class="faculty-badge badge-info-subtle">4 Faculty</span></td>
                                            <td><span class="fw-bold text-white">261</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height:6px; background:rgba(255,255,255,.08); border-radius:3px;">
                                                        <div class="progress-bar bg-warning" style="width:89.5%;"></div>
                                                    </div>
                                                    <small class="fw-bold text-white">89.5%</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo $base_path; ?>modules/departments/admin-departments.php" class="btn btn-sm btn-outline-info py-1 px-2.5 rounded-2"><i class="bi bi-arrow-right"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Quick Navigation Grid -->
                    <div class="col-lg-4">
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
                <div class="faculty-card mb-0">
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
                                <tr>
                                    <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i>Today, 02:15 PM</td>
                                    <td><span class="fw-semibold text-white">Super Admin</span></td>
                                    <td>Assigned CS504 to Prof. Rajesh Sharma</td>
                                    <td>Subject Allocation</td>
                                    <td style="color:#64748b;font-family:monospace;">192.168.1.45</td>
                                    <td><span class="faculty-badge badge-success-subtle">Success</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#94a3b8;"><i class="bi bi-clock me-1"></i>Today, 01:40 PM</td>
                                    <td><span class="fw-semibold text-white">Prof. Rajesh Sharma</span></td>
                                    <td>Submitted Attendance for CS503</td>
                                    <td>Attendance</td>
                                    <td style="color:#64748b;font-family:monospace;">192.168.1.88</td>
                                    <td><span class="faculty-badge badge-success-subtle">Success</span></td>
                                </tr>
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
