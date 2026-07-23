<?php
/**
 * AttendEase - Super Admin Condonation Requests Console
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

$success_msg = '';
$error_msg = '';

// Handle Actions (Approve / Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $cond_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $action = $_POST['action'];

    if ($cond_id > 0) {
        try {
            if ($action === 'approve') {
                // Update status to Approved
                $stmt = $pdo->prepare("UPDATE condonations SET status = 'Approved' WHERE id = ?");
                $stmt->execute([$cond_id]);

                // Fetch details to update attendance
                $stmt_details = $pdo->prepare("SELECT student_id, date FROM condonations WHERE id = ?");
                $stmt_details->execute([$cond_id]);
                $cond = $stmt_details->fetch();

                if ($cond) {
                    // Update student's attendance records on that date from Absent to Present
                    $stmt_att = $pdo->prepare("UPDATE attendance SET status = 'Present' WHERE student_id = ? AND date = ? AND status = 'Absent'");
                    $stmt_att->execute([$cond['student_id'], $cond['date']]);
                }
                $success_msg = 'Condonation application approved successfully, and corresponding absent attendance logs have been updated to Present!';
            } elseif ($action === 'reject') {
                // Update status to Rejected
                $stmt = $pdo->prepare("UPDATE condonations SET status = 'Rejected' WHERE id = ?");
                $stmt->execute([$cond_id]);
                $success_msg = 'Condonation application rejected.';
            }
        } catch (PDOException $e) {
            $error_msg = 'Operation failed: ' . $e->getMessage();
        }
    }
}

// Load Condonations
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : 'All';
$sql = "
    SELECT c.*, u.name AS student_name, u.zprn AS student_zprn, u.class AS student_class, u.division AS student_division
    FROM condonations c
    JOIN users u ON c.student_id = u.id
";
$params = [];
if ($filter_status !== 'All') {
    $sql .= " WHERE c.status = ?";
    $params[] = $filter_status;
}
$sql .= " ORDER BY c.submitted_at DESC";

$stmt_load = $pdo->prepare($sql);
$stmt_load->execute($params);
$condonations_list = $stmt_load->fetchAll();

$page_title       = 'Condonation Requests';
$page_breadcrumb  = 'AttendEase / Super Admin / Condonations';
$page_icon        = 'bi-file-earmark-check-fill';

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
        <?php include '../../includes/admin-sidebar.php'; ?>

        <!-- Main content -->
        <div class="faculty-main-content">

            <!-- Page Title Band -->
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-file-earmark-check-fill"></i> Condonation Requests
                        </h1>
                        <p class="faculty-page-band-breadcrumb"><?php echo $page_breadcrumb; ?></p>
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

                <!-- Alert Feedback -->
                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" style="background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.2); color: #34d399;">
                        <i class="bi bi-check-circle-fill"></i>
                        <div><?php echo htmlspecialchars($success_msg); ?></div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: #f87171;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div><?php echo htmlspecialchars($error_msg); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Filters Control -->
                <div class="faculty-card mb-4">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex gap-2">
                                <a href="?status=All" class="btn btn-sm <?php echo $filter_status === 'All' ? 'btn-primary' : 'btn-outline-light'; ?> rounded-pill px-3">All Requests</a>
                                <a href="?status=Pending" class="btn btn-sm <?php echo $filter_status === 'Pending' ? 'btn-primary' : 'btn-outline-light'; ?> rounded-pill px-3">Pending</a>
                                <a href="?status=Approved" class="btn btn-sm <?php echo $filter_status === 'Approved' ? 'btn-primary' : 'btn-outline-light'; ?> rounded-pill px-3">Approved</a>
                                <a href="?status=Rejected" class="btn btn-sm <?php echo $filter_status === 'Rejected' ? 'btn-primary' : 'btn-outline-light'; ?> rounded-pill px-3">Rejected</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Condonations List Table -->
                <div class="faculty-card">
                    <div class="faculty-card-header">
                        <h3 class="faculty-card-title"><i class="bi bi-table text-warning me-2"></i>Condonation Applications Feed</h3>
                        <p class="faculty-card-subtitle">Review student leave requests and condone attendance logs.</p>
                    </div>
                    <div class="faculty-table-responsive">
                        <table class="faculty-table">
                            <thead>
                                <tr>
                                    <th>Student Details</th>
                                    <th>Leave Type</th>
                                    <th>Absence Date</th>
                                    <th>Reason / Proof</th>
                                    <th>Submitted At</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($condonations_list)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-secondary py-4">No condonation requests found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($condonations_list as $cond): 
                                        $status = $cond['status'];
                                        $badgeClass = ($status === 'Approved') ? 'badge-success-subtle' : (($status === 'Rejected') ? 'badge-danger-subtle' : 'badge-warning-subtle');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-white"><?php echo htmlspecialchars($cond['student_name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($cond['student_zprn']); ?> • <?php echo htmlspecialchars($cond['student_class']); ?> Div <?php echo htmlspecialchars($cond['student_division']); ?></small>
                                        </td>
                                        <td><span class="faculty-badge badge-blue-subtle"><?php echo htmlspecialchars($cond['type']); ?></span></td>
                                        <td><span class="fw-semibold text-white"><?php echo htmlspecialchars($cond['date']); ?></span></td>
                                        <td>
                                            <div style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="<?php echo htmlspecialchars($cond['reason']); ?>"><?php echo htmlspecialchars($cond['reason']); ?></div>
                                            <?php if (!empty($cond['document'])): ?>
                                                <small class="d-block text-info mt-1"><i class="bi bi-file-earmark-pdf me-1"></i><?php echo htmlspecialchars($cond['document']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><small style="color: #94a3b8;"><?php echo htmlspecialchars($cond['submitted_at']); ?></small></td>
                                        <td><span class="faculty-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                        <td class="text-end">
                                            <?php if ($status === 'Pending'): ?>
                                                <form method="POST" class="d-inline-block">
                                                    <input type="hidden" name="id" value="<?php echo $cond['id']; ?>">
                                                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success px-3 me-1" title="Approve Request">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger px-3" title="Reject Request">
                                                        <i class="bi bi-x-lg"></i> Reject
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">Reviewed</span>
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

<?php include '../../includes/footer.php'; ?>
