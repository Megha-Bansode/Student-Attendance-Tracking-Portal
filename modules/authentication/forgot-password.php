<?php
require_once '../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_GET['role']) ? $_GET['role'] : 'student';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_role = $_POST['role'];
    
    if ($post_role === 'student' || $post_role === 'faculty') {
        $username = trim($_POST['username']);
        
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE role = ? AND (username = ? OR zprn = ?)");
        $stmt->execute([$post_role, $username, $username]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Check for existing pending request
            $stmtCheck = $pdo->prepare("SELECT id FROM password_resets WHERE user_id = ? AND status = 'Pending'");
            $stmtCheck->execute([$user['id']]);
            if ($stmtCheck->fetch()) {
                $error = "A password reset request is already pending for this account.";
            } else {
                $stmtInsert = $pdo->prepare("INSERT INTO password_resets (user_id, role, created_at) VALUES (?, ?, ?)");
                $stmtInsert->execute([$user['id'], $post_role, date('Y-m-d H:i:s')]);
                $message = "Your password reset request has been submitted to the admin. Please contact the administrator for your new password.";
            }
        } else {
            $error = "User not found.";
        }
    } elseif ($post_role === 'admin') {
        $email = trim($_POST['email']);
        $new_password = $_POST['new_password'];
        $reset_key = trim($_POST['reset_key']);
        
        if ($reset_key === ADMIN_RESET_KEY) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE role = 'admin' AND username = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Update password immediately for admin
                $stmtUpdate = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmtUpdate->execute([$new_password, $user['id']]); // In a real app, hash this!
                $message = "Admin password has been reset successfully. You can now login.";
            } else {
                $error = "Admin user not found.";
            }
        } else {
            $error = "Invalid Reset Key.";
        }
    }
}

include '../../includes/header.php';
?>

<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/forms.css">

<section class="split-login-section">
    <canvas id="loginInteractiveBgCanvas"></canvas>

    <div class="split-login-card" style="max-width: 500px; margin: auto; display: block;">
        <div class="split-right-panel" style="width: 100%;">
            
            <div class="text-center mb-4">
                <h2 class="form-header-title">Forgot Password</h2>
                <p class="form-header-subtitle">Reset your account access.</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($role === 'student' || $role === 'faculty'): ?>
                <form action="forgot-password.php?role=<?php echo $role; ?>" method="POST" class="split-login-form">
                    <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
                    
                    <div class="compact-form-group">
                        <label for="username" class="compact-label"><?php echo $role === 'student' ? 'ZPRN (Roll Number)' : 'Faculty ID / Email'; ?></label>
                        <div class="input-with-icon">
                            <i class="bi bi-person-fill input-icon-left"></i>
                            <input type="text" name="username" class="custom-compact-input" id="username" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-split-submit mt-3">
                        Submit Request <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            <?php elseif ($role === 'admin'): ?>
                <form action="forgot-password.php?role=admin" method="POST" class="split-login-form">
                    <input type="hidden" name="role" value="admin">
                    
                    <div class="compact-form-group">
                        <label for="email" class="compact-label">Admin Email</label>
                        <div class="input-with-icon">
                            <i class="bi bi-envelope-fill input-icon-left"></i>
                            <input type="text" name="email" class="custom-compact-input" id="email" required>
                        </div>
                    </div>
                    
                    <div class="compact-form-group">
                        <label for="new_password" class="compact-label">New Password</label>
                        <div class="input-with-icon">
                            <i class="bi bi-lock-fill input-icon-left"></i>
                            <input type="password" name="new_password" class="custom-compact-input" id="new_password" required>
                        </div>
                    </div>
                    
                    <div class="compact-form-group">
                        <label for="reset_key" class="compact-label">Specialized Admin Key</label>
                        <div class="input-with-icon">
                            <i class="bi bi-key-fill input-icon-left"></i>
                            <input type="password" name="reset_key" class="custom-compact-input" id="reset_key" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-split-submit mt-3">
                        Reset Password <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            <?php endif; ?>

            <div class="split-form-footer mt-4 text-center">
                <a href="login.php" id="login-back-home">Back to Login</a>
            </div>
        </div>
    </div>
</section>

<?php 
// include '../../includes/footer.php'; // Skipping footer to avoid missing file errors
?>
