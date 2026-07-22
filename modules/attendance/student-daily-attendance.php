<?php
/**
 * AttendEase - Student Daily Attendance Log
 */
$page_title  = 'Daily Attendance';
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

        <!-- Sidebar -->
        <?php include '../../includes/student-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-calendar-event"></i> Daily Attendance
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Daily Log</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <main class="faculty-content-body">
                
                <!-- Filters Card -->
                <div class="faculty-card mb-4">
                    <div class="p-3">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="attendanceDate" class="form-label" style="color: #cbd5e1; font-weight: 500; font-size: 0.85rem;">Select Date</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8;"><i class="bi bi-calendar-week"></i></span>
                                    <input type="date" class="form-control" id="attendanceDate" name="date" value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : date('Y-m-d'); ?>" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label" style="color: #cbd5e1; font-weight: 500; font-size: 0.85rem;">Status</label>
                                <select class="form-select" id="statusFilter" name="status" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                    <option value="all">All Statuses</option>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-premium w-100" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;"><i class="bi bi-funnel-fill me-2"></i> Filter Records</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daily Attendance Records -->
                <div class="faculty-card">
                    <div class="faculty-card-header">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-clock-history" style="color:#f59e0b;"></i> Daily Check-in Logs</h3>
                            <p class="faculty-card-subtitle">Showing logs for <?php echo isset($_GET['date']) ? date('F j, Y', strtotime($_GET['date'])) : date('F j, Y'); ?></p>
                        </div>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Slot / Time</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="fw-semibold text-white">09:00 – 10:00 AM</div><small style="color:#64748b;">Slot 1</small></td>
                                    <td><span class="faculty-badge badge-blue-subtle">CS501</span></td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Data Structures &amp; Algorithms</span></td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
                                </tr>
                                <tr>
                                    <td><div class="fw-semibold text-white">10:15 – 11:15 AM</div><small style="color:#64748b;">Slot 2</small></td>
                                    <td><span class="faculty-badge badge-blue-subtle">CS502</span></td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Database Management Systems</span></td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
                                </tr>
                                <tr>
                                    <td><div class="fw-semibold text-white">11:30 – 12:30 PM</div><small style="color:#64748b;">Slot 3</small></td>
                                    <td><span class="faculty-badge badge-blue-subtle">CS505</span></td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Software Engineering</span></td>
                                    <td>Prof. Rajesh Sharma</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
                                </tr>
                                <tr>
                                    <td><div class="fw-semibold text-white">01:30 – 02:30 PM</div><small style="color:#64748b;">Slot 4</small></td>
                                    <td><span class="faculty-badge badge-blue-subtle">CS503</span></td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Web Technology Lab</span></td>
                                    <td>Dr. Amit Patel</td>
                                    <td><span class="faculty-badge badge-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i>Absent</span></td>
                                </tr>
                                <tr>
                                    <td><div class="fw-semibold text-white">03:00 – 04:00 PM</div><small style="color:#64748b;">Slot 5</small></td>
                                    <td><span class="faculty-badge badge-blue-subtle">CS504</span></td>
                                    <td><span style="color:#f1f5f9;font-weight:600;">Object-Oriented Programming</span></td>
                                    <td>Prof. Priya Rao</td>
                                    <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
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
