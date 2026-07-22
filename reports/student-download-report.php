<?php
/**
 * AttendEase - Student Download Report
 */
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../authentication/login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$student_name = $_SESSION['name'];
$student_prn = $_SESSION['zprn'];
$student_class = $_SESSION['class'] ?? 'First Year';
$student_division = $_SESSION['division'] ?? 'A';

// Fetch all subjects matching student's class
$stmt_subj = $pdo->prepare("SELECT * FROM subjects WHERE class = ? ORDER BY name ASC");
$stmt_subj->execute([$student_class]);
$subjects = $stmt_subj->fetchAll();

$report_data = [];
foreach ($subjects as $subj) {
    $subj_id = $subj['id'];
    
    // Count lectures held
    $stmt_held = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ?");
    $stmt_held->execute([$student_id, $subj_id]);
    $held = $stmt_held->fetchColumn();
    
    // Count attended
    $stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND subject_id = ? AND status = 'Present'");
    $stmt_pres->execute([$student_id, $subj_id]);
    $attended = $stmt_pres->fetchColumn();
    
    $absent = $held - $attended;
    $percent = $held > 0 ? round(($attended / $held) * 100, 1) : 100.0;
    
    $report_data[] = [
        'code' => 'SUBJ-' . $subj_id,
        'name' => $subj['name'],
        'held' => $held,
        'attended' => $attended,
        'absent' => $absent,
        'percent' => $percent
    ];
}

$page_title  = 'Download Report';
include '../includes/header.php';
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
        <?php include '../includes/student-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Report
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Reports</p>
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

                <!-- Filters -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-sliders" style="color: #f59e0b;"></i> Customize Report Criteria</h3>
                    </div>
                    <div class="p-3">
                        <form id="reportFilterForm" class="row g-3">
                            <div class="col-md-4">
                                <label for="reportSem" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Target Class</label>
                                <input type="text" class="form-control text-light" value="<?php echo htmlspecialchars($student_class); ?>" disabled style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08);">
                            </div>
                            <div class="col-md-4">
                                <label for="reportSubject" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Subject</label>
                                <select class="form-select" id="reportSubject" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;">
                                    <option value="all">All Subjects</option>
                                    <?php foreach ($subjects as $subj): ?>
                                        <option value="<?php echo $subj['id']; ?>"><?php echo htmlspecialchars($subj['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="reportDateStart" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Academic Session</label>
                                <input type="text" class="form-control text-light" value="2025 - 2026" disabled style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08);">
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" id="btnSearchReport" class="btn btn-premium px-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;"><i class="bi bi-search me-1"></i> Generate Preview</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Table Container -->
                <div class="faculty-card mb-4" id="reportResultCard">
                    <div class="faculty-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-file-earmark-spreadsheet" style="color: #f59e0b;"></i> Report Preview Summary</h3>
                            <p class="faculty-card-subtitle">Generated preview for <?php echo htmlspecialchars($student_name); ?> (<?php echo htmlspecialchars($student_prn); ?>)</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-danger px-3 py-2" id="btnDownloadPDF" style="font-size: 0.8rem;"><i class="bi bi-file-pdf-fill me-1"></i> Export PDF</button>
                            <button class="btn btn-sm btn-outline-success px-3 py-2" id="btnDownloadExcel" style="font-size: 0.8rem;"><i class="bi bi-file-excel-fill me-1"></i> Export Excel</button>
                        </div>
                    </div>

                    <div class="faculty-table-responsive">
                        <table class="faculty-table" id="previewTable">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Conducted</th>
                                    <th>Attended</th>
                                    <th>Absent</th>
                                    <th>Current %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($report_data)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-4">No curriculum subjects allocated yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($report_data as $row): 
                                        $badge_class = ($row['percent'] >= 75) ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                    ?>
                                    <tr data-subject-id="<?php echo str_replace('SUBJ-', '', $row['code']); ?>">
                                        <td><strong><?php echo htmlspecialchars($row['code']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo $row['held']; ?></td>
                                        <td><?php echo $row['attended']; ?></td>
                                        <td><?php echo $row['absent']; ?></td>
                                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo $row['percent']; ?>%</span></td>
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
    const btnSearch = document.getElementById('btnSearchReport');
    const pdfBtn = document.getElementById('btnDownloadPDF');
    const excelBtn = document.getElementById('btnDownloadExcel');
    const subjectSelect = document.getElementById('reportSubject');

    btnSearch.addEventListener('click', function() {
        btnSearch.disabled = true;
        btnSearch.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Updating...`;
        
        setTimeout(() => {
            btnSearch.disabled = false;
            btnSearch.innerHTML = `<i class="bi bi-search me-1"></i> Generate Preview`;
            
            const selectedVal = subjectSelect.value;
            const rows = document.querySelectorAll('#previewTable tbody tr');
            rows.forEach(row => {
                const subId = row.getAttribute('data-subject-id');
                if (selectedVal === 'all' || subId === selectedVal) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            alert('Report preview refreshed successfully.');
        }, 400);
    });

    pdfBtn.addEventListener('click', function() {
        // Create an elegant, print-ready document structure in memory
        const reportContainer = document.createElement('div');
        reportContainer.style.padding = '40px';
        reportContainer.style.fontFamily = 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif';
        reportContainer.style.color = '#1e293b';
        reportContainer.style.backgroundColor = '#ffffff';

        // Add Header & Layout
        reportContainer.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                <div>
                    <h1 style="margin: 0; font-size: 24px; color: #0f172a; font-weight: 700; letter-spacing: -0.5px;">AttendEase</h1>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Student Attendance Report</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 12px; color: #64748b;">Academic Year</p>
                    <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 600; color: #0f172a;">2025 - 2026</p>
                </div>
            </div>

            <!-- Student Info Card -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Student Name</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($student_name); ?>}</p>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">ZPRN (Roll Number)</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($student_prn); ?>}</p>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Class & Division</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${<?php echo json_encode($student_class); ?>} - Div ${<?php echo json_encode($student_division); ?>}</p>
                </div>
                <div>
                    <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Report Generated On</p>
                    <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;">${new Date().toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' })}</p>
                </div>
            </div>

            <!-- Attendance Table -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
                <thead>
                    <tr style="background-color: #0f172a; color: #ffffff;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Subject Code</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600;">Subject Name</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600;">Conducted</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600;">Attended</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 600;">Absent</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    ${Array.from(document.querySelectorAll('#previewTable tbody tr')).map(row => {
                        if (row.style.display === 'none') return '';
                        const cells = row.querySelectorAll('td');
                        if (cells.length < 6) return '';
                        
                        const code = cells[0].innerText;
                        const name = cells[1].innerText;
                        const conducted = cells[2].innerText;
                        const attended = cells[3].innerText;
                        const absent = cells[4].innerText;
                        const pctText = cells[5].innerText;
                        const pctVal = parseFloat(pctText);
                        
                        const color = pctVal >= 75 ? '#16a34a' : '#dc2626';
                        const bgColor = pctVal >= 75 ? '#f0fdf4' : '#fef2f2';
                        
                        return `
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 14px 16px; font-size: 13px; font-weight: 600; color: #0f172a;">${code}</td>
                                <td style="padding: 14px 16px; font-size: 13px; color: #334155;">${name}</td>
                                <td style="padding: 14px 16px; font-size: 13px; text-align: center; color: #334155;">${conducted}</td>
                                <td style="padding: 14px 16px; font-size: 13px; text-align: center; color: #334155;">${attended}</td>
                                <td style="padding: 14px 16px; font-size: 13px; text-align: center; color: #334155;">${absent}</td>
                                <td style="padding: 14px 16px; font-size: 13px; text-align: right; font-weight: 700; color: ${color};">
                                    <span style="background-color: ${bgColor}; padding: 4px 8px; border-radius: 4px;">${pctText}</span>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>

            <!-- Footer Disclaimer -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; text-align: center; font-size: 11px; color: #94a3b8;">
                <p style="margin: 0;">This is an official system-generated attendance statement from AttendEase Portal.</p>
                <p style="margin: 4px 0 0 0;">Requires no physical signature. Keep for your academic records.</p>
            </div>
        `;

        const opt = {
            margin:       10,
            filename:     `Attendance_Report_${<?php echo json_encode($student_prn); ?>}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Run pdf generation
        html2pdf().set(opt).from(reportContainer).save();
    });

    excelBtn.addEventListener('click', function() {
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Student Name," + <?php echo json_encode($student_name); ?> + "\r\n";
        csvContent += "ZPRN," + <?php echo json_encode($student_prn); ?> + "\r\n";
        csvContent += "Class," + <?php echo json_encode($student_class); ?> + "\r\n";
        csvContent += "Division," + <?php echo json_encode($student_division); ?> + "\r\n\r\n";
        
        csvContent += "Subject Code,Subject Name,Conducted,Attended,Absent,Percentage\r\n";
        
        Array.from(document.querySelectorAll('#previewTable tbody tr')).forEach(row => {
            if (row.style.display === 'none') return;
            const cells = row.querySelectorAll('td');
            if (cells.length < 6) return;
            
            const code = `"${cells[0].innerText.replace(/"/g, '""')}"`;
            const name = `"${cells[1].innerText.replace(/"/g, '""')}"`;
            const conducted = cells[2].innerText;
            const attended = cells[3].innerText;
            const absent = cells[4].innerText;
            const percent = cells[5].innerText.replace('%', '');
            
            csvContent += `${code},${name},${conducted},${attended},${absent},${percent}\r\n`;
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `Attendance_Report_${<?php echo json_encode($student_prn); ?>}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
