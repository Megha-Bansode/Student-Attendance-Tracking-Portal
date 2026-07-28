<?php
$page_title       = 'Password Resets';
$page_breadcrumb  = 'AttendEase / Super Admin / Password Resets';
$page_icon        = 'bi-key-fill';

require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../modules/authentication/login.php");
    exit;
}

$message = '';
$generated_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve') {
    $request_id = $_POST['request_id'];
    $user_id = $_POST['user_id'];
    
    // Generate a new random password
    $generated_password = 'Pass@' . rand(1000, 9999);
    
    // In a real app, hash this password before storing it. Our db seems to use plain text for seeded users, but let's assume it's directly updated.
    $stmtUpdateUser = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmtUpdateUser->execute([$generated_password, $user_id]);
    
    // Update request status
    $stmtUpdateRequest = $pdo->prepare("UPDATE password_resets SET status = 'Approved', resolved_at = ? WHERE id = ?");
    $stmtUpdateRequest->execute([date('Y-m-d H:i:s'), $request_id]);
    
    $message = "Password request approved. New password generated.";
}

// Fetch pending requests
$stmt = $pdo->query("SELECT pr.id, pr.user_id, pr.role, pr.created_at, u.name, u.username 
                     FROM password_resets pr 
                     JOIN users u ON pr.user_id = u.id 
                     WHERE pr.status = 'Pending' 
                     ORDER BY pr.created_at DESC");
$requests = $stmt->fetchAll();

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
        <?php include '../includes/admin-sidebar.php'; ?>
        
        <div class="faculty-main-content">
            <?php include '../includes/admin-topbar.php'; ?>
            
            <main class="faculty-content-body">
                <div class="faculty-card mb-4 animate-fade-up">
                    <h4 class="mb-3" style="color:#f1f5f9;">Pending Password Reset Requests</h4>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                <?php echo htmlspecialchars($message); ?>
                                <br>
                                <strong>Generated Password: </strong> <span class="badge bg-dark fs-6"><?php echo htmlspecialchars($generated_password); ?></span>
                                <br>
                                <small>Please communicate this password to the user.</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Date Requested</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($requests) > 0): ?>
                                    <?php foreach ($requests as $req): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(date('d M Y, h:i A', strtotime($req['created_at']))); ?></td>
                                            <td><?php echo htmlspecialchars($req['name']); ?></td>
                                            <td><?php echo htmlspecialchars($req['username']); ?></td>
                                            <td><span class="badge bg-info text-dark"><?php echo ucfirst(htmlspecialchars($req['role'])); ?></span></td>
                                            <td>
                                                <form action="admin-password-resets.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="approve">
                                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $req['user_id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-success">Approve & Reset</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No pending reset requests.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
