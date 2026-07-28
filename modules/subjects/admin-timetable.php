<?php
/**
 * AttendEase - Super Admin Timetable Management
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../authentication/login.php");
    exit;
}

// Add/Edit Schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_schedule') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $subject_id = intval($_POST['subject_id']);
    $class = trim($_POST['class']);
    $division = trim($_POST['division']);
    $day_of_week = trim($_POST['day_of_week']);
    $start_time = trim($_POST['start_time']); // Expected e.g. "09:00 AM"
    $end_time = trim($_POST['end_time']);

    $new_start = strtotime($start_time);
    $new_end = strtotime($end_time);

    // Override flag
    $override = isset($_POST['override']) && $_POST['override'] == '1';

    if (!$override && $id == 0) {
        // Conflict check for new schedule
        $stmt_day = $pdo->prepare("SELECT s.*, fs.faculty_id FROM schedules s LEFT JOIN faculty_subjects fs ON s.subject_id = fs.subject_id WHERE s.day_of_week = ?");
        $stmt_day->execute([$day_of_week]);
        $day_schedules = $stmt_day->fetchAll();

        $conflict_msg = '';
        $conflict_ids = [];

        $stmt_fac = $pdo->prepare("SELECT faculty_id FROM faculty_subjects WHERE subject_id = ?");
        $stmt_fac->execute([$subject_id]);
        $new_faculty_id = $stmt_fac->fetchColumn();

        foreach($day_schedules as $s) {
            $s_start = strtotime($s['start_time']);
            $s_end = strtotime($s['end_time']);
            
            // Check overlap
            if ($new_start < $s_end && $new_end > $s_start) {
                // Faculty conflict
                if ($new_faculty_id && $s['faculty_id'] == $new_faculty_id) {
                    $conflict_msg = "The assigned faculty is already scheduled for another lecture at this time";
                    $conflict_ids[] = $s['id'];
                }
                
                // Class conflict
                if ($s['class'] === $class && $s['division'] === $division) {
                    $conflict_msg = "This Class & Division already has a lecture scheduled at this time";
                    $conflict_ids[] = $s['id'];
                }
            }
        }

        if (!empty($conflict_ids)) {
            $_SESSION['conflict_alert'] = [
                'msg' => $conflict_msg,
                'conflict_ids' => implode(',', array_unique($conflict_ids)),
                'post_data' => $_POST
            ];
            header("Location: admin-timetable.php");
            exit;
        }
    }

    if ($override && isset($_POST['conflict_ids'])) {
        $ids_to_delete = explode(',', $_POST['conflict_ids']);
        foreach($ids_to_delete as $c_id) {
            $pdo->prepare("DELETE FROM schedules WHERE id = ?")->execute([$c_id]);
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE schedules SET subject_id = ?, class = ?, division = ?, day_of_week = ?, start_time = ?, end_time = ? WHERE id = ?");
        $stmt->execute([$subject_id, $class, $division, $day_of_week, $start_time, $end_time, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO schedules (subject_id, class, division, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$subject_id, $class, $division, $day_of_week, $start_time, $end_time]);
    }
    
    $_SESSION['sweet_msg'] = 'Schedule saved successfully!';
    header("Location: admin-timetable.php");
    exit;
}

// Delete Schedule
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $pdo->prepare("DELETE FROM schedules WHERE id = ?")->execute([$delete_id]);
    $_SESSION['sweet_msg'] = 'Schedule deleted successfully!';
    header("Location: admin-timetable.php");
    exit;
}

// Fetch all schedules
$schedules = $pdo->query("
    SELECT s.*, subj.name AS subject_name, u.name AS faculty_name 
    FROM schedules s 
    LEFT JOIN subjects subj ON s.subject_id = subj.id 
    LEFT JOIN faculty_subjects fs ON subj.id = fs.subject_id
    LEFT JOIN users u ON fs.faculty_id = u.id
    ORDER BY CASE s.day_of_week 
        WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 
        WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 WHEN 'Saturday' THEN 6 ELSE 7 END, 
        s.start_time ASC
")->fetchAll();

$subjects = $pdo->query("SELECT * FROM subjects ORDER BY name ASC")->fetchAll();

$page_title       = 'Timetable Management';
$page_breadcrumb  = 'AttendEase / Super Admin / Timetables';
$page_icon        = 'bi-calendar-week-fill';

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
        <?php include '../../includes/admin-sidebar.php'; ?>
        
        <div class="faculty-main-content">
            <?php include '../../includes/admin-topbar.php'; ?>
            
            <main class="faculty-content-body">
                <!-- Schedule Table Card -->
                <div class="faculty-card mb-0 animate-fade-up">
                    <div class="faculty-card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-calendar-week-fill" style="color:#60a5fa;"></i> Class Timetables</h3>
                            <p class="faculty-card-subtitle">Manage daily lectures and faculty assignments</p>
                        </div>
                        <div class="d-flex align-items-center gap-3 ms-auto flex-wrap justify-content-end">
                            <button type="button" class="btn btn-sm btn-premium" data-bs-toggle="modal" data-bs-target="#addScheduleModal" onclick="prepareAddSchedule()">
                                <i class="bi bi-plus-lg"></i> Add Schedule
                            </button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Time Slot</th>
                                    <th>Subject & Faculty</th>
                                    <th>Class & Division</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($schedules)): ?>
                                    <tr><td colspan="5" class="text-center py-4">No schedules found.</td></tr>
                                <?php else: foreach ($schedules as $sched): ?>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary font-mono fw-bold"><?php echo htmlspecialchars($sched['day_of_week']); ?></span></td>
                                    <td><div class="fw-semibold text-white"><?php echo htmlspecialchars($sched['start_time'] . ' - ' . $sched['end_time']); ?></div></td>
                                    <td>
                                        <div class="fw-semibold text-white"><?php echo htmlspecialchars($sched['subject_name']); ?></div>
                                        <small style="color:#64748b;"><?php echo htmlspecialchars($sched['faculty_name'] ?? 'No Faculty Assigned'); ?></small>
                                    </td>
                                    <td><span class="faculty-badge badge-info-subtle"><?php echo htmlspecialchars($sched['class'] . ' - ' . $sched['division']); ?></span></td>
                                    <td class="text-end">
                                        <a href="admin-timetable.php?delete_id=<?php echo $sched['id']; ?>" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="return confirm('Delete this schedule?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Modal: Add Schedule -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-light" style="background:#131c31; border:1px solid rgba(255,255,255,.15); border-radius:16px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title font-outfit fw-bold text-white" id="modal_title"><i class="bi bi-calendar-plus-fill me-2 text-primary"></i>Add New Schedule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="admin-timetable.php" method="POST">
                <input type="hidden" name="action" value="save_schedule">
                <input type="hidden" name="id" id="modal_schedule_id" value="0">
                
                <div class="modal-body space-y-3">
                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Subject</label>
                        <select name="subject_id" id="modal_subject_id" class="form-select bg-dark text-white border-secondary" required>
                            <?php foreach ($subjects as $sub): ?>
                                <option value="<?php echo $sub['id']; ?>"><?php echo htmlspecialchars($sub['name'] . ' (' . $sub['class'] . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Class</label>
                            <select name="class" id="modal_class" class="form-select bg-dark text-white border-secondary" required>
                                <option value="First Year">First Year</option>
                                <option value="Second Year">Second Year</option>
                                <option value="Third Year">Third Year</option>
                                <option value="Fourth Year">Fourth Year</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Division</label>
                            <select name="division" id="modal_division" class="form-select bg-dark text-white border-secondary" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-slate-300 font-semibold">Day of Week</label>
                        <select name="day_of_week" id="modal_day" class="form-select bg-dark text-white border-secondary" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">Start Time</label>
                            <input type="text" name="start_time" id="modal_start" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 09:00 AM" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-slate-300 font-semibold">End Time</label>
                            <input type="text" name="end_time" id="modal_end" class="form-control bg-dark text-white border-secondary" placeholder="e.g. 10:00 AM" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function prepareAddSchedule() {
    document.getElementById('modal_schedule_id').value = '0';
    document.getElementById('modal_subject_id').selectedIndex = 0;
    document.getElementById('modal_class').value = 'First Year';
    document.getElementById('modal_division').value = 'A';
    document.getElementById('modal_day').value = 'Monday';
    document.getElementById('modal_start').value = '09:00 AM';
    document.getElementById('modal_end').value = '10:00 AM';
}

document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($_SESSION['sweet_msg'])): ?>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '<?php echo addslashes($_SESSION['sweet_msg']); ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        <?php unset($_SESSION['sweet_msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['conflict_alert'])): ?>
        Swal.fire({
            title: 'Conflict Detected!',
            text: "<?php echo addslashes($_SESSION['conflict_alert']['msg']); ?>. Do you want to discard the old lecture and assign this new one?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, overwrite it!',
            cancelButtonText: 'No, keep old'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin-timetable.php';
                
                <?php foreach($_SESSION['conflict_alert']['post_data'] as $k => $v): ?>
                    const input_<?php echo $k; ?> = document.createElement('input');
                    input_<?php echo $k; ?>.type = 'hidden';
                    input_<?php echo $k; ?>.name = '<?php echo $k; ?>';
                    input_<?php echo $k; ?>.value = '<?php echo addslashes($v); ?>';
                    form.appendChild(input_<?php echo $k; ?>);
                <?php endforeach; ?>
                
                const input_ov = document.createElement('input');
                input_ov.type = 'hidden';
                input_ov.name = 'override';
                input_ov.value = '1';
                form.appendChild(input_ov);
                
                const input_ids = document.createElement('input');
                input_ids.type = 'hidden';
                input_ids.name = 'conflict_ids';
                input_ids.value = '<?php echo $_SESSION['conflict_alert']['conflict_ids']; ?>';
                form.appendChild(input_ids);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
        <?php unset($_SESSION['conflict_alert']); ?>
    <?php endif; ?>
});
</script>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
