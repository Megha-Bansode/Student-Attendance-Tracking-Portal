<?php
/**
 * AttendEase - Student Profile
 */
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Access Control
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../authentication/login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$student_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$student_zprn = isset($_SESSION['zprn']) ? $_SESSION['zprn'] : '';
$student_class = isset($_SESSION['class']) ? $_SESSION['class'] : '';
$student_division = isset($_SESSION['division']) ? $_SESSION['division'] : '';

if ($_SESSION['role'] !== 'student') {
    // Fetch first student in database to populate dashboard for preview
    $stmt_s = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
    $first_student = $stmt_s->fetch();
    if ($first_student) {
        $student_id = $first_student['id'];
        $student_name = $first_student['name'];
        $student_zprn = $first_student['zprn'];
        $student_class = $first_student['class'];
        $student_division = $first_student['division'];
    }
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_password = $_POST['password'];
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($new_password)) {
        $error = "Password cannot be empty.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password, $student_id]);
        $message = "Password updated successfully!";
    }
}

// Fetch current user details
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$student_id]);
$user_info = $stmt_user->fetch();

$page_title  = 'My Profile';
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
        <?php include '../../includes/student-sidebar.php'; ?>

        <div class="faculty-main-content">
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-person-fill"></i> My Profile
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Student / Profile</p>
                    </div>
                </div>
            </div>

            <main class="faculty-content-body">
                <div class="row g-4">
                    <!-- Profile Information Card -->
                    <div class="col-lg-7">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-person-lines-fill" style="color:#f59e0b;"></i> Personal Information</h3>
                            </div>
                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</label>
                                        <div class="fw-semibold text-white fs-6"><?php echo htmlspecialchars($user_info['name']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">ZPRN / Registration</label>
                                        <div class="fw-semibold text-white fs-6"><?php echo htmlspecialchars($user_info['zprn'] ?? $user_info['username']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Department</label>
                                        <div class="fw-semibold text-white fs-6"><?php echo htmlspecialchars($user_info['department'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Class & Division</label>
                                        <div class="fw-semibold text-white fs-6"><?php echo htmlspecialchars($user_info['class'] ?? 'N/A'); ?> - Div <?php echo htmlspecialchars($user_info['division'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Account Role</label>
                                        <div class="fw-semibold text-white fs-6 text-capitalize"><?php echo htmlspecialchars($user_info['role']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="faculty-form-label text-muted mb-1" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Status</label>
                                        <div><span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1"><?php echo htmlspecialchars($user_info['status'] ?? 'Active'); ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Password Card -->
                    <div class="col-lg-5">
                        <div class="faculty-card h-100 mb-0">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-shield-lock-fill" style="color:#f59e0b;"></i> Security Settings</h3>
                            </div>
                            
                            <div class="p-4">
                                <?php if ($message): ?>
                                    <div class="alert alert-success bg-success-subtle text-success border border-success-subtle py-2 mb-3"><i class="bi bi-check-circle-fill me-2"></i><?php echo $message; ?></div>
                                <?php endif; ?>
                                <?php if ($error): ?>
                                    <div class="alert alert-danger bg-danger-subtle text-danger border border-danger-subtle py-2 mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?></div>
                                <?php endif; ?>

                                <form action="student-profile.php" method="POST">
                                    <input type="hidden" name="update_profile" value="1">

                                    <div class="mb-3">
                                        <label class="faculty-form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="newPassword" class="form-control faculty-input" placeholder="Enter new password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('newPassword', this)" style="border-color: rgba(255,255,255,0.1); background: rgba(15,23,42,0.4); color: #94a3b8;"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="faculty-form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control faculty-input" placeholder="Confirm new password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword', this)" style="border-color: rgba(255,255,255,0.1); background: rgba(15,23,42,0.4); color: #94a3b8;"><i class="bi bi-eye"></i></button>
                                        </div>
                                        <div class="form-text" style="color: #64748b; font-size: 0.8rem; margin-top: 0.5rem;">Choose a strong password to secure your account. Only you can change your password.</div>
                                    </div>

                                    <button type="submit" class="btn btn-premium w-100"><i class="bi bi-key-fill me-1"></i> Update Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
