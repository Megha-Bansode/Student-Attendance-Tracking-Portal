<?php
/**
 * AttendEase - Faculty Attendance Marking Page
 */
$page_title = 'Mark Attendance';

$selected_subject = isset($_GET['subject']) ? $_GET['subject'] : 'CS501';
$selected_div     = isset($_GET['div'])     ? $_GET['div']     : 'A';
$selected_slot    = isset($_GET['slot'])    ? $_GET['slot']    : '1';

/* ── Mock Students (replace with DB query) ── */
$mock_students = [
    ['roll'=>'101','prn'=>'2023CSE0101','name'=>'Aarav Sharma',    'status'=>'present','remarks'=>''],
    ['roll'=>'102','prn'=>'2023CSE0102','name'=>'Ananya Deshmukh', 'status'=>'present','remarks'=>''],
    ['roll'=>'103','prn'=>'2023CSE0103','name'=>'Devansh Kulkarni','status'=>'present','remarks'=>''],
    ['roll'=>'104','prn'=>'2023CSE0104','name'=>'Diya Patel',      'status'=>'absent', 'remarks'=>'Medical Leave'],
    ['roll'=>'105','prn'=>'2023CSE0105','name'=>'Ishan Verma',     'status'=>'present','remarks'=>''],
    ['roll'=>'106','prn'=>'2023CSE0106','name'=>'Kavya Joshi',     'status'=>'present','remarks'=>''],
    ['roll'=>'107','prn'=>'2023CSE0107','name'=>'Manish Mehta',    'status'=>'present','remarks'=>''],
    ['roll'=>'108','prn'=>'2023CSE0108','name'=>'Neha Gupta',      'status'=>'absent', 'remarks'=>'Unexcused'],
    ['roll'=>'109','prn'=>'2023CSE0109','name'=>'Pranav Rao',      'status'=>'present','remarks'=>''],
    ['roll'=>'110','prn'=>'2023CSE0110','name'=>'Riya Shah',       'status'=>'present','remarks'=>''],
    ['roll'=>'111','prn'=>'2023CSE0111','name'=>'Siddharth Nair',  'status'=>'present','remarks'=>''],
    ['roll'=>'112','prn'=>'2023CSE0112','name'=>'Tanvi Patil',     'status'=>'present','remarks'=>''],
];

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
    <?php include 'includes/faculty-sidebar.php'; ?>

    <div class="faculty-main-content">

        <!-- Page Band -->
        <div class="faculty-page-band">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="faculty-page-band-title"><i class="bi bi-check-circle-fill"></i> Mark Attendance</h1>
                    <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Mark Attendance</p>
                </div>
            </div>
            <div class="faculty-page-band-right">
                <div class="topbar-date-pill"><i class="bi bi-calendar3"></i><span><?php echo date('D, M d, Y'); ?></span></div>
            </div>
        </div>

        <main class="faculty-content-body">

            <!-- Page Intro -->
            <div class="faculty-card mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="h5 fw-bold mb-1" style="color:#f1f5f9;font-family:'Outfit',sans-serif;"><i class="bi bi-check-circle-fill me-2" style="color:#34d399;"></i>Mark Class Attendance</h2>
                        <p class="mb-0" style="color:#64748b;font-size:.875rem;">Select session details, review student statuses, and submit attendance records.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="faculty-badge badge-blue-subtle" style="font-size:.82rem;padding:.45rem .85rem;"><i class="bi bi-clock-history me-1"></i> Real-time Marking Mode</span>
                    </div>
                </div>
            </div>

            <form id="markAttendanceForm" action="mark-attendance.php" method="POST" novalidate>

                <!-- Step 1 – Session Context -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header mb-3 pb-2">
                        <h3 class="faculty-card-title"><i class="bi bi-funnel-fill" style="color:#818cf8;"></i> Step 1 — Select Session Context</h3>
                    </div>
                    <div class="row g-3">
                        <div class="col-xl-3 col-md-6">
                            <label for="markDateInput" class="faculty-form-label"><i class="bi bi-calendar3 me-1" style="color:#60a5fa;"></i> Date <span class="text-danger">*</span></label>
                            <input type="date" id="markDateInput" name="attendance_date" class="faculty-input" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <label for="markSubjectSelect" class="faculty-form-label"><i class="bi bi-journal-text me-1" style="color:#60a5fa;"></i> Subject <span class="text-danger">*</span></label>
                            <select id="markSubjectSelect" name="subject_code" class="faculty-select" required>
                                <option value="">-- Select Subject --</option>
                                <option value="CS501" <?php echo ($selected_subject==='CS501')?'selected':''; ?>>CS501 – Data Structures & Algorithms</option>
                                <option value="CS502" <?php echo ($selected_subject==='CS502')?'selected':''; ?>>CS502 – Database Management Systems</option>
                                <option value="CS503" <?php echo ($selected_subject==='CS503')?'selected':''; ?>>CS503 – Web Technology Lab</option>
                                <option value="CS504" <?php echo ($selected_subject==='CS504')?'selected':''; ?>>CS504 – Object-Oriented Programming</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <label for="markDivisionSelect" class="faculty-form-label"><i class="bi bi-building me-1" style="color:#60a5fa;"></i> Division <span class="text-danger">*</span></label>
                            <select id="markDivisionSelect" name="division" class="faculty-select" required>
                                <option value="">-- Select Division --</option>
                                <option value="A" <?php echo ($selected_div==='A')?'selected':''; ?>>Division A (TE CSE)</option>
                                <option value="B" <?php echo ($selected_div==='B')?'selected':''; ?>>Division B (TE CSE)</option>
                                <option value="C" <?php echo ($selected_div==='C')?'selected':''; ?>>Division C (SE CSE)</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <label for="markLectureSelect" class="faculty-form-label"><i class="bi bi-clock me-1" style="color:#60a5fa;"></i> Lecture Slot <span class="text-danger">*</span></label>
                            <select id="markLectureSelect" name="lecture_slot" class="faculty-select" required>
                                <option value="">-- Select Slot --</option>
                                <option value="1" <?php echo ($selected_slot==='1')?'selected':''; ?>>Slot 1 (09:00 – 10:00 AM)</option>
                                <option value="2" <?php echo ($selected_slot==='2')?'selected':''; ?>>Slot 2 (10:15 – 11:15 AM)</option>
                                <option value="3" <?php echo ($selected_slot==='3')?'selected':''; ?>>Slot 3 (01:30 – 02:30 PM)</option>
                                <option value="4" <?php echo ($selected_slot==='4')?'selected':''; ?>>Slot 4 (03:00 – 04:00 PM)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2 – Student Sheet -->
                <div class="faculty-card">
                    <!-- Sheet header with live counters -->
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,.06);">
                        <div>
                            <h3 class="faculty-card-title mb-1"><i class="bi bi-people-fill" style="color:#38bdf8;"></i> Step 2 — Student Attendance Sheet</h3>
                            <p class="faculty-card-subtitle">Toggle Present / Absent for each student</p>
                        </div>
                        <div class="counter-box-group">
                            <div class="counter-pill">
                                <span style="color:#64748b;">Total</span>
                                <span class="counter-pill-val" style="color:#f1f5f9;" id="statTotalStudents">0</span>
                            </div>
                            <div class="counter-pill" style="border-color:rgba(16,185,129,.35);">
                                <i class="bi bi-check-circle-fill" style="color:#34d399;"></i>
                                <span class="counter-pill-val" style="color:#34d399;" id="statPresentCount">0</span>
                            </div>
                            <div class="counter-pill" style="border-color:rgba(239,68,68,.35);">
                                <i class="bi bi-x-circle-fill" style="color:#f87171;"></i>
                                <span class="counter-pill-val" style="color:#f87171;" id="statAbsentCount">0</span>
                            </div>
                            <div class="counter-pill" style="border-color:rgba(96,165,250,.35);">
                                <i class="bi bi-percent" style="color:#60a5fa;"></i>
                                <span class="counter-pill-val" style="color:#60a5fa;" id="statAttendanceRate">0%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Controls -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 p-3 rounded-3" style="background:rgba(15,23,42,.6);border:1px solid rgba(255,255,255,.06);">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btnMarkAllPresent"><i class="bi bi-check-all me-1"></i> All Present</button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3"  id="btnMarkAllAbsent"><i class="bi bi-x-lg me-1"></i> All Absent</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnResetAttendance"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</button>
                        </div>
                        <small style="color:#475569;"><i class="bi bi-info-circle me-1"></i> Counters update live as you toggle.</small>
                    </div>

                    <!-- Student Table -->
                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="studentAttendanceTable">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Roll</th>
                                    <th style="width:150px;">PRN</th>
                                    <th>Student Name</th>
                                    <th class="text-center" style="width:210px;">Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mock_students as $s): ?>
                                    <tr>
                                        <td class="fw-bold" style="color:#f1f5f9;"><?php echo htmlspecialchars($s['roll']); ?></td>
                                        <td><span class="faculty-badge badge-secondary-subtle"><?php echo htmlspecialchars($s['prn']); ?></span></td>
                                        <td style="color:#f1f5f9;font-weight:500;"><i class="bi bi-person-circle me-2" style="color:#475569;"></i><?php echo htmlspecialchars($s['name']); ?></td>
                                        <td class="text-center">
                                            <input type="hidden" name="attendance[<?php echo $s['prn']; ?>]" class="student-status-input" value="<?php echo $s['status']; ?>">
                                            <div class="attendance-toggle-group">
                                                <button type="button" class="attendance-toggle-btn btn-present <?php echo ($s['status']==='present')?'active':''; ?>"><i class="bi bi-check-lg"></i> Present</button>
                                                <button type="button" class="attendance-toggle-btn btn-absent  <?php echo ($s['status']==='absent') ?'active':''; ?>"><i class="bi bi-x-lg"></i> Absent</button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="remarks[<?php echo $s['prn']; ?>]" class="faculty-input py-1 px-2" style="font-size:.8rem;" placeholder="Optional remark..." value="<?php echo htmlspecialchars($s['remarks']); ?>"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Row -->
                    <div class="d-flex align-items-center justify-content-between pt-4 mt-2" style="border-top:1px solid rgba(255,255,255,.06);">
                        <a href="dashboard.php" class="btn btn-outline-light rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Back</a>
                        <button type="submit" class="btn btn-premium px-4 py-2" id="btnSubmitAttendance">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save & Submit Attendance
                        </button>
                    </div>
                </div>

            </form>

        </main>

    </div>
</div>
</div>
<script src="assets/js/faculty.js"></script>
<?php include 'includes/footer.php'; ?>
