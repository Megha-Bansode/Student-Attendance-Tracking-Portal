<?php
/**
 * AttendEase - Student Dashboard
 * Uses original header.php + footer.php for consistent site-wide nav.
 */
$page_title  = 'Student Dashboard';

/* ── Mock data (replace with DB query later) ── */
$overall_attendance = 82.5; // overall %
$classes_attended = 165;
$classes_conducted = 200;
$subjects_count = 5;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$read_notifs = isset($_SESSION['read_notifs']) ? $_SESSION['read_notifs'] : [];
$deleted_notifs = isset($_SESSION['deleted_notifs']) ? $_SESSION['deleted_notifs'] : [];

$file_path = __DIR__ . '/../../config/notifications.json';
$unread_notifications = 0;
if (file_exists($file_path)) {
    $all_notifs = json_decode(file_get_contents($file_path), true) ?: [];
    foreach ($all_notifs as $n) {
        $id = intval($n['id']);
        if (in_array($id, $deleted_notifs)) {
            continue;
        }
        $is_unread = $n['unread'];
        if (in_array($id, $read_notifs)) {
            $is_unread = false;
        }
        if ($is_unread) {
            $unread_notifications++;
        }
    }
}

include '../../includes/header.php';

?>
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/dashboard.css">
<style>
/* Interactive hover for stats */
.interactive-stat-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    cursor: pointer;
    text-decoration: none !important;
    display: flex;
    align-items: center;
    gap: 15px;
}
.interactive-stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(245, 158, 11, 0.3) !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25) !important;
}
/* Spin / draw animation */
.spinning-pie {
    animation: pie-spin 1.4s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
}
@keyframes pie-spin {
    0% {
        transform: rotate(-270deg);
    }
    100% {
        transform: rotate(-90deg);
    }
}
</style>
<script>
document.body.classList.add('faculty-portal-body');
if (window.innerWidth >= 992 && localStorage.getItem('facultySidebarCollapsed') === 'true') {
    document.documentElement.classList.add('preload-collapsed');
}
</script>

<div class="faculty-portal">
    <!-- ── Student Portal Layout ── -->
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
                            <i class="bi bi-speedometer2"></i> Student Dashboard
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Overview</p>
                    </div>
                </div>
                <div class="faculty-page-band-right">
                    <div class="topbar-date-pill">
                        <i class="bi bi-calendar3"></i>
                        <span><?php echo date('D, M d, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <main class="faculty-content-body">

                <!-- Welcome Banner -->
                <div class="faculty-card welcome-banner-card mb-4" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.05) 100%); border-color: rgba(245, 158, 11, 0.25);">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-circle-fill" style="font-size:.45rem;"></i> Status: Good Standing</span>
                                <span class="faculty-badge badge-blue-subtle"><i class="bi bi-mortarboard-fill"></i> Semester V · Div A</span>
                            </div>
                            <h2 class="h3 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;">Welcome back, Aarav Mehta 👋</h2>
                            <p class="mb-0" style="color:#94a3b8;font-size:.9rem;">
                                Your overall attendance is <strong style="color:#f59e0b;"><?php echo $overall_attendance; ?>%</strong>. Keep maintaining it above the 75% threshold.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo $base_path; ?>reports/student-download-report.php" class="btn btn-premium" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;"><i class="bi bi-file-earmark-arrow-down-fill"></i> Download Report</a>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <!-- Overall Attendance Card -->
                    <div class="col-xl-3 col-sm-6">
                        <div class="faculty-stat-card interactive-stat-card" data-bs-toggle="modal" data-bs-target="#attendancePieModal">
                            <div class="faculty-stat-icon icon-amber" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="bi bi-percent"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $overall_attendance; ?>%</div><div class="faculty-stat-lbl">Overall Attendance</div></div>
                        </div>
                    </div>
                    <!-- Lectures Attended Card -->
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/student-daily-attendance.php" class="faculty-stat-card interactive-stat-card">
                            <div class="faculty-stat-icon icon-emerald"><i class="bi bi-calendar-check-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $classes_attended; ?>/<?php echo $classes_conducted; ?></div><div class="faculty-stat-lbl">Lectures Attended</div></div>
                        </a>
                    </div>
                    <!-- Enrolled Subjects Card -->
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/attendance/student-subject-attendance.php" class="faculty-stat-card interactive-stat-card">
                            <div class="faculty-stat-icon icon-purple"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $subjects_count; ?></div><div class="faculty-stat-lbl">Enrolled Subjects</div></div>
                        </a>
                    </div>
                    <!-- New Notifications Card -->
                    <div class="col-xl-3 col-sm-6">
                        <a href="<?php echo $base_path; ?>modules/notifications/student-notifications.php" class="faculty-stat-card interactive-stat-card">
                            <div class="faculty-stat-icon icon-cyan"><i class="bi bi-bell-fill"></i></div>
                            <div><div class="faculty-stat-val"><?php echo $unread_notifications; ?></div><div class="faculty-stat-lbl">New Notifications</div></div>
                        </a>
                    </div>
                </div>

                <!-- Schedule & Quick Actions -->
                <div class="row g-4 mb-4">
                    <!-- Left: Today's Schedule -->
                    <div class="col-lg-8">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-calendar-event" style="color:#f59e0b;"></i> Today's Class Schedule</h3>
                                    <p class="faculty-card-subtitle">Timetable for <?php echo date('F j, Y'); ?></p>
                                </div>
                                <a href="<?php echo $base_path; ?>modules/attendance/student-daily-attendance.php" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size:.8rem;">View Logs</a>
                            </div>
                            <div class="faculty-table-responsive">
                                <table class="faculty-table">
                                    <thead><tr><th>Time / Slot</th><th>Subject</th><th>Faculty</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">09:00 – 10:00 AM</div><small style="color:#64748b;">Slot 1</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Data Structures &amp; Algorithms</span><br><small style="color:#64748b;">CS501 · Lecture</small></td>
                                            <td>Prof. Rajesh Sharma</td>
                                            <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">10:15 – 11:15 AM</div><small style="color:#64748b;">Slot 2</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Database Management Systems</span><br><small style="color:#64748b;">CS502 · Lecture</small></td>
                                            <td>Prof. Rajesh Sharma</td>
                                            <td><span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">01:30 – 02:30 PM</div><small style="color:#64748b;">Slot 3</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Web Technology Lab</span><br><small style="color:#64748b;">CS503 · Practical</small></td>
                                            <td>Dr. Amit Patel</td>
                                            <td><span class="faculty-badge badge-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i>Absent</span></td>
                                        </tr>
                                        <tr>
                                            <td><div class="fw-semibold" style="color:#f1f5f9;">03:00 – 04:00 PM</div><small style="color:#64748b;">Slot 4</small></td>
                                            <td><span style="color:#f1f5f9;font-weight:600;">Object-Oriented Programming</span><br><small style="color:#64748b;">CS504 · Lecture</small></td>
                                            <td>Prof. Priya Rao</td>
                                            <td><span class="faculty-badge badge-warning-subtle"><i class="bi bi-clock-history me-1"></i>Scheduled</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Leave & Condonation Requests -->
                    <div class="col-lg-4">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-file-earmark-medical-fill" style="color:#f59e0b;"></i> Leave &amp; Condonations</h3>
                                    <p class="faculty-card-subtitle">Condonation applications &amp; status</p>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3 p-2.5 rounded interactive-stat-card" data-bs-toggle="modal" data-bs-target="#leaveModal1" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 10px; border-radius: 10px;">
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width:38px; height:38px; background: rgba(16, 185, 129, 0.15); color: #10b981; flex-shrink: 0;">
                                            <i class="bi bi-check-circle-fill" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Medical Leave (July 10)</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Verified by HOD • 1 Day Approved</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3 p-2.5 rounded interactive-stat-card" data-bs-toggle="modal" data-bs-target="#leaveModal2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 10px; border-radius: 10px;">
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width:38px; height:38px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; flex-shrink: 0;">
                                            <i class="bi bi-clock-history" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Sports Leave (July 18)</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Pending HOD Approval • Inter-College</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3 p-2.5 rounded interactive-stat-card" data-bs-toggle="modal" data-bs-target="#leaveModal3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 10px; border-radius: 10px;">
                                        <div class="rounded d-flex align-items-center justify-content-center" style="width:38px; height:38px; background: rgba(14, 165, 233, 0.15); color: #0ea5e9; flex-shrink: 0;">
                                            <i class="bi bi-hourglass-split" style="font-size: 1.1rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Duty Leave (July 15)</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Under Review • Seminar Attendance</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-top border-light-subtle d-grid gap-2">
                                    <button class="btn btn-sm btn-outline-light text-start py-2 px-3" style="font-size: 0.82rem; border-color: rgba(255,255,255,0.15);" onclick="showFacultyToast('Redirecting to Condonation application page...', 'info')"><i class="bi bi-plus-lg me-2"></i> Apply for Condonation</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<!-- Overall Attendance Modal -->
<div class="modal fade" id="attendancePieModal" tabindex="-1" aria-labelledby="attendancePieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="attendancePieModalLabel"><i class="bi bi-pie-chart-fill text-warning me-2"></i> Attendance Analysis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <!-- Animated Spinning Pie Chart Container -->
                <div class="pie-chart-wrapper position-relative mx-auto" style="width: 200px; height: 200px;">
                    <!-- SVG Pie Chart with Spin Animation -->
                    <svg class="w-100 h-100 spinning-pie" viewBox="0 0 36 36" style="transform: rotate(-90deg); filter: drop-shadow(0px 0px 10px rgba(245, 158, 11, 0.3));">
                        <circle cx="18" cy="18" r="15.9155" style="fill: none; stroke: rgba(255, 255, 255, 0.05); stroke-width: 3;"></circle>
                        <circle class="pie-slice" cx="18" cy="18" r="15.9155" style="fill: none; stroke: #f59e0b; stroke-width: 3.2; stroke-dasharray: 0, 100; stroke-linecap: round; transition: stroke-dasharray 1.2s cubic-bezier(0.4, 0, 0.2, 1);"></circle>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <span class="fs-2 fw-bold text-white font-outfit" id="modalPercentage">82.5%</span>
                        <div class="text-muted" style="font-size: 0.75rem;">Attended</div>
                    </div>
                </div>
                
                <h4 class="mt-4 text-white font-outfit h5">Aarav Mehta — Semester V</h4>
                <p class="text-light-subtitle px-3" style="font-size: 0.88rem; color: #94a3b8;">
                    You have attended <strong class="text-success">165</strong> out of <strong class="text-white">200</strong> total lectures. Your attendance is in the safe zone.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill"></i> Safe Standing</span>
                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill"><i class="bi bi-clock-history"></i> Last Checked: Today</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Modal 1 (Medical Leave) -->
<div class="modal fade" id="leaveModal1" tabindex="-1" aria-labelledby="leaveModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="leaveModal1Label"><i class="bi bi-check-circle-fill text-success me-2"></i> Leave Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px; background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="bi bi-file-earmark-medical" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-white h6 mb-1 fw-bold">Medical Leave (Fever &amp; Recovery)</h4>
                        <span class="badge bg-success-subtle text-success">Approved</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3 text-light-subtitle" style="font-size: 0.88rem;">
                    <div><strong>Date Applied:</strong> July 11, 2026</div>
                    <div><strong>Date of Absence:</strong> July 10, 2026 (1 Day)</div>
                    <div><strong>Document:</strong> <span class="text-info"><i class="bi bi-file-earmark-pdf me-1"></i>medical_certificate.pdf</span></div>
                    <div><strong>Remarks:</strong> Verified by Dr. Rajesh Sharma (HOD). Attendance marked as Condoned.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Modal 2 (Sports Leave) -->
<div class="modal fade" id="leaveModal2" tabindex="-1" aria-labelledby="leaveModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="leaveModal2Label"><i class="bi bi-clock-history text-warning me-2"></i> Leave Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <i class="bi bi-trophy" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-white h6 mb-1 fw-bold">Sports Leave (Inter-College Cricket)</h4>
                        <span class="badge bg-warning-subtle text-warning">Pending Review</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3 text-light-subtitle" style="font-size: 0.88rem;">
                    <div><strong>Date Applied:</strong> July 19, 2026</div>
                    <div><strong>Date of Absence:</strong> July 18, 2026 (1 Day)</div>
                    <div><strong>Document:</strong> <span class="text-info"><i class="bi bi-file-earmark-pdf me-1"></i>sports_invitation.pdf</span></div>
                    <div><strong>Remarks:</strong> Under verification by Physical Education Director.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Modal 3 (Duty Leave) -->
<div class="modal fade" id="leaveModal3" tabindex="-1" aria-labelledby="leaveModal3Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(14, 165, 233, 0.25); border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white font-outfit" id="leaveModal3Label"><i class="bi bi-hourglass-split text-info me-2"></i> Leave Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px; height:48px; background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                        <i class="bi bi-briefcase" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h4 class="text-white h6 mb-1 fw-bold">Duty Leave (AI &amp; ML Seminar)</h4>
                        <span class="badge bg-info-subtle text-info">Under Review</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-3 text-light-subtitle" style="font-size: 0.88rem;">
                    <div><strong>Date Applied:</strong> July 16, 2026</div>
                    <div><strong>Date of Absence:</strong> July 15, 2026 (1 Day)</div>
                    <div><strong>Document:</strong> <span class="text-info"><i class="bi bi-file-earmark-pdf me-1"></i>seminar_attendance_certificate.pdf</span></div>
                    <div><strong>Remarks:</strong> Forwarded to Faculty Mentor for verification.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const myModal = document.getElementById('attendancePieModal');
    if (myModal) {
        myModal.addEventListener('shown.bs.modal', function () {
            const slice = myModal.querySelector('.pie-slice');
            if (slice) {
                // Set dasharray to trigger drawing animation
                slice.style.strokeDasharray = '82.5, 100';
            }
        });
        myModal.addEventListener('hidden.bs.modal', function () {
            const slice = myModal.querySelector('.pie-slice');
            if (slice) {
                slice.style.strokeDasharray = '0, 100';
            }
        });
    }
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
