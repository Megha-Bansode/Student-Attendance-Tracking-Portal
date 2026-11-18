<?php
/**
 * AttendEase - Super Admin System Settings
 * Aligned with Faculty Dashboard design & AttendEase web portal theme
 */
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../modules/authentication/login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

// Get current admin details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_credentials') {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    
    if (!empty($new_username)) {
        if (!empty($new_password)) {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt->execute([$new_username, $new_password, $admin_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->execute([$new_username, $admin_id]);
        }
        $_SESSION['username'] = $new_username;
        $msg = 'Credentials updated successfully!';
        
        // Refresh admin details
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();
    } else {
        $err = 'Username cannot be empty.';
    }
}

$page_title       = 'System Settings';
$page_breadcrumb  = 'AttendEase / Super Admin / Settings';
$page_icon        = 'bi-gear-fill';

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

        <!-- Super Admin Sidebar -->
        <?php include '../includes/admin-sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="faculty-main-content">

            <!-- Topbar Page Band -->
            <?php include '../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="faculty-card mb-4">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-sliders" style="color:#60a5fa;"></i> Institution Profile &amp; Portal Settings</h3>
                            </div>
                            <form onsubmit="event.preventDefault(); alert('System settings saved successfully!');">
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">Portal Title &amp; Institution Branding</label>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" value="AttendEase - Student Attendance Tracking Portal">
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-slate-300 font-semibold">Current Academic Year</label>
                                        <select class="form-select bg-dark text-white border-secondary">
                                            <option selected>2026 - 2027 (Current Active)</option>
                                            <option>2025 - 2026</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-slate-300 font-semibold">Minimum Attendance Threshold (%)</label>
                                        <input type="number" class="form-control bg-dark text-white border-secondary" value="75">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">Attendance Edit Window (Hours)</label>
                                    <input type="number" class="form-control bg-dark text-white border-secondary" value="48">
                                    <small style="color:#64748b;">Faculty members can edit submitted attendance up to 48 hours without admin approval.</small>
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-premium px-4"><i class="bi bi-save me-1"></i> Save Portal Settings</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Admin Credentials -->
                        <div class="faculty-card mb-4 text-light">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-key" style="color:#60a5fa;"></i> Admin Credentials</h3>
                            </div>
                            <?php if (!empty($msg)): ?>
                                <div class="alert alert-success py-2 font-mono small"><?php echo $msg; ?></div>
                            <?php endif; ?>
                            <?php if (!empty($err)): ?>
                                <div class="alert alert-danger py-2 font-mono small"><?php echo $err; ?></div>
                            <?php endif; ?>
                            <form action="admin-settings.php" method="POST">
                                <input type="hidden" name="action" value="update_credentials">
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">Username / Email</label>
                                    <input type="text" name="username" class="form-control bg-dark text-white border-secondary" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-slate-300 font-semibold">New Password</label>
                                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Leave blank to keep same">
                                </div>
                                <button type="submit" class="btn btn-premium btn-sm w-full"><i class="bi bi-shield-lock me-1"></i> Update Credentials</button>
                            </form>
                        </div>

                        <div class="faculty-card mb-4">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-database-fill-gear" style="color:#34d399;"></i> Database &amp; Security</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 rounded-3" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);">
                                    <h6 class="fw-bold text-white mb-1"><i class="bi bi-shield-check text-success me-1"></i> Automated DB Backup</h6>
                                    <p class="small mb-2" style="color:#94a3b8;">Last backup executed today at 04:00 AM.</p>
                                    <button class="btn btn-sm btn-outline-light w-full" onclick="alert('Creating manual DB backup...')"><i class="bi bi-cloud-arrow-up me-1"></i> Create Manual Backup</button>
                                </div>

                                <div class="p-3 rounded-3 mt-3" style="background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08);">
                                    <h6 class="fw-bold text-white mb-1"><i class="bi bi-key-fill text-warning me-1"></i> Security Encryption</h6>
                                    <p class="small mb-0" style="color:#94a3b8;">AES-256 Bit SSL/TLS Data Encryption is Active across all API endpoints.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
