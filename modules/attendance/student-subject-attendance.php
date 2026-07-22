<?php
/**
 * AttendEase - Student Subject-wise Attendance Breakdown
 */
$page_title  = 'Subject-wise Attendance';
include '../../includes/header.php';

// Mock list of subjects
$subjects_attendance = [
    [
        'code' => 'CS501',
        'name' => 'Data Structures & Algorithms',
        'instructor' => 'Prof. Rajesh Sharma',
        'attended' => 44,
        'conducted' => 50,
        'percentage' => 88.0
    ],
    [
        'code' => 'CS502',
        'name' => 'Database Management Systems',
        'instructor' => 'Prof. Rajesh Sharma',
        'attended' => 40,
        'conducted' => 50,
        'percentage' => 80.0
    ],
    [
        'code' => 'CS503',
        'name' => 'Web Technology Lab',
        'instructor' => 'Dr. Amit Patel',
        'attended' => 28,
        'conducted' => 40,
        'percentage' => 70.0
    ],
    [
        'code' => 'CS504',
        'name' => 'Object-Oriented Programming',
        'instructor' => 'Prof. Priya Rao',
        'attended' => 34,
        'conducted' => 40,
        'percentage' => 85.0
    ],
    [
        'code' => 'CS505',
        'name' => 'Software Engineering',
        'instructor' => 'Prof. Rajesh Sharma',
        'attended' => 19,
        'conducted' => 20,
        'percentage' => 95.0
    ],
];
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
                            <i class="bi bi-journal-text"></i> Subject-wise Attendance
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Academic Breakdown</p>
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

                <!-- Alert for critical attendance -->
                <div class="alert alert-warning border border-warning-subtle d-flex align-items-center gap-3 py-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 1.5rem;"></i>
                    <div>
                        <h4 class="alert-heading h6 fw-bold mb-1 text-warning">Low Attendance Warning</h4>
                        <p class="mb-0 text-light-subtitle" style="font-size: 0.85rem;">Your attendance in <strong class="text-warning">CS503: Web Technology Lab</strong> is currently at <strong class="text-warning">70.0%</strong>, which is below the minimum required threshold of 75%.</p>
                    </div>
                </div>

                <!-- Subject Grid / List -->
                <div class="faculty-card mb-4">
                    <div class="faculty-card-header">
                        <div>
                            <h3 class="faculty-card-title"><i class="bi bi-mortarboard" style="color:#f59e0b;"></i> Course Wise Analytics</h3>
                            <p class="faculty-card-subtitle">Overview of your semester attendance progress</p>
                        </div>
                    </div>
                    
                    <div class="p-3">
                        <div class="row g-4">
                            <?php foreach ($subjects_attendance as $sub): ?>
                                <?php 
                                    $is_low = $sub['percentage'] < 75;
                                    $progress_class = $is_low ? 'bg-danger' : ($sub['percentage'] >= 85 ? 'bg-success' : 'bg-warning');
                                    $border_class = $is_low ? 'border-danger-subtle' : 'border-light-subtle';
                                    $badge_class = $is_low ? 'badge-danger-subtle' : 'badge-success-subtle';
                                ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card p-3 h-100" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px;">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <span class="faculty-badge badge-blue-subtle mb-2"><?php echo $sub['code']; ?></span>
                                                <h4 class="h6 fw-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;"><?php echo $sub['name']; ?></h4>
                                                <small style="color: #94a3b8;"><i class="bi bi-person me-1"></i><?php echo $sub['instructor']; ?></small>
                                            </div>
                                            <span class="faculty-badge <?php echo $badge_class; ?>" style="font-size: 0.85rem; font-weight: 700;"><?php echo $sub['percentage']; ?>%</span>
                                        </div>

                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between mb-2" style="font-size: 0.8rem; color: #cbd5e1;">
                                                <span>Attended: <strong><?php echo $sub['attended']; ?></strong></span>
                                                <span>Total Classes: <strong><?php echo $sub['conducted']; ?></strong></span>
                                            </div>
                                            <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.06); border-radius: 4px;">
                                                <div class="progress-bar <?php echo $progress_class; ?>" role="progressbar" style="width: <?php echo $sub['percentage']; ?>%" aria-valuenow="<?php echo $sub['percentage']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <?php if ($is_low): ?>
                                                <div class="mt-2 text-danger" style="font-size: 0.75rem; font-weight: 500;">
                                                    <i class="bi bi-exclamation-circle me-1"></i> Attendance short by <?php echo (75 - $sub['percentage']); ?>%
                                                </div>
                                            <?php else: ?>
                                                <div class="mt-2 text-success" style="font-size: 0.75rem; font-weight: 500;">
                                                    <i class="bi bi-check-circle me-1"></i> Meets requirement
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
