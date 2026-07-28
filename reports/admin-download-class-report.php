<?php
/**
 * AttendEase - Super Admin Class-wise Report Exporter
 */
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../modules/authentication/login.php");
    exit;
}

$class = isset($_GET['class']) ? trim($_GET['class']) : 'First Year';
$division = isset($_GET['division']) ? trim($_GET['division']) : 'all';
$format = isset($_GET['format']) ? trim($_GET['format']) : 'pdf';

// Query Students
$sql = "SELECT id, name, zprn, division, department FROM users WHERE role = 'student' AND class = ?";
$params = [$class];
if ($division !== 'all') {
    $sql .= " AND division = ?";
    $params[] = $division;
}
$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

$report_data = [];
foreach ($students as $student) {
    $s_id = $student['id'];
    
    // Count total lectures held for this student
    $stmt_held = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ?");
    $stmt_held->execute([$s_id]);
    $held = $stmt_held->fetchColumn();
    
    // Count attended
    $stmt_pres = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE student_id = ? AND status = 'Present'");
    $stmt_pres->execute([$s_id]);
    $attended = $stmt_pres->fetchColumn();
    
    $absent = $held - $attended;
    $percent = $held > 0 ? round(($attended / $held) * 100, 1) : 100.0;
    
    $report_data[] = [
        'zprn' => $student['zprn'],
        'name' => $student['name'],
        'division' => $student['division'],
        'department' => $student['department'] ?? 'Unassigned',
        'held' => $held,
        'attended' => $attended,
        'absent' => $absent,
        'percent' => $percent
    ];
}

// Format 1: CSV Export
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Class_Attendance_' . str_replace(' ', '_', $class) . '_Div_' . $division . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Title Blocks
    fputcsv($output, ["AttendEase - Super Admin Attendance Report"]);
    fputcsv($output, ["Class", $class]);
    fputcsv($output, ["Division", $division === 'all' ? 'All Divisions' : $division]);
    fputcsv($output, ["Exported On", date('Y-m-d H:i:s')]);
    fputcsv($output, []);
    
    // Table Headers
    fputcsv($output, ["Roll / PRN", "Student Name", "Department", "Division", "Lectures Conducted", "Lectures Attended", "Absent", "Attendance Percentage"]);
    
    foreach ($report_data as $row) {
        fputcsv($output, [
            $row['zprn'],
            $row['name'],
            $row['department'],
            $row['division'],
            $row['held'],
            $row['attended'],
            $row['absent'],
            $row['percent'] . '%'
        ]);
    }
    
    fclose($output);
    exit;
}

// Format 2: PDF Export (using html2pdf.js)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exporting Attendance Report...</title>
    <!-- Include html2pdf.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .generating-msg {
            max-width: 400px;
            margin: 100px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            text-align: center;
        }
        .spinner {
            border: 4px solid rgba(0,0,0,0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #3b82f6;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div class="generating-msg" id="loadingDiv">
        <div class="spinner"></div>
        <h3 style="margin:0 0 8px 0; color:#0f172a;">Generating Report</h3>
        <p style="margin:0; font-size:14px; color:#64748b;">Please wait while we compile the PDF file...</p>
    </div>

    <!-- The actual print template (Hidden from display) -->
    <div id="printTemplate" style="background:#ffffff; padding:40px; color:#1e293b; display:none;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
            <div>
                <h1 style="margin: 0; font-size: 26px; color: #0f172a; font-weight: 700; letter-spacing: -0.5px;">AttendEase</h1>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Class-wise Attendance Audit</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0; font-size: 12px; color: #64748b;">Report Scope</p>
                <p style="margin: 2px 0 0 0; font-size: 14px; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($class); ?> (Div: <?php echo htmlspecialchars($division === 'all' ? 'All' : $division); ?>)</p>
            </div>
        </div>

        <!-- Info Table Cards -->
        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 30px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div>
                <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Total Students</p>
                <p style="margin: 4px 0 0 0; font-size: 16px; font-weight: 700; color: #0f172a;"><?php echo count($report_data); ?></p>
            </div>
            <div>
                <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Academic Year</p>
                <p style="margin: 4px 0 0 0; font-size: 16px; font-weight: 700; color: #0f172a;">2025 - 2026</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">Exported On</p>
                <p style="margin: 4px 0 0 0; font-size: 15px; font-weight: 600; color: #0f172a;"><?php echo date('M d, Y h:i A'); ?></p>
            </div>
        </div>

        <!-- Report Table -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
            <thead>
                <tr style="background-color: #0f172a; color: #ffffff;">
                    <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Roll / PRN</th>
                    <th style="padding: 10px 12px; text-align: left; font-size: 12px; font-weight: 600;">Student Name</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600;">Dept</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600;">Div</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600;">Conducted</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600;">Attended</th>
                    <th style="padding: 10px 12px; text-align: center; font-size: 12px; font-weight: 600;">Absent</th>
                    <th style="padding: 10px 12px; text-align: right; font-size: 12px; font-weight: 600; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report_data)): ?>
                    <tr>
                        <td colspan="8" style="padding: 20px; text-align: center; color: #94a3b8; font-size: 14px;">No student records match this filter.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report_data as $row): 
                        $color = $row['percent'] >= 75 ? '#16a34a' : '#dc2626';
                        $bgColor = $row['percent'] >= 75 ? '#f0fdf4' : '#fef2f2';
                    ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 10px 12px; font-size: 12px; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($row['zprn']); ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; color: #334155;"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: center; color: #64748b;"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: center; color: #334155;"><?php echo htmlspecialchars($row['division']); ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: center; color: #334155;"><?php echo $row['held']; ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: center; color: #334155;"><?php echo $row['attended']; ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: center; color: #334155;"><?php echo $row['absent']; ?></td>
                            <td style="padding: 10px 12px; font-size: 12px; text-align: right; font-weight: 700; color: <?php echo $color; ?>;">
                                <span style="background-color: <?php echo $bgColor; ?>; padding: 3px 6px; border-radius: 4px;"><?php echo $row['percent']; ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; text-align: center; font-size: 10px; color: #94a3b8;">
            <p style="margin: 0;">Super Admin generated institutional statement from AttendEase Student Attendance Portal.</p>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const element = document.getElementById('printTemplate');
        element.style.display = 'block'; // Make it visible briefly for capture
        
        const opt = {
            margin:       10,
            filename:     'Class_Attendance_<?php echo str_replace(' ', '_', $class); ?>_Div_<?php echo $division; ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['css', 'legacy'] }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            // Once saved, hide it back and redirect to admin-reports.php
            element.style.display = 'none';
            document.getElementById('loadingDiv').innerHTML = `
                <i class="bi bi-check-circle" style="color:#10b981; font-size:36px; margin-bottom:15px; display:block;"></i>
                <h3 style="margin:0 0 8px 0; color:#0f172a;">PDF Exported Successfully!</h3>
                <p style="margin:0 0 15px 0; font-size:14px; color:#64748b;">Your download should have started.</p>
                <a href="admin-reports.php" style="color:#3b82f6; text-decoration:none; font-weight:600; font-size:14px;">Go Back</a>
            `;
            setTimeout(() => {
                window.location.href = 'admin-reports.php';
            }, 1000);
        }).catch(err => {
            console.error(err);
            alert("Error exporting PDF");
            window.location.href = 'admin-reports.php';
        });
    });
    </script>
</body>
</html>
