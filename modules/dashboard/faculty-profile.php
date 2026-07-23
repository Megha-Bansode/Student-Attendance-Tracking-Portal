<?php
/**
 * AttendEase - Faculty Profile
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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['password'];

    if (empty($new_username)) {
        $error = "Username cannot be empty.";
    } else {
        // Check if username already exists for another user
        $stmt_check = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt_check->execute([$new_username, $faculty_id]);
        if ($stmt_check->fetch()) {
            $error = "Username is already taken by another user.";
        } else {
            if (!empty($new_password)) {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                $stmt->execute([$new_username, $new_password, $faculty_id]);
                $message = "Username and password updated successfully!";
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->execute([$new_username, $faculty_id]);
                $message = "Username updated successfully!";
            }
            $_SESSION['username'] = $new_username;
        }
    }
}

// Fetch current user details
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$faculty_id]);
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
        <?php include '../../includes/faculty-sidebar.php'; ?>

        <div class="faculty-main-content">
            <div class="faculty-page-band">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="faculty-page-band-title">
                            <i class="bi bi-person-fill"></i> My Profile
                        </h1>
                        <p class="faculty-page-band-breadcrumb">AttendEase / Faculty / Profile</p>
                    </div>
                </div>
            </div>

            <main class="faculty-content-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="faculty-card">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-gear-fill" style="color:#60a5fa;"></i> Update Credentials</h3>
                            </div>
                            
                            <div class="p-3">
                                <?php if ($message): ?>
                                    <div class="alert alert-success bg-success text-white border-0 py-2"><?php echo $message; ?></div>
                                <?php endif; ?>
                                <?php if ($error): ?>
                                    <div class="alert alert-danger bg-danger text-white border-0 py-2"><?php echo $error; ?></div>
                                <?php endif; ?>

                                <form action="faculty-profile.php" method="POST">
                                    <input type="hidden" name="update_profile" value="1">
                                    
                                    <div class="mb-3">
                                        <label class="faculty-form-label">Full Name</label>
                                        <input type="text" class="faculty-input" value="<?php echo htmlspecialchars($user_info['name']); ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="faculty-form-label">Username</label>
                                        <input type="text" name="username" class="faculty-input" value="<?php echo htmlspecialchars($user_info['username']); ?>" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="faculty-form-label">New Password</label>
                                        <input type="password" name="password" class="faculty-input" placeholder="Leave blank to keep current password">
                                    </div>

                                    <button type="submit" class="btn btn-premium w-100"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../../includes/footer.php'; ?>
