<?php
/**
 * AttendEase - Super Admin Notifications Management Console
 * Allows super admins to create, publish, and delete student notifications
 */
$page_title       = 'Manage Notifications';
$page_breadcrumb  = 'AttendEase / Super Admin / Notifications';
$page_icon        = 'bi-bell-fill';

$file_path = __DIR__ . '/../config/notifications.json';
$notifications = [];
if (file_exists($file_path)) {
    $notifications = json_decode(file_get_contents($file_path), true) ?: [];
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $title = trim($_POST['title']);
        $message = trim($_POST['message']);
        $type = $_POST['type'];

        // Map types to icons, colors and glows
        $icons = [
            'alert' => 'bi-exclamation-triangle-fill',
            'info' => 'bi-info-circle-fill',
            'success' => 'bi-check-circle-fill',
            'warning' => 'bi-exclamation-circle-fill'
        ];
        $colors = [
            'alert' => '#ef4444',
            'info' => '#38bdf8',
            'success' => '#10b981',
            'warning' => '#f59e0b'
        ];
        $glows = [
            'alert' => 'rgba(239, 68, 68, 0.05)',
            'info' => 'rgba(56, 189, 248, 0.05)',
            'success' => 'rgba(16, 185, 129, 0.02)',
            'warning' => 'rgba(245, 158, 11, 0.05)'
        ];

        $new_notif = [
            'id' => empty($notifications) ? 1 : max(array_column($notifications, 'id')) + 1,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'unread' => true,
            'icon' => isset($icons[$type]) ? $icons[$type] : 'bi-info-circle-fill',
            'icon_color' => isset($colors[$type]) ? $colors[$type] : '#38bdf8',
            'bg_glow' => isset($glows[$type]) ? $glows[$type] : 'rgba(56, 189, 248, 0.05)'
        ];

        $notifications[] = $new_notif;
        file_put_contents($file_path, json_encode($notifications, JSON_PRETTY_PRINT));
        header('Location: admin-notifications.php?success=1');
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id']);
        $notifications = array_values(array_filter($notifications, function($n) use ($id) {
            return intval($n['id']) !== $id;
        }));
        file_put_contents($file_path, json_encode($notifications, JSON_PRETTY_PRINT));
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

function get_relative_time($datetime) {
    $time = strtotime($datetime);
    if (!$time) return 'some time ago';
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return round($diff / 60) . ' mins ago';
    if ($diff < 86400) return round($diff / 3600) . ' hours ago';
    return round($diff / 86400) . ' days ago';
}

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

        <!-- Main Content -->
        <div class="faculty-main-content">

            <!-- Topbar Page Title Band -->
            <?php include '../includes/admin-topbar.php'; ?>

            <!-- Content Body -->
            <main class="faculty-content-body">

                <!-- Action alert toast -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border-radius: 12px;">
                        <i class="bi bi-check-circle-fill me-2"></i><strong>Success!</strong> Notification has been published to student feeds.
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Left Column: Publish Form -->
                    <div class="col-lg-5">
                        <div class="faculty-card h-100">
                            <div class="faculty-card-header">
                                <h3 class="faculty-card-title"><i class="bi bi-send-plus-fill text-primary"></i> Publish Notification</h3>
                                <p class="faculty-card-subtitle">Push a new alert or info message to all student consoles</p>
                            </div>
                            <div class="p-3">
                                <form action="admin-notifications.php" method="POST">
                                    <input type="hidden" name="action" value="add">
                                    
                                    <div class="mb-3">
                                        <label for="notifType" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Notification Type</label>
                                        <select name="type" id="notifType" class="form-select" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;" required>
                                            <option value="info">Info (Blue)</option>
                                            <option value="alert">Alert / Critical Warning (Red)</option>
                                            <option value="warning">Academic Warning (Yellow)</option>
                                            <option value="success">Success Status (Green)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notifTitle" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Title / Headline</label>
                                        <input type="text" name="title" id="notifTitle" class="form-control" placeholder="e.g., Attendance Defaulter List Released" style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notifMessage" class="form-label text-light-subtitle" style="font-size: 0.85rem;">Detailed Message</label>
                                        <textarea name="message" id="notifMessage" rows="5" class="form-control" placeholder="Type notification details here..." style="background: rgba(15,23,42,0.4); border: 1px solid rgba(255,255,255,0.08); color: #f1f5f9;" required></textarea>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-premium py-2"><i class="bi bi-send-fill me-1"></i> Broadcast Notification</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Broadcast History -->
                    <div class="col-lg-7">
                        <div class="faculty-card h-100">
                            <div class="faculty-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h3 class="faculty-card-title"><i class="bi bi-broadcast text-primary"></i> Broadcast History</h3>
                                    <p class="faculty-card-subtitle">Active notifications in system</p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5" style="font-size: 0.72rem;"><?php echo count($notifications); ?> Active</span>
                            </div>
                            <div class="p-3">
                                <?php if (empty($notifications)): ?>
                                    <div class="text-center py-5">
                                        <i class="bi bi-chat-left-dots text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-light-subtitle mt-3 mb-0">No active notifications found in system.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column gap-3" id="adminNotificationList">
                                        <?php foreach (array_reverse($notifications) as $n): ?>
                                            <div class="card p-3 admin-notif-item-card" data-id="<?php echo $n['id']; ?>" style="background: <?php echo $n['bg_glow']; ?>; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 12px; transition: all 0.3s ease;">
                                                <div class="d-flex gap-3 align-items-start">
                                                    <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: rgba(255,255,255,0.03); color: <?php echo $n['icon_color']; ?>; border: 1px solid rgba(255,255,255,0.08);">
                                                        <i class="bi <?php echo $n['icon']; ?>" style="font-size: 1.2rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                            <h4 class="h6 fw-bold mb-1 text-white" style="font-family: 'Outfit', sans-serif;">
                                                                <?php echo htmlspecialchars($n['title']); ?>
                                                                <span class="badge bg-secondary-subtle text-light-subtitle ms-2" style="font-size: 0.65rem; text-transform: uppercase;"><?php echo htmlspecialchars($n['type']); ?></span>
                                                            </h4>
                                                            <small style="color: #64748b; font-size: 0.75rem;"><?php echo get_relative_time($n['created_at']); ?></small>
                                                        </div>
                                                        <p class="mb-0 text-light-subtitle mt-1" style="font-size: 0.85rem; color: #94a3b8;"><?php echo htmlspecialchars($n['message']); ?></p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <button class="btn btn-sm btn-outline-danger py-1 px-2 btn-delete-admin-notif" style="font-size: 0.72rem; border-color: rgba(239, 68, 68, 0.15);"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const list = document.getElementById('adminNotificationList');
    if (list) {
        list.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-admin-notif');
            if (!btn) return;
            
            const card = btn.closest('.admin-notif-item-card');
            const id = card.getAttribute('data-id');
            
            if (confirm('Are you sure you want to delete this notification?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                
                fetch('admin-notifications.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            card.remove();
                            if (window.showFacultyToast) {
                                showFacultyToast('Notification deleted successfully.', 'warning');
                            } else {
                                location.reload();
                            }
                        }, 300);
                    }
                });
            }
        });
    }
});
</script>
<script src="<?php echo $base_path; ?>assets/js/dashboard.js"></script>
<?php include '../includes/footer.php'; ?>
