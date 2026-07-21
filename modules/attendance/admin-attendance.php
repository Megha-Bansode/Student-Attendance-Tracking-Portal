<?php
/**
 * AttendEase - Super Admin Attendance Audit & Management
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
$page_title       = 'Attendance Audit & Records';
$page_breadcrumb  = 'AttendEase / Super Admin / Attendance Audit';
$page_icon        = 'bi-calendar-check-fill';

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

            <!-- Topbar Page Band -->
            <?php include '../../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-calendar-check-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">92.4%</div>
                                <div class="faculty-stat-lbl">Today's Presence Rate</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-journal-check"></i></div>
                            <div>
                                <div class="faculty-stat-val">28 / 32</div>
                                <div class="faculty-stat-lbl">Lectures Conducted</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-amber"><i class="bi bi-pencil-square"></i></div>
                            <div>
                                <div class="faculty-stat-val">2 Pending</div>
                                <div class="faculty-stat-lbl">Edit Approval Requests</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-shield-lock-fill"></i></div>
                            <div>
                                <div class="faculty-stat-val">48 Hours</div>
                                <div class="faculty-stat-lbl">Locking Window</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Request Approvals Card -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-pencil-square" style="color:#fbbf24;"></i> Pending Faculty Correction Requests</h3>
                            <p class="faculty-card-subtitle">Review &amp; approve historical attendance edit requests older than 48 hours</p>
                        </div>
                        <span class="faculty-badge badge-warning-subtle">2 Approvals Action Needed</span>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Faculty Name</th>
                                    <th>Subject &amp; Code</th>
                                    <th>Class / Div</th>
                                    <th>Date of Class</th>
                                    <th>Reason for Edit</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="fw-semibold text-white">Prof. Rajesh Sharma</span></td>
                                    <td>Data Structures (CS501)</td>
                                    <td>TE CSE – Div A</td>
                                    <td>19 Jul 2026</td>
                                    <td><span style="color:#cbd5e1;">Medical certificate submitted by Roll No. 2026CS103</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-success py-1 px-3 me-1" onclick="alert('Request Approved!')"><i class="bi bi-check-lg me-1"></i>Approve</button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2" onclick="alert('Request Rejected!')"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="fw-semibold text-white">Dr. Sarah Jenkins</span></td>
                                    <td>Operating Systems (IT301)</td>
                                    <td>SE IT – Div A</td>
                                    <td>18 Jul 2026</td>
                                    <td><span style="color:#cbd5e1;">Biometric scanner sync delay adjustment</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-success py-1 px-3 me-1" onclick="alert('Request Approved!')"><i class="bi bi-check-lg me-1"></i>Approve</button>
                                        <button class="btn btn-sm btn-outline-danger py-1 px-2" onclick="alert('Request Rejected!')"><i class="bi bi-x-lg"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Daily Audit Logs Table -->
                <div class="faculty-card mb-0">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-clock-history" style="color:#38bdf8;"></i> Institution Daily Attendance Stream</h3>
                            <p class="faculty-card-subtitle">Real-time record of all class attendance submissions across departments</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" class="form-control form-control-sm bg-dark text-light border-secondary" value="<?php echo date('Y-m-d'); ?>">
                            <button class="btn btn-sm btn-outline-light"><i class="bi bi-filter"></i> Filter</button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Time Submitted</th>
                                    <th>Department</th>
                                    <th>Subject &amp; Code</th>
                                    <th>Faculty</th>
                                    <th>Present / Total</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Today, 01:45 PM</td>
                                    <td>Computer Engineering</td>
                                    <td>Web Technology Lab (CS503)</td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="fw-bold text-success">58</span> / 60</td>
                                    <td>96.6%</td>
                                    <td><span class="faculty-badge badge-success-subtle">Verified</span></td>
                                </tr>
                                <tr>
                                    <td>Today, 10:25 AM</td>
                                    <td>Computer Engineering</td>
                                    <td>Database Management (CS502)</td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="fw-bold text-success">55</span> / 60</td>
                                    <td>91.6%</td>
                                    <td><span class="faculty-badge badge-success-subtle">Verified</span></td>
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
