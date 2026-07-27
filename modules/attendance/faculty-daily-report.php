<?php
/**
 * AttendEase - Faculty Daily Attendance Report
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../authentication/login.php");
    exit;
}

$faculty_id = $_SESSION['user_id'];
$faculty_name = $_SESSION['name'];

// Fetch Assigned Subjects
$stmt_fac_sub = $pdo->prepare("
    SELECT s.* FROM subjects s
    JOIN faculty_subjects fs ON s.id = fs.subject_id
    WHERE fs.faculty_id = ?
");
$stmt_fac_sub->execute([$faculty_id]);
$fac_subjects = $stmt_fac_sub->fetchAll();

$selected_subject_id = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : (count($fac_subjects) > 0 ? $fac_subjects[0]['id'] : 0);
$default_class = 'First Year';
$default_div = 'B';

if ($selected_subject_id > 0) {
    foreach ($fac_subjects as $fs) {
        if ($fs['id'] == $selected_subject_id) {
            $default_class = $fs['class'];
            break;
        }
    }
}

// Fetch faculty profile for division default
$stmt_fac = $pdo->prepare("SELECT class, division FROM users WHERE id = ?");
$stmt_fac->execute([$faculty_id]);
$fac_info = $stmt_fac->fetch();
if ($fac_info && !empty($fac_info['division'])) {
    $default_div = $fac_info['division'];
}

$selected_div        = isset($_GET['division'])   ? $_GET['division']   : $default_div;
$selected_class      = isset($_GET['class'])      ? $_GET['class']      : $default_class;
$selected_date       = isset($_GET['date'])       ? $_GET['date']       : date('Y-m-d');

// Fetch students and their current attendance records for selected slot/date
$stmt_att = $pdo->prepare("
    SELECT u.id AS student_id, u.name, u.zprn, a.status AS attendance_status
    FROM users u
    LEFT JOIN attendance a ON u.id = a.student_id AND a.date = ? AND a.subject_id = ?
    WHERE u.role = 'student' AND u.class = ? AND u.division = ?
    ORDER BY u.name ASC
");
$stmt_att->execute([$selected_date, $selected_subject_id, $selected_class, $selected_div]);
$students_att = $stmt_att->fetchAll();

$total_students = count($students_att);
$present_count = 0;
foreach ($students_att as $s) {
    if ($s['attendance_status'] === 'Present') {
        $present_count++;
    }
}
$absent_count = $total_students - $present_count;
$attendance_percent = $total_students > 0 ? round(($present_count / $total_students) * 100, 1) : 0;

$page_title = 'Daily Attendance Report';
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
                    <h1 class="faculty-page-band-title"><i class="bi bi-file-earmark-bar-graph-fill"></i> Daily Attendance Report</h1>
                    <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Daily Report</p>
                </div>
            </div>
            <div class="faculty-page-band-right">
                <div class="topbar-date-pill"><i class="bi bi-calendar3"></i><span><?php echo date('D, M d, Y'); ?></span></div>
            </div>
        </div>

        <main class="faculty-content-body">

            <!-- Context Filter Form -->
            <form method="GET" action="faculty-daily-report.php" class="faculty-card mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="faculty-form-label">Date</label>
                        <input type="date" name="date" class="faculty-input" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Subject</label>
                        <select name="subject_id" class="faculty-select" onchange="this.form.submit()">
                            <?php foreach ($fac_subjects as $fs): ?>
                                <option value="<?php echo $fs['id']; ?>" <?php echo ($selected_subject_id == $fs['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fs['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Class</label>
                        <input type="text" name="class" class="faculty-input" value="<?php echo htmlspecialchars($selected_class); ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="faculty-form-label">Division</label>
                        <select name="division" class="faculty-select" onchange="this.form.submit()">
                            <option value="A" <?php echo ($selected_div === 'A') ? 'selected' : ''; ?>>Division A</option>
                            <option value="B" <?php echo ($selected_div === 'B') ? 'selected' : ''; ?>>Division B</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="faculty-stat-card">
                        <div class="faculty-stat-icon icon-cyan"><i class="bi bi-people-fill"></i></div>
                        <div><div class="faculty-stat-val"><?php echo $total_students; ?></div><div class="faculty-stat-lbl">Total Students</div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faculty-stat-card">
                        <div class="faculty-stat-icon icon-emerald"><i class="bi bi-person-check-fill"></i></div>
                        <div><div class="faculty-stat-val"><?php echo $present_count; ?></div><div class="faculty-stat-lbl">Present</div></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faculty-stat-card">
                        <div class="faculty-stat-icon icon-purple"><i class="bi bi-percent"></i></div>
                        <div><div class="faculty-stat-val"><?php echo $attendance_percent; ?>%</div><div class="faculty-stat-lbl">Attendance Rate</div></div>
                    </div>
                </div>
            </div>

            <!-- Student Table -->
            <div class="faculty-card">
                <div class="faculty-card-header mb-3 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h3 class="faculty-card-title"><i class="bi bi-list-columns-reverse" style="color:#818cf8;"></i> Attendance Roll Call</h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-danger px-3 py-2" id="btnDownloadPDF" style="font-size: 0.8rem;"><i class="bi bi-file-pdf-fill me-1"></i> Export PDF</button>
                        <button class="btn btn-sm btn-outline-success px-3 py-2" id="btnDownloadExcel" style="font-size: 0.8rem;"><i class="bi bi-file-excel-fill me-1"></i> Export Excel</button>
                    </div>
                </div>
                <div class="faculty-table-responsive">
                    <table class="faculty-table">
                        <thead>
                            <tr>
                                <th>Roll / PRN</th>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students_att)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-secondary py-4">No students found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students_att as $s): ?>
                                    <tr>
                                        <td class="fw-bold" style="color:#f1f5f9;"><?php echo htmlspecialchars($s['zprn']); ?></td>
                                        <td style="color:#f1f5f9;font-weight:500;"><i class="bi bi-person-circle me-2" style="color:#475569;"></i><?php echo htmlspecialchars($s['name']); ?></td>
                                        <td>
                                            <?php if ($s['attendance_status'] === 'Present'): ?>
                                                <span class="faculty-badge badge-success-subtle"><i class="bi bi-check-circle-fill me-1"></i>Present</span>
                                            <?php elseif ($s['attendance_status'] === 'Absent'): ?>
                                                <span class="faculty-badge badge-danger-subtle"><i class="bi bi-x-circle-fill me-1"></i>Absent</span>
                                            <?php else: ?>
                                                <span class="faculty-badge badge-warning-subtle"><i class="bi bi-exclamation-circle-fill me-1"></i>Not Marked</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
</div>

<!-- Include html2pdf.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pdfBtn = document.getElementById('btnDownloadPDF');
    const excelBtn = document.getElementById('btnDownloadExcel');

    pdfBtn.addEventListener('click', function() {
        const reportContainer = document.createElement('div');
        reportContainer.style.padding = '40px';
        reportContainer.style.fontFamily = 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif';
        reportContainer.style.color = '#1e293b';
        reportContainer.style.backgroundColor = '#ffffff';

        reportContainer.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                <div>
                    <h1 style="margin: 0; font-size: 24px; color: #0f172a; font-weight: 700; letter-spacing: -0.5px;">AttendEase</h1>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Daily Faculty Report</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 12px; color: #64748b;">Report Date</p>
                    <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($selected_date); ?>}</p>
                </div>
            </div>

            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Faculty Name</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($faculty_name); ?>}</p>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Class & Division</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($selected_class); ?>} - Div ${<?php echo json_encode($selected_div); ?>}</p>
                </div>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
                <thead>
                    <tr style="background-color: #0f172a; color: #ffffff;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600;">PRN</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600;">Name</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${Array.from(document.querySelectorAll('.faculty-table tbody tr')).map(row => {
                        const cells = row.querySelectorAll('td');
                        if (cells.length < 3) return '';
                        
                        const prn = cells[0].innerText;
                        const name = cells[1].innerText;
                        const status = cells[2].innerText;
                        
                        const color = status.includes('Present') ? '#16a34a' : (status.includes('Absent') ? '#dc2626' : '#d97706');
                        
                        return `
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 14px 16px; font-size: 13px; font-weight: 600; color: #0f172a;">${prn}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: #334155;">${name}</td>
                                <td style="padding: 14px 16px; font-size: 13px; text-align: right; font-weight: 700; color: ${color};">${status}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;

        const opt = {
            margin:       10,
            filename:     `Faculty_Report_${<?php echo json_encode($selected_date); ?>}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(reportContainer).save();
    });

    excelBtn.addEventListener('click', function() {
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Faculty Name," + <?php echo json_encode($faculty_name); ?> + "\r\n";
        csvContent += "Date," + <?php echo json_encode($selected_date); ?> + "\r\n";
        csvContent += "Class," + <?php echo json_encode($selected_class); ?> + "\r\n";
        csvContent += "Division," + <?php echo json_encode($selected_div); ?> + "\r\n\r\n";
        
        csvContent += "PRN,Student Name,Status\r\n";
        
        Array.from(document.querySelectorAll('.faculty-table tbody tr')).forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 3) return;
            
            const prn = `"${cells[0].innerText.replace(/"/g, '""')}"`;
            const name = `"${cells[1].innerText.replace(/"/g, '""')}"`;
            const status = cells[2].innerText;
            
            csvContent += `${prn},${name},${status}\r\n`;
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `Faculty_Report_${<?php echo json_encode($selected_date); ?>}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
