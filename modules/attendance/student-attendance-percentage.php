<?php
/**
 * AttendEase - Student Attendance Percentage Analysis
 */
$page_title  = 'Attendance Percentage';
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
                            <i class="bi bi-percent"></i> Attendance Percentage
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Percentage Analysis</p>
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

                <!-- Main Analysis Panel -->
                <div class="row g-4 mb-4">
                    <!-- Left: Circular gauge indicator / Stat summary -->
                    <div class="col-lg-5">
                        <div class="faculty-card h-100 mb-0 text-center d-flex flex-column justify-content-center p-4">
                            <h3 class="faculty-card-title mb-4" style="text-align: left;"><i class="bi bi-pie-chart-fill" style="color: #f59e0b;"></i> Overall Standing</h3>
                            
                            <!-- Circular Graphic simulation -->
                            <div class="position-relative mx-auto my-3" style="width: 180px; height: 180px;">
                                <svg class="w-100 h-100" viewBox="0 0 36 36">
                                    <path class="text-secondary" style="opacity: 0.1; fill: none; stroke: currentColor; stroke-width: 3.5;"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-warning" style="fill: none; stroke: #f59e0b; stroke-width: 3.5; stroke-dasharray: 82.5, 100; stroke-linecap: round; transition: stroke-dasharray 1s ease 0s;"
                                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="fs-2 fw-bold text-white">82.5%</span>
                                    <div class="text-muted" style="font-size: 0.75rem;">Attendance</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-shield-fill-check me-1"></i> Above Minimum (75%)</span>
                            </div>
                            <p class="text-light-subtitle mt-3 mb-0" style="font-size: 0.85rem;">You need to attend 12 more lectures consecutively to reach 85% attendance standing.</p>
                        </div>
                    </div>

                    <!-- Right: Monthly progress overview -->
                    <div class="col-lg-7">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-graph-up" style="color: #f59e0b;"></i> Monthly Log Trends</h3>
                                    <p class="faculty-card-subtitle">Active semester timeline overview</p>
                                </div>
                            </div>
                            <div class="p-3">
                                <!-- June -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color:#cbd5e1;">
                                        <span class="fw-semibold">June 2026</span>
                                        <span><strong>90.0%</strong> (45 / 50 classes)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.06); border-radius: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!-- July -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color:#cbd5e1;">
                                        <span class="fw-semibold">July 2026</span>
                                        <span><strong>85.0%</strong> (51 / 60 classes)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.06); border-radius: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!-- August -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color:#cbd5e1;">
                                        <span class="fw-semibold">August 2026</span>
                                        <span><strong>78.0%</strong> (39 / 50 classes)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.06); border-radius: 5px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!-- September -->
                                <div class="mb-0">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem; color:#cbd5e1;">
                                        <span class="fw-semibold">September 2026</span>
                                        <span><strong>75.0%</strong> (30 / 40 classes)</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.06); border-radius: 5px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Criteria and Rules -->
                <div class="faculty-card">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-info-circle" style="color: #f59e0b;"></i> Attendance Requirements &amp; Policies</h3>
                    </div>
                    <div class="p-3">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 1.1rem;"></i>
                                <div style="color: #cbd5e1;">
                                    <strong>75% Mandatory Attendance:</strong> According to college policies, students must have a minimum of 75% attendance overall to sit for final exams.
                                </div>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 1.1rem;"></i>
                                <div style="color: #cbd5e1;">
                                    <strong>Condonation Criteria:</strong> Medical issues or representation in college sports/events must be supported with proper documentation within 7 working days.
                                </div>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="bi bi-bell-fill text-danger" style="font-size: 1.1rem;"></i>
                                <div style="color: #cbd5e1;">
                                    <strong>Automated Alerts:</strong> The system sends warnings to your dashboard and registered email address once your attendance drops below 75% in any subject.
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
